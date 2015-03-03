<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       10.02.2015
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

$user = JFactory::getUser();

// If the user doesn't have create permissions, then don't show anything
if (!$user->authorise('core.multimedia.create', $displayData['component']))
{
	return;
}

$canDelete = $user->authorise('core.multimedia.delete', $displayData['component']) || $user->authorise('core.multimedia.delete.own', $displayData['component']);

JHtml::stylesheet('media/lib_compojoom/css/jquery.fileupload.css');
JHtml::stylesheet('media/lib_compojoom/css/jquery.fileupload-ui.css');

CompojoomHtmlBehavior::jquery();

JHtml::script('media/lib_compojoom/js/jquery.ui.custom.js');
JHtml::script('http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js');
JHtml::script('media/lib_compojoom/js/load-image.all.min.js');
JHtml::script('media/lib_compojoom/js/jquery.iframe-transport.js');
JHtml::script('media/lib_compojoom/js/jquery.fileupload.js');
JHtml::script('media/lib_compojoom/js/jquery.fileupload-process.js');
JHtml::script('media/lib_compojoom/js/jquery.fileupload-image.js');
JHtml::script('media/lib_compojoom/js/jquery.fileupload-audio.js');
JHtml::script('media/lib_compojoom/js/jquery.fileupload-video.js');

JHtml::script('media/lib_compojoom/js/jquery.fileupload-validate.js');
JHtml::script('media/lib_compojoom/js/jquery.fileupload-ui.js');
?>

<div id="fileupload">
	<!-- Redirect browsers with JavaScript disabled to the origin page -->
	<noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
	<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
	<div class="row fileupload-buttonbar">
		<div class="col-lg-12">
			<!-- The fileinput-button span is used to style the file input field as button -->
            <span class="btn btn-default btn-xs fileinput-button">
                <i class="fa fa-plus-square-o"></i>
                <span><?php echo JText::_('LIB_COMPOJOOM_ADD_FILES'); ?></span>
                <input type="file" name="files[]" multiple>
            </span>
			<button type="submit" class="btn btn-default btn-xs start">
				<i class="fa fa-upload"></i>
				<span><?php echo JText::_('LIB_COMPOJOOM_START_UPLOAD'); ?></span>
			</button>
			<button type="reset" class="btn btn-default btn-xs cancel">
				<i class="fa fa-stop"></i>
				<span><?php echo JText::_('LIB_COMPOJOOM_CANCEL_UPLOAD'); ?></span>
			</button>

			<!-- The global file processing state -->
			<span class="fileupload-process"><span class="fa fa-spinner fa-pulse"></span></span>
		</div>
		<!-- The global progress state -->
		<div class="col-lg-12 fileupload-progress fade">
			<!-- The global progress bar -->
			<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
				<div class="progress-bar progress-bar-success" style="width:0%;"></div>
			</div>
			<!-- The extended global progress state -->
			<div class="progress-extended">&nbsp;</div>
		</div>
	</div>
	<!-- The table listing the files available for upload/download -->
	<div class="row">
		<div class="col-lg-12">
			<div class="alert alert-error hide compojoom-max-number-files">
				<?php echo JText::sprintf('LIB_COMPOJOOM_MAX_NUMBER_OF_FILES_REACHED', $displayData['maxNumberOfFiles']); ?>
			</div>
			<table role="presentation" class="table table-striped">
				<thead></thead>
				<tbody class="files"></tbody>
			</table>
			<div class="alert alert-error hide compojoom-max-number-files">
				<?php echo JText::sprintf('LIB_COMPOJOOM_MAX_NUMBER_OF_FILES_REACHED', $displayData['maxNumberOfFiles']); ?>
			</div>
		</div>
	</div>
</div>
<br>

