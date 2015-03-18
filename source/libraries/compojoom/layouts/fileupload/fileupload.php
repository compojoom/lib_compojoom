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

$mediaHelper = new JHelperMedia;
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
		<input type="file" name="files[]" id="file-upload-real" multiple>
		<div class="col-lg-12">
			<div class="panel panel-default compojoom-notes">
				<div class="panel-body">
					<!-- The global file processing state -->
					<span class="fileupload-process"><span class="fa fa-spinner fa-pulse"></span></span>
					<?php echo JText::sprintf('LIB_COMPOJOOM_ATTACH_IMAGES_BY_DRAG_DROP_OR', '<span id="file-upload-fake" type="button" class="btn-link">', '</span>'); ?>
					<br/>
					<small class="muted"><?php echo JText::sprintf('LIB_COMPOJOOM_THE_MAXIMUM_FILE_SIZE', $displayData['maxSize'] . 'MB'); ?>
						<?php echo JText::sprintf('LIB_COMPOJOOM_ONLY_FILE_TYPES_ARE_ALLOWED', $displayData['fileTypes']); ?></small>

					<!-- The global progress state -->
					<div class="fileupload-progress fade">
						<!-- The global progress bar -->
						<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
							<div class="progress-bar progress-bar-success" style="width:0%;"></div>
						</div>
						<!-- The extended global progress state -->
						<div class="progress-extended">&nbsp;</div>
					</div>
					<div class="">
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
		</div>
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
         <span class="name"><i>{%=file.name%}</i></span>
            <div class="compojoom-single-file-progress">
	            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
	                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
	            </div>
	           <small><strong class="size"><?php echo JText::_('LIB_COMPOJOOM_PROCESSING'); ?>...</strong></small>
			</div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-default btn-xs start" disabled>
                    <i class="fa fa-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-default btn-xs btn-xs cancel pull-left">
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
        <td style="">
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
        {% if (!file.error) { %}
	        <div class="file-meta">
			    <div class="row">
			        <div class="col-lg-4">
			           <input type="text" class="form-control"
			                placeholder="<?php echo JText::_('LIB_COMPOJOOM_TITLE'); ?>"
							name="<?php echo $displayData['formControl']; ?>[<?php echo $displayData['fieldName']; ?>_data][{%=file.name%}][title]"
					        value="{%=file.title%}" />
			        </div>
			        <div class="col-lg-8">
			            <input type="text" placeholder="<?php echo JText::_('LIB_COMPOJOOM_DESCRIPTION'); ?>" class="form-control"
					                name="<?php echo $displayData['formControl']; ?>[<?php echo $displayData['fieldName']; ?>_data][{%=file.name%}][description]"

					                value="{%=file.description%}" />
			        </div>
			    </div>
	        </div>
		 {% } %}
        {% if (file.error) { %}
            <div><span class="label label-danger">Error</span> {%=file.error%}</div>
        {% } %}
        </td>
        <td style="text-align: center">
            {% if (file.deleteUrl) { %}
                <?php if ($canDelete) : ?>
	                <button class="btn btn-danger btn-xs delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
	                    <i class="fa fa-trash-o"></i>
	                    <span>Delete</span>
	                </button>
	                <div>
		                <small class="size muted">{%=o.formatFileSize(file.size)%}</small>
	                </div>
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
	document.getElementById("file-upload-fake").addEventListener("click", function () {
		document.getElementById("file-upload-real").click();  // trigger the click of actual file upload button
	});

	jQuery(document).ready(function () {
		var $ = jQuery;

		// Initialize the jQuery File Upload widget:
		$('#fileupload').fileupload({
			// Uncomment the following to send cross-domain cookies:
			//xhrFields: {withCredentials: true},
			formData: {},
			autoUpload: true,
			maxFileSize: <?php echo $mediaHelper->toBytes($displayData['maxSize'] . 'M'); ?>,
			maxNumberOfFiles: <?php echo $displayData['maxNumberOfFiles']; ?>,
			url: '<?php echo $displayData['url'] . '&' . JSession::getFormToken(); ?>=1',
			finished: function (e, data) {
				if ($(this).fileupload('option').getNumberOfFiles() >= 10) {
					$('.compojoom-max-number-files').removeClass('hide');
				}
				else {
					$('.compojoom-max-number-files').addClass('hide');
				}
			},
			destroyed: function (e, data) {
				if ($(this).fileupload('option').getNumberOfFiles() >= <?php echo $displayData['maxNumberOfFiles']; ?>) {
					$('.compojoom-max-number-files').removeClass('hide');
				}
				else {
					$('.compojoom-max-number-files').addClass('hide');
				}
			}
		}).on('destroyed', function (e, data) {
			if ($(this).fileupload('option').getNumberOfFiles() >= <?php echo $displayData['maxNumberOfFiles']; ?>) {
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