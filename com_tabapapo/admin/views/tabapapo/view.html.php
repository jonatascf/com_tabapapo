<?php
/**
 * @package Tabapapo Component for Joomla! 3.9
 * @version 0.7.7
 * @author Jonatas C. Ferreira
 * @copyright (C) 2021 Tabaoca.org
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

class TabaPapoViewTabaPapo extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $script;
	protected $canDo;

	/**
	 * Display the Hello World view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		// Get the Data
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->script = $this->get('Script');


		// What Access Permissions does this user have? What can (s)he do?
		$this->canDo = JHelperContent::getActions('com_tabapapo', 'tabapapo', $this->item->id);

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}


		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolBar()
	{
		$input = JFactory::getApplication()->input;

		// Hide Joomla Administrator Main menu
		$input->set('hidemainmenu', true);

		$isNew = ($this->item->id == 0);

		JToolBarHelper::title($isNew ? JText::_('COM_TABAPAPO_MANAGER_TABAPAPO_NEW')
		                             : JText::_('COM_TABAPAPO_MANAGER_TABAPAPO_EDIT'), 'tabapapo');

		if ($isNew)
		{
			// For new records, check the create permission.
			if ($this->canDo->get('core.create')) 
			{
				JToolBarHelper::apply('tabapapo.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('tabapapo.save', 'JTOOLBAR_SAVE');
				JToolBarHelper::custom('tabapapo.save2new', 'save-new.png', 'save-new_f2.png',
				                       'JTOOLBAR_SAVE_AND_NEW', false);
			}
			JToolBarHelper::cancel('tabapapo.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			if ($this->canDo->get('core.edit'))
			{
				// We can save the new record
				JToolBarHelper::apply('tabapapo.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('tabapapo.save', 'JTOOLBAR_SAVE');
 
				// We can save this record, but check the create permission to see
				// if we can return to make a new one.
				if ($this->canDo->get('core.create')) 
				{
					JToolBarHelper::custom('tabapapo.save2new', 'save-new.png', 'save-new_f2.png',
					                       'JTOOLBAR_SAVE_AND_NEW', false);
				}
 				$config = JFactory::getConfig();
				$save_history = $config->get('save_history', true);
				if ($save_history) 
				{
					JToolbarHelper::versions('com_tabapapo.tabapapo', $this->item->id);
				}
			}
			if ($this->canDo->get('core.create')) 
			{
				JToolBarHelper::custom('tabapapo.save2copy', 'save-copy.png', 'save-copy_f2.png',
				                       'JTOOLBAR_SAVE_AS_COPY', false);
			}
			JToolBarHelper::cancel('tabapapo.cancel', 'JTOOLBAR_CLOSE');
		}
	}
	
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$isNew = ($this->item->id == 0);
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('COM_TABAPAPO_TABAPAPO_CREATING')
		                           : JText::_('COM_TABAPAPO_TABAPAPO_EDITING'));
		$document->addScript(JURI::root() . $this->script);
		$document->addScript(JURI::root() . "/administrator/components/com_tabapapo"
		                                  . "/views/tabapapo/submitbutton.js");
		JText::script('COM_TABAPAPO_TABAPAPO_ERROR_UNACCEPTABLE');
	}
}
