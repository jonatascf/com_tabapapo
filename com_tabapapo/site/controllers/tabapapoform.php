<?php
/**
 * @package     Tabapapo.Administrator
 * @subpackage  com_tabapapo
 */

defined('_JEXEC') or die('Restricted access');

/**
 * TabaPapo Controller
 *
 * @package     Joomla.Site
 * @subpackage  com_tabapapo
 *
 * Used to handle the http POST from the front-end form which allows 
 * users to enter a new tabapapo message
 *
 */

use Joomla\CMS\Date\Date;
 
class TabaPapoControllerTabaPapoForm extends JControllerForm
{   
public $currentuser;

	public function add($key = null, $urlVar = null)
	{
//		$this->checkToken();
		
$conec = new conexoesTabapapoForm;
$currentuser = JFactory::getuser();

$conec->enviarMsg(1, $currentuser->get("id"), 'mensagem dbb', 0, 0, '');
   
}



}


class conexoesTabapapoForm {
	
	protected $currentuser;
	protected $results;
	public $mensagens;
   public $usuarios;
   
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
         

         }

   }
   
}