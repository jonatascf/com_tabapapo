<?php
/**
 * @package Tabapapo Component for Joomla! 3.9
 * @version 0.8.5
 * @author Jonatas C. Ferreira
 * @copyright (C) 2021 Tabaoca.org
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Form\Form;

class TabaPapoViewTabaPapo extends JViewLegacy
{

   
	/**
	 * Display the Tabapapo view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$input = JFactory::getApplication()->input;
		$form = $input->post->get('jform', array(), 'array');
		$model = $this->getModel();
		if ($form)
		{
			$records = $model->enviarMensagem($form);
			if ($records) 
			{
				echo new JResponseJson($records);
			}
			else
			{
				echo new JResponseJson(null, JText::_('COM_TABAPAPO_ERROR_NO_RECORDS'), true);
			}
		}
		else 
		{
			$records = array();
			echo new JResponseJson(null, JText::_('COM_TABAPAPO_ERROR_NO_MAP_BOUNDS'), true);
		}
	}
	

   
}

