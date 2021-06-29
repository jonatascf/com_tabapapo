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

    public function userstatus() {
    
        if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, JText::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            $this->getuserstatus();
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
   
    	public function getuserstatus()
	{

		$model = $this->getModel();
      $item = $model->getitem();
      
		if ($item->id > 0)
		{
			$records = $model->userstatusB($item->id);
         
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