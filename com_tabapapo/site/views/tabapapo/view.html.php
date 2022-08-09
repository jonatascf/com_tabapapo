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
		
     // $tabaform = new TabaPapoModelForm;
      
      // Assign data to the view
		$this->item = $this->get('Item');
		
		$this->options = $this->get('Options');
      
		//$this->table = $this->get('Table');

		if (!$this->form = $this->get('Form'))
		{
			echo "Can't load form<br>";
			return;
		}
		

         
            
      //$this->script = $this->get('Script');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');

			return false;
		}
     
		
      $this->setDocument();
      
      // Display the view
		parent::display($tpl);

	
	}
	

	
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		JHtml::_('jquery.framework');
		$document->setTitle(JText::_('COM_TABAPAPO_TABAPAPO_CHAT'));
		$document->addScript('media/com_tabapapo/js/systabapapo.js' . '?' .JSession::getFormToken());
		$document->addStyleSheet('media/com_tabapapo/css/tabapapo.css' . '?' .JSession::getFormToken());
		
		JText::script('COM_TABAPAPO_MESSAGES_TO');
		JText::script('COM_TABAPAPO_USERSON_ALLUSERS');
		JText::script('COM_TABAPAPO_USERSON_OWNER');
		JText::script('COM_TABAPAPO_TABAPAPOCHAT_TALKINGTO');
		JText::script('COM_TABAPAPO_TABAPAPOCHAT_EVERYBODY');
		JText::script('COM_TABAPAPO_FIELD_TITLE_LABEL');
		JText::script('COM_TABAPAPO_FIELD_CATEGORY_LABEL');
		JText::script('COM_TABAPAPO_FIELD_OWNER_LABEL');
		JText::script('COM_TABAPAPO_FIELD_EDIT_LABEL');
		JText::script('COM_TABAPAPO_FIELD_PRIVATE_LABEL');
		JText::script('COM_TABAPAPO_FIELD_DICE_LABEL');
		JText::script('COM_TABAPAPO_FIELD_USERS_LIMIT_LABEL');
		JText::script('COM_TABAPAPO_YES');
		JText::script('COM_TABAPAPO_NO');
		JText::script('JGLOBAL_USE_GLOBAL');
		JText::script('COM_TABAPAPO_INTERVAL');
		JText::script('COM_TABAPAPO_SEND');
      
      
      //$params = $this->get('enviaParams');
      //$document->addScriptOptions('params', $params);
	}
}
