<?php
/**
 * @package Tabapapo Component for Joomla! 3.9
 * @version 0.8.5
 * @author Jonatas C. Ferreira
 * @copyright (C) 2021 Tabaoca.org
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

class TabaPapoModelForm extends JModelAdmin
{

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
			'com_tabapapo.form',
			'add-form',
			array(
				'control' => 'jform',
				'load_data' => $loadData
			)
		);

		if (empty($form))
		{
			$errors = $this->getErrors();
			throw new Exception(implode("\n", $errors), 500);
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 * As this form is for add, we're not prefilling the form with an existing record
	 * But if the user has previously hit submit and the validation has found an error,
	 *   then we inject what was previously entered.
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

		return $data;
	}
    
	/**
	 * Method to get the script that have to be included on the form
	 * This returns the script associated with tabapapo field room validation
	 *
	 * @return string	Script files
	 */
	public function getScript() 
	{
		return 'administrator/components/com_tabapapo/models/forms/tabapapo.js';
	}
}