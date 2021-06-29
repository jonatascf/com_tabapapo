<?php
/**
 * @package Tabapapo Component for Joomla! 3.9
 * @version 0.8.5
 * @author Jonatas C. Ferreira
 * @copyright (C) 2021 Tabaoca.org
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('list');

class JFormFieldTabaPapoUsu extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var         string
	 */
	protected $type = 'TabaPapoUsu';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  array  An array of JHtml options.
	 */
	protected function getOptions()
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('#__tabapapo_usu.id as tusu_id, #__tabapapo_usu.usu_id as usu_id, 
      #__tabapapo_usu.sala_id as sala_id, #__tabapapo_usu.status as status, #__tabapapo_usu.params as params,
      #__tabapapo_usu.ip as ip, #__tabapapo_usu.tempo as tempo');
		$query->from('#__tabapapo_usu', '#__tabapapo');
		$query->leftJoin('#__tabapapo on sala_id=#__tabapapo.id');
		// Retrieve only published items
		//$query->where('#__tabapapo_usu.sala_id = 1');
		$db->setQuery((string) $query);
		$users = $db->loadObjectList();
		$options  = array();

		if ($users)
		{
			foreach ($users as $user)
			{
				$options[] = JHtml::_('select.option', $user->tusu_id, $user->usu_id);
			}
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}