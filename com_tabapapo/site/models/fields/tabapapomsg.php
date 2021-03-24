<?php
/**
 * @package     Tabapapo.Administrator
 * @subpackage  com_tabapapo
 */

defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('list');

/**
 * TabaPapo Form Field class for the TabaPapo component
 */
class JFormFieldTabaPapoMsg extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var         string
	 */
	protected $type = 'TabaPapoMsg';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  array  An array of JHtml options.
	 */
	protected function getOptions()
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('#__tabapapo_msg.id as msg_id, #__tabapapo_msg.reservado as reservado, 
                     #__tabapapo_msg.sala_id as sala_id, #__tabapapo_msg.usu_id as usu_id,
                     #__tabapapo_msg.params as params, #__tabapapo_msg.msg as msg,
                     #__tabapapo_msg.falacom_id as falacom_id, #__tabapapo_msg.tempo as tempo');
		$query->from('#__tabapapo_msg', '#__tabapapo');
		$query->leftJoin('#__tabapapo on sala_id=#__tabapapo.id');
		// Retrieve only published items
		$db->setQuery((string) $query);
		$messages = $db->loadObjectList();
		$options  = array();

		if ($messages)
		{
			foreach ($messages as $message)
			{
				$options[] = JHtml::_('select.option', $message->msg_id, $message->msg);
			}
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}