<div class="panel panel-default compojoom-notes">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo JText::_('LIB_COMPOJOOM_NOTES'); ?></h3>
	</div>
	<div class="panel-body">
		<ul>
			<li><?php echo JText::sprintf('LIB_COMPOJOOM_THE_MAXIMUM_FILE_SIZE', $displayData['maxSize'] .' MB'); ?></li>
			<li><?php echo JText::sprintf('LIB_COMPOJOOM_ONLY_FILE_TYPES_ARE_ALLOWED', $displayData['fileTypes']); ?>
			</li>
			<li><?php echo JText::_('YOU_CAN_DRAG_AND_DROP_FILES'); ?>
			</li>
		</ul>
	</div>
</div>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-default btn-xs start" disabled>
                    <i class="fa fa-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-default btn-xs btn-xs cancel">
                    <i class="fa fa-stop"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>

<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
        {% if (file.thumbnailUrl) { %}
            <span class="preview">
                {% if (file.url) { %}
					<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery>
						<img src="{%=file.thumbnailUrl%}">
					</a>
				{% } else { %}
					<img src="{%=file.thumbnailUrl%}">
				{% } %}
            </span>
			{% } %}
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <?php if($canDelete) : ?>
	                <button class="btn btn-danger btn-xs delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
	                    <i class="fa fa-trash-o"></i>
	                    <span>Delete</span>
	                </button>
                <?php endif; ?>
            {% } else { %}
                 <button class="btn btn-default btn-xs btn-xs cancel">
                    <i class="fa fa-stop"></i>
                    <span>Cancel</span>
                </button>
            {% }%}
            {% if (!file.error) { %}
            <input type="hidden" name="<?php echo $displayData['formControl']; ?>[<?php echo $displayData['fieldName']; ?>][]" value="{%=file.name%}" />
            {% } %}
        </td>
    </tr>
{% } %}

</script>

<script type="text/javascript">
	jQuery(document).ready(function () {
		var $ = jQuery;

		// Initialize the jQuery File Upload widget:
		$('#fileupload').fileupload({
			// Uncomment the following to send cross-domain cookies:
			//xhrFields: {withCredentials: true},
			formData: {},
			autoUpload: true,
			maxNumberOfFiles: <?php echo $displayData['maxNumberOfFiles']; ?>,
			url: '<?php echo $displayData['url'] . '&' . JSession::getFormToken(); ?>=1',
			finished: function(e, data) {
				if($(this).fileupload('option').getNumberOfFiles() >= 10)
				{
					$('.compojoom-max-number-files').removeClass('hide');
				}
				else {
					$('.compojoom-max-number-files').addClass('hide');
				}
			},
			destroyed: function(e, data)
			{
				console.log('destroyed', $(this).fileupload('option').getNumberOfFiles());
				if($(this).fileupload('option').getNumberOfFiles() >= <?php echo $displayData['maxNumberOfFiles']; ?>)
				{
					$('.compojoom-max-number-files').removeClass('hide');
				}
				else {
					$('.compojoom-max-number-files').addClass('hide');
				}
			}
		}).on('destroyed', function(e, data){
			if($(this).fileupload('option').getNumberOfFiles() >= <?php echo $displayData['maxNumberOfFiles']; ?>)
			{
				$('.compojoom-max-number-files').removeClass('hide');
			}
			else {
				$('.compojoom-max-number-files').addClass('hide');
			}
		});

		// Enable iframe cross-domain access via redirect option:
		$('#fileupload').fileupload(
			'option',
			'redirect',
			window.location.href.replace(
				/\/[^\/]*$/,
				'/cors/result.html?%s'
			)
		);

		// Load existing files:
		$('#fileupload').addClass('fileupload-processing');
		$.ajax({
			// Uncomment the following to send cross-domain cookies:
			//xhrFields: {withCredentials: true},
			url: '<?php echo $displayData['url'] . '&' . JSession::getFormToken(); ?>=1&id=<?php echo JFactory::getApplication()->input->get('id'); ?>',
			dataType: 'json',
			context: $('#fileupload')[0]
		}).always(function () {
			$(this).removeClass('fileupload-processing');
		}).done(function (result) {
			$(this).fileupload('option', 'done')
				.call(this, $.Event('done'), {result: result});
		});

	});
</script>