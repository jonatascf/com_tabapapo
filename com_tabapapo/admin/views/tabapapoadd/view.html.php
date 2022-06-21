<?php
/**
 * @package Tabapapo Component for Joomla! 3.9
 * @version 0.8.5
 * @author Jonatas C. Ferreira
 * @copyright (C) 2021 Tabaoca.org
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;


class TabaPapoViewTabaPapoAdd extends JViewLegacy
{
	protected $form;
	protected $item;
    protected $state;
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
		$this->script = $this->get('State');


		// What Access Permissions does this user have? What can (s)he do?
		$this->canDo = JHelperContent::getActions('com_tabapapo', 'tabapapo', $this->item->id);

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
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
		
      Factory::getApplication()->input->set('hidemainmenu', true);
      
  		$user       = Factory::getUser();
		$userId     = $user->id;
		$isNew      = ($this->item->id == 0);
		$checkedOut = !(is_null($this->item->checked_out) || $this->item->checked_out == $userId);

		// Since we don't track these assets at the item level, use the category id.
		$canDo = ContentHelper::getActions('com_contact', 'category', $this->item->catid);


		ToolBarHelper::title($isNew ? Text::_('COM_TABAPAPO_MANAGER_TABAPAPO_NEW')
		                            : Text::_('COM_TABAPAPO_MANAGER_TABAPAPO_EDIT'), 'tabapapo');

		if ($isNew)
		{
			// For new records, check the create permission.
			if (count($user->getAuthorisedCategories('com_tabapapo', 'core.create')) > 0) 
			{
				ToolBarHelper::apply('tabapapoadd.apply');
				
				ToolbarHelper::saveGroup(
					[
						['save', 'tabapapoadd.save'],
						['save2new', 'tabapapoadd.save2new']
					],
					'btn-success'
				);
			}
         
			ToolBarHelper::cancel('tabapapoadd.cancel');
         
		}
		else
		{
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$itemEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId);

			$toolbarButtons = [];

			// Can't save the record if it's checked out and editable
			if (!$checkedOut && $itemEditable)
			{
				ToolbarHelper::apply('tabapapoadd.apply');

				$toolbarButtons[] = ['save', 'tabapapoadd.save'];

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create'))
				{
					$toolbarButtons[] = ['save2new', 'tabapapoadd.save2new'];
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create'))
			{
				$toolbarButtons[] = ['save2copy', 'tabapapoadd.save2copy'];
			}

			ToolbarHelper::saveGroup(
				$toolbarButtons,
				'btn-success'
			);

			ToolbarHelper::cancel('tabapapoadd.cancel', 'JTOOLBAR_CLOSE');

			/*if (ComponentHelper::isEnabled('com_contenthistory') && $this->state->params->get('save_history', 0) && $itemEditable)
			{
				ToolbarHelper::versions('com_tabapapo.chatroom', $this->item->id);
			}*/

			if (Associations::isEnabled() && ComponentHelper::isEnabled('com_associations'))
			{
				ToolbarHelper::custom('tabapapo.editAssociations', 'contract', '', 'JTOOLBAR_ASSOCIATIONS', false, false);
			}
		}

		ToolbarHelper::divider();
		ToolbarHelper::help('JHELP_COMPONENTS_TABAPAPOS_TABAPAPOS_EDIT');
      
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
		$document->addScript(JURI::root() . "/administrator/components/com_tabapapo"
		                                  . "/views/tabapapo/submitbutton.js");
		JText::script('COM_TABAPAPO_TABAPAPO_ERROR_UNACCEPTABLE');
	}
}
