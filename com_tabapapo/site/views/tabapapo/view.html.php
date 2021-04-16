<?php
/**
 * @package     Tabapapo.Administrator
 * @subpackage  com_tabapapo
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Form\Form;

/**
 * HTML View class for the TabaPapo Component
 */
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
      
	//	$this->form = $this->get('Form');

		if (!$this->form = $this->get('form'))
		{
			echo "Can't load form<br>";
			return;
		}
         
         
            
      $this->script = $this->get('Script');

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

		$document->setTitle(JText::_('COM_TABAPAPO_TABAPAPO_CREATING'));
      
      $document->addScript('media/com_tabapapo/js/systabapapo.js' . '?' .JSession::getFormToken());
		$document->addStyleSheet('media/com_tabapapo/css/tabapapo.css' . '?' .JSession::getFormToken());
      
      
      
      //$params = $this->get('enviaParams');
      //$document->addScriptOptions('params', $params);
	}
}