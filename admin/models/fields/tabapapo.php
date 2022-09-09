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

/**
 * TabaPapo Form Field class for the TabaPapo component
 */
class JFormFieldTabaPapo extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var         string
	 */
	protected $type = 'TabaPapo';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  array  An array of JHtml options.
	 */
	protected function getOptions()
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('h.id, h.title, h.created, h.created_by, h.params, c.title as category,
                         h.alias, h.catid, h.description, h.asset_id, h.published, h.params');
		$query->from('#__tabapapo as h');
		$query->leftJoin('#__categories as c on h.catid=c.id');
		// Retrieve only published items
		$query->where('h.published = 1');
		$db->setQuery((string) $query);
		$messages = $db->loadObjectList();
		$options  = array();

		if ($messages)
		{
			foreach ($messages as $message)
			{
				$options[] = JHtml::_('select.option', $message->id, $message->title);
			}
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}