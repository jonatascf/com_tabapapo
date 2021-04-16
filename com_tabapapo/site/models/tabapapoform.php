<?php
/**
 * @package     Tabapapo.Administrator
 * @subpackage  com_tabapapo
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\Model\FormModel;

class TabaPapoModelForm extends FormModel
{

	public function getsForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm(
			'com_tabapapo.tabapapo',  // just a unique name to identify the form
			'tabapapo-form',				// the filename of the XML form definition
										// Joomla will look in the models/forms folder for this file
			array(
				'control' => 'jform',	// the name of the array for the POST parameters
				'load_data' => $loadData	// will be TRUE
			)
		);

		if (empty($form))
		{
            $errors = $this->getErrors();
			throw new Exception(implode("\n", $errors), 500);
		}

		return $form;
	}

    protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState(
			'com_tabapapo.tabapapo',	// a unique name to identify the data in the session
			array("reservado" => "0")	// prefill data if no data found in session
		);

		return $data;
	}
   
}
