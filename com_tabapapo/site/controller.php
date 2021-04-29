<?php
/**
 * @package Tabapapo Component for Joomla! 3.9
 * @version 0.7.7
 * @author Jonatas C. Ferreira
 * @copyright (C) 2021 Tabaoca.org
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

class TabaPapoController extends JControllerLegacy
{

    public function mensagemEnviar() {
    
        if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, JText::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            parent::display();
        }
    }

    public function mensagemLer() {
    
        if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, JText::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            $this->getmsgsler();
        }
    }

    public function usersLer() {
    
        if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, JText::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            $this->getusersler();
        }
    }

   public function salaEntrar() {
      
      if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, JText::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            $this->getinDisplay();
        }
   }


   public function salaSair() {
      
      if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, JText::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            $this->getoutDisplay();
        }
   }


   public function atualiza___Status($id, $status) {

      // Create an object for the record we are going to update.
      $object = new stdClass();

      // Must be a valid primary key value.
      $object->id = $id;
      $object->status = $status;

      // Update their details in the users table using id as the primary key.
      $result = JFactory::getDbo()->updateObject('#__tabapapo_usu', $object, 'id');

      
   }
    
 	public function getmsgsler()
	{
		$model = $this->getModel();
      $item = $model->getitem();
		if ($item->id > 0)
		{
			$records = $model->msgslerB($item->id);
         
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
			echo new JResponseJson(null, JText::_('COM_TABAPAPO_ERROR_NO_CHAT_BOUNDS'), true);
		}
	}   

 	public function getusersler()
	{
		//$input = JFactory::getApplication()->input;
		//$sala_id = $input->post->get('sl', '0', 'string');
    //  $sala_id = $this->get('item')->id;
//var_dump($sala_id);
		$model = $this->getModel();
      $item = $model->getitem();
		if ($item->id > 0)
		{
			$records = $model->userslerB($item->id);
         
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
			echo new JResponseJson(null, JText::_('COM_TABAPAPO_ERROR_NO_CHAT_BOUNDS'), true);
		}
	}   

    
 	public function getinDisplay()
	{
		//$input = JFactory::getApplication()->input;
		//$sala_id = $input->post->get('sl', '0', 'string');
    //  $sala_id = $this->get('item')->id;
//var_dump($sala_id);
		$model = $this->getModel();
      $item = $model->getitem();
		if ($item->id > 0)
		{
			$record = $model->entrarSalaB($item->id);
			if ($record) 
			{
				echo new JResponseJson($record);
			}
			else
			{
				echo new JResponseJson(null, JText::_('COM_TABAPAPO_ERROR_NO_RECORDS'), true);
			}
		}
		else 
		{
			$record = array();
			echo new JResponseJson(null, JText::_('COM_TABAPAPO_ERROR_NO_CHAT_BOUNDS'), true);
		}
	}   


 	public function getoutDisplay()
	{
		//$input = JFactory::getApplication()->input;
		//$sala_id = $input->post->get('sl', '0', 'string');
    //  $sala_id = $this->get('item')->id;
//var_dump($sala_id);
		$model = $this->getModel();
      $item = $model->getitem();
		if ($item->id > 0)
		{
			$record = $model->sairSalaB($item->id);
			if ($record) 
			{
				echo new JResponseJson($record);
			}
			else
			{
				echo new JResponseJson(null, JText::_('COM_TABAPAPO_ERROR_NO_RECORDS'), true);
			}
		}
		else 
		{
			$record = array();
			echo new JResponseJson(null, JText::_('COM_TABAPAPO_ERROR_NO_CHAT_BOUNDS'), true);
		}
	}   

}