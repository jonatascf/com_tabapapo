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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

class TabaPapoController extends JControllerLegacy
{

    public function salasListar() {
	   
	   if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, Text::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            $input = JFactory::getApplication()->input;
              
            $model = $this->getModel();

            $page_actual = $input->get('page_actual', 0, 'INT');
            $list_limit = $input->get('list_limit', 0, 'INT');
            
      		if ($page_actual)
      		{
      			$record = $model->listarSalas($page_actual, $list_limit);
      			if ($record) 
      			{
      				echo new JResponseJson($record);
      			}
      			else
      			{
      				echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_RECORDS'), true);
      			}
      		}
      		else 
      		{
      			$record = array();
      			echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_CHAT_BOUNDS'), true);
      		}
            
        }
	   
   }

    public function categoriesList() {
	   
	   if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, Text::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            $input = JFactory::getApplication()->input;
              
            $model = $this->getModel();

            $option = $input->get('option', '', 'STRING');
            
      		if ($option == 'com_tabapapo')
      		{
      			$record = $model->listCategories($option);
      			if ($record) 
      			{
      				echo new JResponseJson($record);
      			}
      			else
      			{
      				echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_RECORDS'), true);
      			}
      		}
      		else 
      		{
      			$record = array();
      			echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_CHAT_BOUNDS'), true);
      		}
            
        }
	   
   }


    public function salasN() {
	   
	   if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, Text::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            $input = JFactory::getApplication()->input;
              
            $model = $this->getModel();
            

                $record = $model->nSalas();
                if ($record) 
                {
                        echo new JResponseJson($record);
                }
                else
                {
                        echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_RECORDS'), true);
                }
      		
        }
	   
   }

   
   public function salaEntrar() {
      
      if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, Text::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            //parent::Display();

            $input = JFactory::getApplication()->input;
       		//$form = $input->post->get('jform', array(), 'array');               
            $model = $this->getModel();
            //$item = $model->getitem();
            //$sala_id = $item->id;
            $sala_id = $input->get('sala_id', 0, 'INT');
            
            
      		if ($sala_id)
      		{
      			$record = $model->entrarSalaB($sala_id);
      			if ($record) 
      			{
      				echo new JResponseJson($record);
      			}
      			else
      			{
      				echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_RECORDS'), true);
      			}
      		}
      		else 
      		{
      			$record = array();
      			echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_CHAT_BOUNDS'), true);
      		}

            
        }
   }


    public function mensagemEnviar() {
    
        if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, Text::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            //parent::display();
            
                   
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
       				echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_RECORDS'), true);
       			}
       		}
       		else 
       		{
       			$records = array();
       			echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_CHAT_BOUNDS'), true);
       		}
      
        }
    }


    public function mensagemLer() {
    
        if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, Text::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            //parent::getmsgsler();
            
            $input = JFactory::getApplication()->input;
       		//$form = $input->post->get('jform', array(), 'array');
            $model = $this->getModel();
            //$item = $model->getitem();
            //$item = $form->sala_id;
            $sala_id = $input->get('sala_id', 0, 'int');
            $lmsg_id = $input->get('lmsg_id', 0, 'int');
            
      		if ($sala_id)
      		{
      			$records = $model->msgslerB($sala_id, $lmsg_id);
               
      			if ($records) 
      			{
      				echo new JResponseJson($records);
      			}
      			else
      			{
      				echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_RECORDS'), true);
      			}
      		}
      		else 
      		{
      			$records = array();
      			echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_CHAT_BOUNDS'), true);
      		}

        }
    }

    public function usersLer() {
    
        if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, Text::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            //parent::getusersler();
            
            $input = JFactory::getApplication()->input;
       		//$form = $input->post->get('jform', array(), 'array');
            $model = $this->getModel();
            //$item = $model->getitem();
            //$item = $form->sala_id;
            $sala_id = $input->get('sala_id', 0, 'int');
                        
      		if ($sala_id)
      		{
      			$records = $model->userslerB($sala_id);
               
      			if ($records) 
      			{
      				echo new JResponseJson($records);
      			}
      			else
      			{
      				echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_RECORDS'), true);
      			}
      		}
      		else 
      		{
      			$records = array();
      			echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_CHAT_BOUNDS'), true);
      		}
      
        }
    }

    public function userstatus() {
    
        if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, Text::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            //parent::getuserstatus();
            
            $input = JFactory::getApplication()->input;
       		//$form = $input->post->get('jform', array(), 'array');                   
            $model = $this->getModel();
            //$item = $model->getitem();
            $sala_id = $input->get('sala_id', 0, 'INT');
            $status = $input->get('status', 0, 'INT');
                         
       		if ($sala_id)
       		{
       			$records = $model->statususer($sala_id, $status);
                
       			if ($records) 
       			{
       				echo new JResponseJson($records);
       			}
       			else
       			{
       				echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_RECORDS'), true);
       			}
       		}
       		else 
       		{
       			$records = array();
       			echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_CHAT_BOUNDS'), true);
       		}

        }
    }


    public function rolldice() {
    
        if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, Text::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            //parent::getuserstatus();
            
            $input = JFactory::getApplication()->input;
       		//$form = $input->post->get('jform', array(), 'array');                   
            $model = $this->getModel();
            //$item = $model->getitem();
            $sala_id = $input->get('sala_id', 0, 'INT');
            $dice = $input->get('dice', 0, 'INT');
                         
       		if ($sala_id)
       		{
       			$records = $model->diceroll($sala_id, $dice);
                
       			if ($records) 
       			{
       				echo new JResponseJson($records);
       			}
       			else
       			{
       				echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_RECORDS'), true);
       			}
       		}
       		else 
       		{
       			$records = array();
       			echo new JResponseJson(null, Text::_('COM_TABAPAPO_ERROR_NO_CHAT_BOUNDS'), true);
       		}

        }
    }


   public function exitRoom() {
      
      if (!JSession::checkToken('get')) 
        {
            echo new JResponseJson(null, Text::_('JINVALID_TOKEN'), true);
        }
        else 
        {

            //parent::getoutDisplay();

            $input = JFactory::getApplication()->input;
       		//$form = $input->post->get('jform', array(), 'array');             
            $model = $this->getModel();
            //$item = $model->getitem();
            $sala_id = $input->get('sala_id', 0, 'INT');
            
      		if ($sala_id)
      		{
      			$record = $model->roomExit($sala_id);
      			if ($record == $sala_id) 
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


}
