<?php
/**
 * @package Tabapapo Component for Joomla! 3.9
 * @version 0.8.5
 * @author Jonatas C. Ferreira
 * @copyright (C) 2021 Tabaoca.org
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

class TabaPapoModelTabaPapo extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'alias', 'a.alias',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'catid', 'a.catid', 'category_id', 'category_title',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'ordering', 'a.ordering',
				'featured', 'a.featured',
				'language', 'a.language', 'language_title',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down',
				'published', 'a.published',
            'hits', 'a.hits',
				'tag',
				'level', 'c.level',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */


	public function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('a.id as id, a.title as title, a.alias as alias, a.published as published, a.created as created, a.hits as hits, a.params as params')
			  ->from($db->quoteName('#__tabapapo', 'a'))
			  ->group('a.id');

		// Join over the users on.
		$query->select('count(o.sala_id) as users_on')
			->join('LEFT', $db->quoteName('#__tabapapo_usu', 'o') . ' ON o.sala_id = a.id');

		// Join over the categories.
		$query->select($db->quoteName('c.title', 'category_title'))
			->join('LEFT', $db->quoteName('#__categories', 'c') . ' ON c.id = a.catid');

		// Join with users table to get the username of the author
		$query->select($db->quoteName('u.username', 'created_by'))
			->join('LEFT', $db->quoteName('#__users', 'u') . ' ON u.id = a.created_by');

		// Filter: like / search
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$like = $db->quote('%' . $search . '%');
			$query->where('title LIKE ' . $like);
		}

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published IN (0, 1))');
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'title');
		$orderDirn 	= $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

		return $query;
	}
	

	protected $item;
	protected $options;
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
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Get the message id
		$jinput = JFactory::getApplication()->input;
		$id     = $jinput->get('id', 1, 'INT');
		$this->setState('chatroom.id', $id);

		// Load the parameters.
		$this->setState('params', JFactory::getApplication()->getParams());
		parent::populateState();
	}
	
	public function getItem()
	{
		if (!isset($this->item)) 
		{
			$id    = $this->getState('chatroom.id');
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('h.id, h.title, h.created, h.created_by, h.params, c.title as category,
                         h.alias, h.catid, h.description, h.asset_id, h.published, h.params, count(o.sala_id) as users_on')
				  ->from('#__tabapapo as h')
				  ->leftJoin('#__tabapapo_usu as o ON h.id=o.sala_id')
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
	
    public function getOptions () {
      
      $currentuser = JFactory::getuser();
      $usu_id = $currentuser->get("id");
      //$sala_id = $form['sala_id'];
      
      $db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select($db->quoteName(array('id', 'title', 'catid', 'created_by')));
		$query->from($db->quoteName('#__tabapapo'));
		//$query->where($db->quoteName('usu_id').'='.$db->quote($usu_id));
		//$query->where($db->quoteName('sala_id').'='.$db->quote($sala_id));

		$db->setQuery($query);
      //$db->execute();
      //$num_rows = $db->getNumRows();
		$this->options = $db->loadObjectlist();
		
      return $this->options;
      
    }


    public function listarSalas ($page_actual, $list_limit) {
		
      $currentuser = JFactory::getuser();
      $usu_id = $currentuser->get("id");
      //$sala_id = $form['sala_id'];
      
      $start_limit = ($page_actual * $list_limit) - $list_limit;
      
      $db = JFactory::getDbo();

		$query = $db->getQuery(true);
		
		//$query->select($db->quoteName('a.id'), 'id');
		//$query->select($db->quoteName('a.title'), 'title');
		//$query->select($db->quoteName('a.catid'), 'cat_title');
		//$query->from($db->quoteName('#__tabapapo', 'a'));
		//$query->join('LEFT', $db->quoteName('#__categories', 'd') . ' ON ' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('d.id'));
		//$query->where($db->quoteName('usu_id').'='.$db->quote($usu_id));
		//$query->where($db->quoteName('sala_id').'='.$db->quote($sala_id));

		$db->setQuery($this->getListQuery());
		$db->execute();
		
		$rows[0]= $db->getNumRows();
		
		$db->setQuery($this->getListQuery(), $start_limit, $list_limit);
		
		$rows[1] = $db->loadObjectlist();
		
		return $rows;
	}

  public function nSalas () {
		
      $currentuser = JFactory::getuser();
      $usu_id = $currentuser->get("id");
      //$sala_id = $form['sala_id'];
      
      $db = JFactory::getDbo();

      $query = $db->getQuery(true);

      $query->select($db->quoteName(array('id', 'title', 'catid', 'created_by')));
      $query->from($db->quoteName('#__tabapapo'));
      //$query->where($db->quoteName('usu_id').'='.$db->quote($usu_id));
      //$query->where($db->quoteName('sala_id').'='.$db->quote($sala_id));

      $db->setQuery($query);
      $db->execute();
      $num_rows = $db->getNumRows();
      //$rows = $db->loadObjectlist();
      
      return $num_rows;
  }


	
	public function entrarSalaB($sala_id) {
	   
      $currentuser = JFactory::getuser();

      if ($currentuser->get("id") > 0) {
      
		try {  
      
         $usu_id = $currentuser->get("id");
         $usu_name = $currentuser->get("username");
        
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
            
         return $sala_id;	

           }
           
   		catch (Exception $e)
   		{
   			$msg = $e->getMessage();
   			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
   			$resultmsg = null;
   		  }
           
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
