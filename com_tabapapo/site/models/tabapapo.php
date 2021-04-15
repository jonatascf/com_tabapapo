<?php
/**
 * @package     Tabapapo.Administrator
 * @subpackage  com_tabapapo
 */

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
		$this->setState('message.id', $id);

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
			$id    = $this->getState('message.id');
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('h.id, h.title, h.params, h.imagem as imagem, c.title as category')
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
				
				// Convert the JSON-encoded image info into an array
				$imagem = new JRegistry;
				$imagem->loadString($this->item->imagem, 'JSON');
				$this->item->imageDetails = $imagem;
			}
		}
		return $this->item;
	}
   
	public function getForm($data = array(), $loadData = false)
	{
		$form = $this->loadForm(
			'com_tabapapo.tabapapoform',  // just a unique name to identify the form
			'tabapapo-form',				// the filename of the XML form definition
										// Joomla will look in the models/forms folder for this file
			array(
				'control' => 'jform',	// the name of the array for the POST parameters
				'load_data' => $loadData	// will be TRUE
			)
		);

//		if (empty($form))
//		{
//            $errors = $this->getErrors();
//			throw new Exception(implode("\n", $errors), 500);
//		}

		return $form;
	}

    protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState(
			'com_tabapapo.tabapapoform',	// a unique name to identify the data in the session
			array("reservado" => "0")	// prefill data if no data found in session
		);

		return $data;
	}

	public function enviarMensagem($form)
	{

         $currentuser = JFactory::getuser();
         $date = new Date();         
         
		try 
		{         
         $msgchat = new stdClass();
			$msgchat->reservado = $form['privado'];
			$msgchat->sala_id = $form['sala_id'];
			$msgchat->usu_id = $currentuser->get("id");
			$msgchat->params =$form['params'];
			$msgchat->msg = $form['msg2'];
			$msgchat->falacom_id = $form['falacom_id'];
			$msgchat->tempo = $date->toSQL(); //+180?

			$resultmsg = JFactory::getDbo()->insertObject('#__tabapapo_msg', $msgchat);
         
//			$db    = JFactory::getDbo();
//			$query = $db->getQuery(true);
//			$query->select('h.usu_id, h.sala_id, h.status')
//			   ->from('#__tabapapo_usu as h')
//			   ->where('h.sala_id = ' . $form['sala_id'] );
//			$db->setQuery($query);
//			$results = $db->loadObjectList(); 

		}
		catch (Exception $e)
		{
			$msg = $e->getMessage();
			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
			$resultmsg = null;
		}

		return $resultmsg; 
	}


	public function  msgslerB($sala_id) {
	   
      $currentuser = JFactory::getuser();

$input = Factory::getApplication()->input;

  $lmsg_id = $input->get('lmsg',0,'INT');
         
         if($currentuser->get("id") > 0){
   		try {  
            $usu_id = $currentuser->get("id");
   			$date = new Date();
            
            

   			$db = JFactory::getDbo();

   			$query = $db->getQuery(true);

   			$query->select($db->quoteName(array('id','sala_id','usu_id','reservado','msg','falacom_id','params','tempo')));
   			$query->from($db->quoteName('#__tabapapo_msg'));
   			$query->where($db->quoteName('sala_id').'='.$db->quote($sala_id));
            $query->where($db->quoteName('id').'>'.$db->quote($lmsg_id));
            $query->order($db->quoteName('id'), 'ASC');
            
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

	public function  userslerB($sala_id) {
	   
      $currentuser = JFactory::getuser();

      $input = Factory::getApplication()->input;
         
         if($currentuser->get("id") > 0){
   		try {  
            $usu_id = $currentuser->get("id");
   			$date = new Date();
            $object = new stdClass();
            $object->id = $usu_id;
            $object->tempo = $date->toSQL();

   			$db = JFactory::getDbo();
   			
            $result = $db->updateObject('#__tabapapo_usu', $object, 'id');
            
            //DELETE users time out
            
            $query = $db->getQuery(true);

   			$query->select($db->quoteName(array('id','sala_id','usu_id','status','ip','params','tempo')));
   			$query->from($db->quoteName('#__tabapapo_usu'));
   			$query->where($db->quoteName('sala_id').'='.$db->quote($sala_id));
            $query->order($db->quoteName('tempo'), 'ASC');
            
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

	public function  entrarSalaB($sala_id) {
	   
      $currentuser = JFactory::getuser();

  
         
         if($currentuser->get("id") > 0){
   		try {  
            $usu_id = $currentuser->get("id");
   			$date = new Date();
            
   			// Create and populate an object.
   			$usuchat = new stdClass();
   			$usuchat->sala_id = $sala_id;
   			$usuchat->usu_id = $usu_id;
   			$usuchat->status ='1';
   			$usuchat->params ='';
   			$usuchat->ip = $_SERVER["REMOTE_ADDR"];
   			$usuchat->tempo = $date->toSQL();  //+30?

   			$resultusu = JFactory::getDbo()->insertObject('#__tabapapo_usu', $usuchat);

   			$db = JFactory::getDbo();

   			$query = $db->getQuery(true);

   			$query->select($db->quoteName(array('id','usu_id')));
   			$query->from($db->quoteName('#__tabapapo_usu'));
   			$query->where($db->quoteName('usu_id').'='.$db->quote($usu_id));

   			$db->setQuery($query);

   			$results = $db->loadObject();
            $userin = ["sala_id" => $sala_id,
                        "msg2" => 'entrou na sala agora',
                        "privado" => 0,
                        "falacom_id" => 0,
                        "params" => ''];
            
            //$this->enviarMensagem($userin);

            
            //$this->enviarMsg($sala_id, $results->usu_id, 'entrou na sala', 0, 0,'');

            return $results->id;
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

   public function atualizarStatus($id, $status) {

      // Create an object for the record we are going to update.
      $object = new stdClass();

      // Must be a valid primary key value.
      $object->id = $id;
      $object->status = $status;

      // Update their details in the users table using id as the primary key.
      $result = JFactory::getDbo()->updateObject('#__tabapapo_usu', $object, 'id');

      
   }
   
   public function atualizarParams($id, $params) {

      // Create an object for the record we are going to update.
      $object = new stdClass();

      // Must be a valid primary key value.
      $object->id = $id;
      $object->params = $params;

      // Update their details in the users table using id as the primary key.
      $result = JFactory::getDbo()->updateObject('#__tabapapo_usu', $object, 'id');

      
   }
   
	public function  sairSalaB($sala_id) {
	   
      $currentuser = JFactory::getuser();
      
         if($currentuser->get("id") > 0){
   		try {      
      
      $db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$conditions = array($db->quoteName('usu_id').' = '. $currentuser->get("id"),
                          $db->quoteName('sala_id').' = '. $sala_id);

		$query->delete($db->quoteName('#__tabapapo_usu'));
		$query->where($conditions);

		$db->setQuery($query);
		$result = $db->execute();

            $userout = ["sala_id" => $sala_id,
                        "msg" => 'saiu na sala.',
                        "privado" => 0,
                        "falacom_id" => 0,
                        "params" => ''];
            
            $this->enviarMensagem($userout);

//      $this->enviarMsg($sala_id, $this->results->usu_id, 'saiu da sala', 0, 0, '');
      
      
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



//colocar na view revisar

   public function getenviaParams()
	{
		if ($this->item) 
		{
			$this->enviaParams = array(
				'id' => $this->item->id,
				'title' => $this->item->title,
				'zoom' => 10,
				'category' => $this->item->category
			);
			return $this->enviaParams; 
		}
		else
		{
			throw new Exception('No enviaParams details available for map', 500);
		}
	}


   
}
