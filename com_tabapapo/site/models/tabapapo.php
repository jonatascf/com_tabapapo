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
use Joomla\CMS\MVC\Model\FormModel;
use Joomla\CMS\Date\Date;

class TabaPapoModelTabaPapo extends FormModel
{

	/**
	 * @var object item
	 */
	protected $item;
   //protected $form;

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	2.5
	 */
	protected function populateState()
	{
		// Get the message id
		$jinput = JFactory::getApplication()->input;
		$id     = $jinput->get('id', 1, 'INT');
		$this->setState('chatroom.id', $id);

		// Load the parameters.
		$this->setState('params', JFactory::getApplication()->getParams());
		parent::populateState();
	}
	
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

	public function getScript() 
	{
		return '/media/com_tabapapo/js/systabapapo.js';
	}
	
	/**
	 * Get the message
	 * @return object The message to be displayed to the user
	 */
	public function getItem()
	{
		if (!isset($this->item)) 
		{
			$id    = $this->getState('chatroom.id');
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('h.id, h.title, h.created, h.created_by, h.params, c.title as category,
                         h.alias, h.catid, h.description, h.asset_id, h.published, h.params')
				  ->from('#__tabapapo as h')
				  ->leftJoin('#__categories as c ON h.catid=c.id')
				  ->where('h.id=' . (int)$id);
			$db->setQuery((string)$query);
		
			if ($this->item = $db->loadObject()) 
			{
				// Load the JSON string
				$params = new JRegistry;
				$params->loadString($this->item->params, 'JSON');
				$this->item->params = $params;

				// Merge global params with item params
				$params = clone $this->getState('params');
				$params->merge($this->item->params);
				$this->item->params = $params;
				
			}
		}
		return $this->item;
	}
   
	public function getForm($data = array(), $loadData = false)
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

		return $form;
	}

    protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState(
			'com_tabapapo.tabapapoform',	// a unique name to identify the data in the session
			array()	// prefill data if no data found in session
		);

