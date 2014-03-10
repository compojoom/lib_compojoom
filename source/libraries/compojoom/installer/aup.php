<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       10.03.14
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomInstallAUP
 *
 * @since  1.0
 */
class CompojoomInstallerAup
{
	/**
	 * Function to install an AUP rule
	 *
	 * @param   string  $xmlFile  - path to a xml file with the AUP rule
	 *
	 * @return void
	 */
	public static function installRule($xmlFile)
	{
		$xmlDoc = simplexml_load_file($xmlFile);

		if ($xmlDoc)
		{
			if ($xmlDoc->getName() == 'alphauserpoints')
			{
				$element = $xmlDoc->xpath('//rule');
				$ruleName = $element ? (string) $element[0] : '';

				$element = $xmlDoc->xpath('//description');
				$ruleDescription = $element ? (string) $element[0] : '';

				$element = $xmlDoc->xpath('//component');
				$component = $element ? (string) $element[0] : '';
				$element = $xmlDoc->xpath('//plugin_function');
				$pluginFunction = $element ? (string) $element[0] : '';
				$element = $xmlDoc->xpath('//fixed_points');
				$fixedpoints = $element ? (string) $element[0] : '';
				$fixedpoints = (trim(strtolower($fixedpoints)) == 'true') ? 1 : 0;

				if ($ruleName != '' && $ruleDescription != '' && $pluginFunction != '' && $component != '')
				{
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query = $query->select('*')->from($db->qn('#__alpha_userpoints_rules'))
						->where($db->qn('plugin_function') . ' = ' . $db->q($pluginFunction));
					$db->setQuery($query);
					$count = $db->loadResult();


					if (!$count)
					{
						$query->clear();
						$query->insert('#__alpha_userpoints_rules')
							->columns(
								array(
									'id',
									'rule_name',
									'rule_description',
									'rule_plugin',
									'plugin_function',
									'component',
									'fixedpoints',
									'category',
									'access'
								)
							)->values("'', '$ruleName', '$ruleDescription', '$component', '$pluginFunction', '$component', '$fixedpoints', 'fo', 1");
						$db->setQuery($query);
						$db->execute();
					}
				}
			}
		}
	}
}
