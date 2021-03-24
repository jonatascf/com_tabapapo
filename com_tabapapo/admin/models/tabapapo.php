<?php
/**
 * @package     Tabapapo.Administrator
 * @subpackage  com_tabapapo
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\Registry\Registry;

/**
 * TabaPapo Model
 *
 * @since  0.0.1
 */
class TabaPapoModelTabaPapo extends JModelAdmin
{
		/**
	 * Method to override getItem to allow us to convert the JSON-encoded image information
	 * in the database record into an array for subsequent prefilling of the edit form
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);
		if ($item AND property_exists($item, 'imagem'))
		{
			$registry = new Registry($item->imagem);
			$item->imageminfo = $registry->toArray();
		}
		return $item; 
	}
	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'TabaPapo', $prefix = 'TabaPapoTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed    A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm(
			'com_tabapapo.tabapapo',
			'tabapapo',
			array(
				'control' => 'jform',
				'load_data' => $loadData
			)
		);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the script that have to be included on the form
	 *
	 * @return string	Script files
	 */
	public function getScript() 
	{
		return 'administrator/components/com_tabapapo/models/forms/tabapapo.js';
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState(
			'com_tabapapo.edit.tabapapo.data',
			array()
		);

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}
	
	/**
	 * Method to check if it's OK to delete a message. Overrides JModelAdmin::canDelete
	 */
	protected function canDelete($record)
	{
		if( !empty( $record->id ) )
		{
			return JFactory::getUser()->authorise( "core.delete", "com_tabapapo.tabapapo." . $record->id );
		}
	}
}