		return $data;
	}


	public function entrarSalaB($sala_id) {
	   
      $currentuser = JFactory::getuser();

      if ($currentuser->get("id") > 0) {
      
		try {  
      
         $usu_id = $currentuser->get("id");
         $usu_name = $currentuser->get("username");
         //$sala_id = $form['sala_id'];
        
         $delusers = $this->deleteUsers($sala_id,'no-msg');

         $db = JFactory::getDbo();

			$query = $db->getQuery(true);

			$query->select($db->quoteName(array('id','usu_id')));
			$query->from($db->quoteName('#__tabapapo_usu'));
			$query->where($db->quoteName('usu_id').'='.$db->quote($usu_id));
         $query->where($db->quoteName('sala_id').'='.$db->quote($sala_id));

			$db->setQuery($query);
         $db->execute();
         $num_rows = $db->getNumRows();
			$results = $db->loadObjectlist();
         
         if ($num_rows > 0) {

            $db = JFactory::getDbo();
            
      		$querydel = $db->getQuery(true);

      		$querydel->delete($db->quoteName('#__tabapapo_usu'));
   			$querydel->where($db->quoteName('usu_id').'='.$db->quote($usu_id));
            $querydel->where($db->quoteName('sala_id').'='.$db->quote($sala_id));

      		$db->setQuery($querydel);
      		$result = $db->execute();

            $this->insert_user($sala_id);

			}
         else {
         
            $this->insert_user($sala_id);
            
         }

         return true;
      }
        
		catch (Exception $e)
		{
			$msg = $e->getMessage();
			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
			$resultmsg = null;
		  }
        
   	}
		else {
			header('Location:index.php');
		}
 
	}

	public function enviarMensagem($form) {

      $currentuser = JFactory::getuser();
      $usu_id = $currentuser->get("id");
      $sala_id = $form['sala_id'];
      
      $db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select($db->quoteName(array('id','usu_id')));
		$query->from($db->quoteName('#__tabapapo_usu'));
		$query->where($db->quoteName('usu_id').'='.$db->quote($usu_id));
      $query->where($db->quoteName('sala_id').'='.$db->quote($sala_id));

		$db->setQuery($query);
      $db->execute();
      $num_rows = $db->getNumRows();
		$results = $db->loadObjectlist();
      
      if ($num_rows == null){
      
         $this->insert_user($sala_id);
         
      }

      $db = JFactory::getDbo();

		$querynow = $db->getQuery(true);
		$querynow->select('now() as now');
		$db->setQuery($querynow);
      $resultnow = $db->loadObjectList();
      
      $params = new stdClass();
      $params->usu_name = $currentuser->get("username");
      $params->talkto_name = $form['talkto_name'];
      $params = json_encode($params);
      
      $mensagem = $form['msg'];
      
      if (trim($mensagem) == '') {
         
         return false;
      }
      else {
         
         $algoritmo = "AES-256-CTR";
         $key = $form['sala_id']."-salak";
         $iv = "ZZqZswppWgNrNQmt";
         $tag = "";
         $tag_length = 16;

         $mensagem = openssl_encrypt($mensagem, $algoritmo, $key, OPENSSL_RAW_DATA, $iv, $tag, "", $tag_length);
         $mensagem = base64_encode($mensagem);
         
   		try 
   		{         
                    
            $msgchat = new stdClass();
   			
   			$msgchat->sala_id = $form['sala_id'];
   			$msgchat->usu_id = $currentuser->get("id");
   			$msgchat->params = $params;
   			$msgchat->msg = $mensagem;
            $msgchat->reservado = $form['privado'];
   			$msgchat->falacom_id = $form['talkto_id'];
   			$msgchat->tempo = $resultnow[0]->now;

   			$resultmsg = JFactory::getDbo()->insertObject('#__tabapapo_msg', $msgchat);
            
   		}
   		catch (Exception $e)
   		{
   			$msg = $e->getMessage();
   			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
   			$resultmsg = null;
   		}

   		return $resultmsg;
      } 
	}

	public function enviarMensagemSys($form, $type)	{

      $currentuser = JFactory::getuser();

      $db = JFactory::getDbo();

		$querynow = $db->getQuery(true);
		$querynow->select('now() as now');
		$db->setQuery($querynow);
      $resultnow = $db->loadObjectList();

      $params = new stdClass();
      $params->usu_name = '>';
      $params->talkto_name = $currentuser->get('username');
      $params = json_encode($params);
            
      try 
		{         
         $msgchat = new stdClass();
			
         if ($type == 1) {
            $msgchat->msg = $form->params.' entered.';
         }
         
         if ($type == 2) {
            $msgchat->msg = $form->params.' left.';
         }
         
         if ($type == 3) {
            $msgchat->msg = $form->msg;
         }
         
         $algoritmo = "AES-256-CTR";
         $key = $form->sala_id."-salak";
         $iv = "ZZqZswppWgNrNQmt";
         $tag = "";
         $tag_length = 16;

         $msgchat->msg = openssl_encrypt($msgchat->msg, $algoritmo, $key, OPENSSL_RAW_DATA, $iv, $tag, "", $tag_length);
         $msgchat->msg = base64_encode($msgchat->msg);

         $msgchat->reservado = 0;
			$msgchat->sala_id = $form->sala_id;
			$msgchat->usu_id = 0; //System Id
			$msgchat->params = $params;
   		$msgchat->falacom_id = 0;
			$msgchat->tempo = $resultnow[0]->now;

			$resultmsg = JFactory::getDbo()->insertObject('#__tabapapo_msg', $msgchat);
         
		}
		catch (Exception $e)
		{
			$msg = $e->getMessage();
			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
			$resultmsg = null;
		}

		return $resultmsg; 
	}

	public function msgslerB($sala_id, $lmsg_id) {
	   
      $currentuser = JFactory::getuser();

      //$sala_id = $form['sala_id'];
           
         if($currentuser->get("id") > 0){
         
   		try {
         
   			$db = JFactory::getDbo();
            
            $delmsgs = $this->deleteMsgs($sala_id);
         
   			$query = $db->getQuery(true);

   			$query->select($db->quoteName(array('id','sala_id','usu_id','reservado','msg','falacom_id','params','tempo')));
   			$query->from($db->quoteName('#__tabapapo_msg'));
   			$query->where($db->quoteName('sala_id').'='.$db->quote($sala_id));
            $query->where($db->quoteName('id').'>'.$db->quote($lmsg_id));
            $query->order($db->quoteName('id'), 'ASC');
            
   			$db->setQuery($query);
            $db->execute();
            $num_rows = $db->getNumRows();
            $results = $db->loadObjectList();
            
            if ($num_rows > 0) {
               
               for ($i = 0; $i < $num_rows; $i++) {
                  
                  $mensagem = $results[$i]->msg;
                  $algoritimo = "AES-256-CTR";
                  $key = $sala_id."-salak";
                  $iv = "ZZqZswppWgNrNQmt";
                  
                  $mensagem = openssl_decrypt(base64_decode($mensagem), $algoritimo, $key, OPENSSL_RAW_DATA, $iv);
                  $results[$i]->msg = $mensagem;
                  
               }
            
            }
            
            return $results;
            
           }
           
   		catch (Exception $e)
   		{
   			$msg = $e->getMessage();
   			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
   			$resultmsg = null;
   		  }
           
      	}
   		else {
   			header('Location:index.php');
   		}
      

	}

	public function userslerB($sala_id) {
	   
      $currentuser = JFactory::getuser();

      //$sala_id = $form['sala_id'];   
      
         if($currentuser->get("id") > 0){
         
   		try {

   			$db = JFactory::getDbo();

            $id = $this->atualizarTempo($currentuser->get("id"), $sala_id);

            $delusers = $this->deleteUsers($sala_id, 'yes-msg');
            
            $query = $db->getQuery(true);

   			$query->select($db->quoteName(array('id','sala_id','usu_id','status','ip','params','tempo')));
   			$query->from($db->quoteName('#__tabapapo_usu'));
   			$query->where($db->quoteName('sala_id').'='.$db->quote($sala_id));
            $query->order($db->quoteName('params'), 'ASC');
            
   			$db->setQuery($query);
            $results = $db->loadObjectList();
            
            return $results;
           }
           
   		catch (Exception $e)
   		{
   			$msg = $e->getMessage();
   			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
   			$resultmsg = null;
   		  }
           
      	}
   		else {
   			header('Location:index.php');
   		}
      
	}
   
   public function statususer($sala_id, $status) {

      $currentuser = JFactory::getuser();
      $usu_id = $currentuser->get("id");
      
      if($currentuser->get("id") > 0) {
         
   		try {
      
            $db = JFactory::getDbo();
            $id = $this->selectId($usu_id, $sala_id);

            $object = new stdClass();
            $object->id = $id[0]->id;
            $object->status = $status;

            $result = $db->updateObject('#__tabapapo_usu', $object, 'id');
            
            return true;
         }
           
   		catch (Exception $e)
   		{
   			$msg = $e->getMessage();
   			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
   			$resultmsg = null;
   		  }
           
      	}
   		else {
   			header('Location:index.php');
   		}

   }


   public function diceroll($sala_id, $dice) {

      $currentuser = JFactory::getuser();
      
      if($currentuser->get("id") > 0) {
         
   		try {
      

            $object = new stdClass();
            $object->sala_id = $sala_id;
            $object->dice = $dice;
            $object->msg = $currentuser->get("username").' roll D' . $dice . ': ' . '<b>[ ' . rand(1, $dice). ' ]</b>';

            $this->enviarMensagemSys($object, 3);
            
            return true;
         }
           
   		catch (Exception $e)
   		{
   			$msg = $e->getMessage();
   			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
   			$resultmsg = null;
   		  }
           
      	}
   		else {
   			header('Location:index.php');
   		}

   }


	public function insert_user($sala_id) {

      $currentuser = JFactory::getuser();

      if ($currentuser->get("id") > 0) {
      
		try {  

         $usu_id = $currentuser->get("id");
         $usu_name = $currentuser->get("username");
        
         $db = JFactory::getDbo();
   		$querynow = $db->getQuery(true);
   		$querynow->select('now() as now');
   		$db->setQuery($querynow);
         $result = $db->loadObjectList();

         $usuchat = new stdClass();
      	$usuchat->sala_id = $sala_id;
      	$usuchat->usu_id = $usu_id;
      	$usuchat->status = 1;
      	$usuchat->params = $usu_name;
      	$usuchat->ip = $_SERVER["REMOTE_ADDR"];
      	$usuchat->tempo = $result[0]->now;

      	$resultusu = JFactory::getDbo()->insertObject('#__tabapapo_usu', $usuchat);

         $this->enviarMensagemSys($usuchat,1);
         
         return true;

      }
        
		catch (Exception $e)
		{
			$msg = $e->getMessage();
			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
			$resultmsg = null;
		  }
        
   	}
		else {
			header('Location:index.php');
		}

   }

   public function selectId($usu_id, $sala_id) {

		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select($db->quoteName(array('id','usu_id')));
		$query->from($db->quoteName('#__tabapapo_usu'));
		$query->where($db->quoteName('usu_id').'='.$db->quote($usu_id));
		$query->where($db->quoteName('sala_id').'='.$db->quote($sala_id));

		$db->setQuery($query);

		$results = $db->loadObjectList();

      return $results;

   }
   
   public function atualizarTempo($usu_id, $sala_id) {

      $db = JFactory::getDbo();
      
      $id = $this->selectId($usu_id, $sala_id);

      $db = JFactory::getDbo();

		$querynow = $db->getQuery(true);

		$querynow->select('now() as now');
		
		$db->setQuery($querynow);
            			
      $resultnow = $db->loadObjectList();

      $object = new stdClass();

      $object->id = $id[0]->id;
      $object->tempo = $resultnow[0]->now;

      $result = $db->updateObject('#__tabapapo_usu', $object, 'id');

      return true;

   }

   public function atualizarParams($id, $params) {

      $object = new stdClass();

      $object->id = $id;
      $object->params = $params;

      $result = JFactory::getDbo()->updateObject('#__tabapapo_usu', $object, 'id');

   }
   
	public function  roomExit($sala_id) {
	   
      $currentuser = JFactory::getuser();
      $usu_id = $currentuser->get("id");
      $usu_name = $currentuser->get("username");;
         
      if ($usu_id > 0) {
   		try {      
      
            $db = JFactory::getDbo();
            
            $id = $this->selectId($usu_id, $sala_id);

      		$query = $db->getQuery(true);

      		$conditions = array($db->quoteName('id').' = '. $id[0]->id);

      		$query->delete($db->quoteName('#__tabapapo_usu'));
      		$query->where($conditions);

      		$db->setQuery($query);
      		$result = $db->execute();

            $userout = new stdClass();
            $userout->sala_id = $sala_id;
            $userout->privado = 0;
            $userout->falacom_id = 0;
            $userout->params = $usu_name;
            
            $msg = $this->enviarMensagemSys($userout, 2); //msg type 2 exit

         return true;	

           }
           
   		catch (Exception $e)
   		{
   			$msg = $e->getMessage();
   			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
   			$resultmsg = null;
   		  }
           
      	}
   		else {
   			header('Location:index.php');
   		}

	}


	public function deleteUsers($sala_id, $type) {

		try {      
   
         $db = JFactory::getDbo();
         
         $querylist = $db->getQuery(true);
         
         $querylist->select($db->quoteName(array('id','sala_id','usu_id','status','ip','params','tempo')));
			$querylist->from($db->quoteName('#__tabapapo_usu'));
			$querylist->where($db->quoteName('sala_id').'='.$db->quote($sala_id));
   		$querylist->where('timestampdiff(second, tempo, now()) > 30');
         
         $db->setQuery($querylist);
         $db->execute();
         
         $num_rows = $db->getNumRows();
         $results = $db->loadObjectList();
         
         if ($num_rows) {
            
            $db = JFactory::getDbo();
            
      		$query = $db->getQuery(true);

      		$query->delete($db->quoteName('#__tabapapo_usu'));
      		$query->where('timestampdiff(second, tempo, now()) > 30');

      		$db->setQuery($query);
      		$result = $db->execute();

				if ($type == 'yes-msg') {
            
            	for ($i = 0; $i < $num_rows; $i++) {
            
               	$userout = new stdClass();
               	$userout->sala_id = $sala_id;
               	$userout->privado = 0;
               	$userout->falacom_id = 0;
               	$userout->params = $results[$i]->params;

               	$msgout = $this->enviarMensagemSys($userout,2);
               
            	}
         	}
         }
         return true;	

        }
        
		catch (Exception $e)
		{
			$msg = $e->getMessage();
			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
			$resultmsg = null;
		  }

	}
   
	public function deleteMsgs($sala_id) {
	   
		try {      
   
         $db = JFactory::getDbo();
         
   		$query = $db->getQuery(true);

   		$query->delete($db->quoteName('#__tabapapo_msg'));
   		$query->where('timestampdiff(second, tempo, now()) > 300');

   		$db->setQuery($query);
   		$result = $db->execute();

         return true;	

        }
        
		catch (Exception $e)
		{
			$msg = $e->getMessage();
			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
			$resultmsg = null;
		  }

	}

}
