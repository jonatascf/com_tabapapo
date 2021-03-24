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
      
      $document->addScript('media/com_tabapapo/js/systabapapo.js');
		$document->addStyleSheet('media/com_tabapapo/css/tabapapo.css');
      
      
      
      //$params = $this->get('enviaParams');
      //$document->addScriptOptions('params', $params);
	}
}

class conexoesTabapapo {
	
	protected $currentuser;
	protected $results;
	public $mensagens;
   public $usuarios;
	
	public function  entrarSala($sala_id) {
	   
      $currentuser = JFactory::getuser();
         
      if($currentuser->get("id") > 0){
			
         $usu_id = $currentuser->get("id");
			$date = new Date();
         
			// Create and populate an object.
			$usuchat = new stdClass();
			$usuchat->sala_id = $sala_id;
			$usuchat->usu_id = $usu_id;
			$usuchat->status ='on';
			$usuchat->params ='cor #006699';
			$usuchat->ip = $_SERVER["REMOTE_ADDR"];
			$usuchat->tempo = $date->toSQL();  //+30?

			$resultusu = JFactory::getDbo()->insertObject('#__tabapapo_usu', $usuchat);

			$db = JFactory::getDbo();

			$query = $db->getQuery(true);

			$query->select($db->quoteName(array('id','usu_id')));
			$query->from($db->quoteName('#__tabapapo_usu'));
			$query->where($db->quoteName('usu_id').'='.$db->quote($usu_id));

			$db->setQuery($query);

			$this->results = $db->loadObject();

         $this->enviarMsg($sala_id, $this->results->usu_id, 'entrou na sala', 0, 0,'');

         return $this->results->id;

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


   public function listarUsuarios($sala_id) {
      
      $db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select($db->quoteName(array('id', 'sala_id', 'usu_id', 'status', 'params', 'tempo')));
		$query->from($db->quoteName('#__tabapapo_usu'));
		$query->where($db->quoteName('sala_id').'='.$db->quote($sala_id));

		$db->setQuery($query);
      $db->execute();
      $nrows = $db->getNumRows();
		
      $this->usuarios = $db->loadObjectList();

      return $nrows;      
      
   }


   public function escreverMsg($typemsg) {
      
      
      
   }

   public function lerMsgs($sala_id) {
      
      $db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select($db->quoteName(array('id','usu_id','msg','falacom_id','reservado','params')));
		$query->from($db->quoteName('#__tabapapo_msg'));
		$query->where($db->quoteName('sala_id').'='.$db->quote($sala_id));

		$db->setQuery($query);
      $db->execute();
      $nrows = $db->getNumRows();
		
      $this->mensagens = $db->loadObjectList();

      return $nrows;
      
   }

   public function enviarMsg($sala_id, $usu_id, $msg, $reservado, $falacom_id, $params) {   
      
      $currentuser = JFactory::getuser();
      
      if($usu_id > 0){
         
         $date = new Date();         
         
         $msgchat = new stdClass();
			$msgchat->reservado = $reservado;
			$msgchat->sala_id = $sala_id;
			$msgchat->usu_id = $usu_id;
			$msgchat->params =$params;
			$msgchat->msg = $msg;
			$msgchat->falacom_id = $falacom_id;
			$msgchat->tempo = $date->toSQL(); //+180?

			$resultmsg = JFactory::getDbo()->insertObject('#__tabapapo_msg', $msgchat);
         
         $msg_out = '<div>'.$currentuser->get("username").' fala reservadamente com '.$msgchat->falacom_id.' '.$msgchat->msg. '</div>';
         
         return $msg_out;
         }
		else {
			header('Location:index.php');
		}
   }
	
    public function apagarMsg() {
      
   }
   
     
    public function sairSala($sala_id, $usu_id) {
		
      $db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$conditions = array($db->quoteName('usu_id').' = '. $usu_id,
                          $db->quoteName('sala_id').' = '. $sala_id);

		$query->delete($db->quoteName('#__tabapapo_usu'));
		$query->where($conditions);

		$db->setQuery($query);
		$result = $db->execute();

      $this->enviarMsg($sala_id, $this->results->usu_id, 'saiu da sala', 0, 0, '');
      
      
      return;	
	}
   
}