<?php
/**
 * @package     Tabapapo.Administrator
 * @subpackage  com_tabapapo
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');



$conec = new conexoesTabapapo;

$idusu = $conec->entrarSala($this->item->id);

$rows = $conec->lerMsgs($this->item->id);

$msgs = $conec->mensagens;
$msgs = json_encode($msgs);

//var_dump($msgs);

$usuarios = $conec->listarUsuarios($this->item->id);
$usuarios = json_encode($usuarios);

echo 'Users:'.$usuarios;

$currentuser = JFactory::getuser();

//$statuson = JControllerLegacy::atualizaStatus($idusu, 0);

//$msg_out = $conec->enviarMsg($this->item->id, $currentuser->get("id"), 'vou embora...', 0, 0, '');
//$conec->sairSala($this->item->id, $currentuser->get("id"));

//terminar

$msgs_inicial = "";

for ($i = 0; $i < $rows; $i++) {
   $msgs_inicial .= '<div class="'.'publico'.'">'.$conec->mensagens[$i]->id.' '.$conec->mensagens[$i]->msg.'</div>';
}

$users_on = "";



for ($i = 0; $i < $usuarios; $i++) {

   if ($conec->usuarios[$i]->status) { 
      $typeuser = 'talkto'; 
   }
   else {
      $typeuser = 'privado';
   }
   
   $users_on .= '<div class="' . $typeuser . '">' . '@ ' . JFactory::getUser($conec->usuarios[$i]->usu_id)->get('username'). '<i> [' . JFactory::getUser($conec->usuarios[$i]->usu_id)->get('id') . ']</i></div>';

}

$document = JFactory::getDocument();
$document->addScriptDeclaration('var dados = '.$msgs.';' .
                                'var users = '."'".$users_on."'".';'.
                                'var sala_id = '.$this->item->id.';'.
                                'var usu_id = '.$currentuser->get("id").';'.
                                'var tk = '."'".JSession::getFormToken()."'".';'
                                );


$styleiframe = '.frames {'
        . 'width: 100%;'
        . 'border: 1px solid #ddd;'
        . '}'
//        . '.boxdiv {'
//        . 'border: 1px solid blue;'        
//        . '}'
//        . '.boxdivg {'
//        . 'border: 1px solid green;'        
//        . '}'        
//        . '.boxdivr {'
//        . 'border: 1px solid red;'        
//        . '}'
        . '.tablet {'
        . 'text-align: left;'        
        . '}'
        .'.textarea-container {
  position: relative;
}
.textarea-container textarea {
  width: 100%;
  height: 100%;
  box-sizing: border-box;
}
.textarea-container i {
  position: absolute;
  top: 0;
  right: 0;
}';

$document->addStyleDeclaration($styleiframe);





?>

<script language="JavaScript">



function addLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
        	window.onload = func;
	} 
	else {
       window.onload = function() { if (oldonload) { oldonload();}  func();
   }
  }
}


function addBeforeunloadEvent() {
	var oldonload = window.onbeforeunload;
	if (typeof window.onbeforeunload != 'function') {
        	window.onbeforeunload = function(){ saindo(sala_id, tk); return true;}
	} 
	else {
       window.onbeforeunload = function() {  
                                             if (oldonload) { oldonload();} 
                                             //alert("successful"); 
                                             saindo(sala_id, tk);
                                             return true;
   }
  }
}

function addUnloadEvent() {
	var oldonload = window.onunload;
	if (typeof window.onunload != 'function') {
        	window.onunload = function(){ saindo(sala_id, tk); return "saindo";}
	} 
	else {
       window.onunload = function() {  
                                             if (oldonload) { oldonload();} 
                                             alert("successful"); 
                                             saindo(sala_id, tk);
                                             return "saindo";
   }
  }
}


//window.addEventListener("onload", () => {alert("successful");} );
//window.addEventListener("beforeunload", () => {alert("successful");} );

addLoadEvent(function(){ rolar(); 
                         inicia();
                         entrando(sala_id, tk);
                         Ler("frameread", usu_id, tk);
                         });
addBeforeunloadEvent();
//addUnloadEvent();

</script>

  


<?php

//var_dump($_POST);

    $src = $this->item->imageDetails['imagem'];
    if ($src)
    {
        $html = '<figure>
                    <img src="%s" alt="%s" >
                    <figcaption>%s</figcaption>
                </figure>';
        $alt = $this->item->imageDetails['alt'];
        $caption = $this->item->imageDetails['caption'];
        echo sprintf($html, $src, $alt, $caption);
    }

// iframeread                   onload = "populateChatRoom(this.id, dados);"
?>


<div class="row-fluid boxdivr">
   <div class="boxdivg">

      <table>
        <tr>
          <th class="span8" ><h4 class="tablet"><?php echo $this->item->title.(($this->item->category and $this->item->params->get('show_category'))
                                                    ? (' ('.$this->item->category.')') : ''); ?></h4>   </th>

          <th class="span4" ><h4 class="tablet">Falar com:</h4></th>
        </tr>
        <tr>
          <td onMouseOver="document.getElementById('rolagem').value = 0" onMouseOut="document.getElementById('rolagem').value = 1,rolar()">        
          <iframe
                  id="frameread"
                  name="fread"
                  class="frames"
                  src="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapo&layout=messages&tmpl=messages'); ?>"
                  marginwidth="0"
                  marginheight="0"
                  scrolling="auto"
                  frameborder="1">
      		  </iframe></td>
          <td><iframe
                  id="frameusers"
                  name="fusers"
                  class="frames"
                  onload = "populateUsersOn(this.id, users);"
                  src="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapo&layout=usuarios&tmpl=usuarios'); ?>"
                  marginwidth="0"
                  marginheight="0"
                  scrolling="auto"
                  frameborder="1">
      		  </iframe></td>

        </tr>
      </table>
      <?php //var_dump($_POST); ?>
      </div>

      <div class="boxdivg">

         <i class="icon-circle" style="color:#51a351;"></i><span id="exibefrase"><?php echo $currentuser->get("username").' fala com TODOS.'; ?></span>
      
      </div>
      


   <div>
      <div class="span4 textarea-container">

      <form action="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapo&layout=default'); ?>"
         method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">

         <input type="hidden" id="rolagem" value="1">


         <?php echo $this->form->renderField('sala_id'); ?>
         <?php echo $this->form->renderField('usu_id'); ?>
         <?php echo $this->form->renderField('falacom_id'); ?>
         <?php echo $this->form->renderField('params'); ?>
         

         <?php // echo $this->form->renderField('msg'); ?>
	   <textarea
         name="jform[msg2]"
         cols="85"
         rows="3"
         id="jform[msg2]"
         class="span12"
         onKeyPress="ContaCaracteres();if (event.keyCode==13){ send_msg('frameread', sala_id, usu_id, tk); VerificaMsg();}"
         onKeyDown="ContaCaracteres();"
         onKeyUp="ContaCaracteres();"
         onFocus="ContaCaracteres();"
         onChange="ContaCaracteres();"
         title="Digite sua mensagem"></textarea>
         
			<i class="icon-smiley-2" onclick="emojis();"></i>
         
      </div>
      <div class="span2">
      
	<!--	<small class="hasPopover" data-toggle="popover" data-placement="top" title="Header" data-content="Content" data-trigger="focus"><span id="botenviar"></span></small>  -->
         <button type="button" class="btn btn-primary" onclick="send_msg('frameread', sala_id, usu_id, tk); VerificaMsg();">
			<span class="icon-ok"></span><?php echo JText::_('COM_TABAPAPO_SEND_SEND') ?>
         </button>

      </div>
      
      <div class="span4">

         <?php echo $this->form->renderField('privado'); ?>

         <?php echo $this->form->renderField('status'); ?>

      </div>
      
      <div class="span2">    
         
         <button type="button" class="btn btn-primary" onclick="sair();">
         <span class="icon-exit"></span>Sair
         </button>
         
      	<input type="hidden" id="lmsg"/>
         
      	<input type="hidden" name="task" value="mensagemEnviar"/>
         <?php echo JHtml::_('form.token'); ?>
         
      </form>
      
      </div>
  
   </div>

</div>
