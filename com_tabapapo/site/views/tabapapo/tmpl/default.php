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

JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');

$currentuser = JFactory::getuser();
$document = JFactory::getDocument();

if ( !$currentuser->get("id")){
	header('Location:index.php');
}

$document->addScriptDeclaration('var sala_id = '.$this->item->id.';'.
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
  top: 1;
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

addLoadEvent(function(){ rolar(); 
                         inicia();
                         entrando(sala_id, tk);
                         Ler("frameread", usu_id, tk);
                         Ler_users("frameusers", tk);
                         });

</script>

<?php

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

?>


<div class="row-fluid boxdivr">
   <div class="boxdivg">

      <table>
        <tr>
          <th class="span8" >
            <h4 class="tablet"><?php echo $this->item->title.(($this->item->category and $this->item->params->get('show_category'))
                                ? (' ('.$this->item->category.')') : ''); ?></h4>
            <p> <?php //echo $this->item->description; ?> </p>
          </th>

          <th class="span4" ><h4 class="tablet">Talk to:</h4></th>
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
                  src="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapo&layout=usuarios&tmpl=usuarios'); ?>"
                  marginwidth="0"
                  marginheight="0"
                  scrolling="auto"
                  frameborder="1">
      		  </iframe></td>

        </tr>
      </table>

      </div>

      <div class="boxdivg">

         <p><i id="statusb" class="icon-circle" style="color:#72bf44;"></i><span id="exibefrase"><?php echo $currentuser->get("username").' fala com TODOS.'; ?></span></p>
      
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

	   <textarea
         name="jform[msg2]"
         cols="85"
         rows="3"
         id="jform[msg2]"
         class="span12"
         onKeyPress="ContaCaracteres(); if (event.keyCode==13){ send_msg('frameread', sala_id, usu_id, tk);}"
         onKeyDown="ContaCaracteres();"
         onKeyUp="ContaCaracteres();"
         onFocus="ContaCaracteres();"
         onChange="ContaCaracteres();"
         title="Digite sua mensagem"></textarea>
         
			<!--- <a href="#" onclick="emojis();"><i class="icon-smiley-2"></i></a> -->
         
         <input type="checkbox" id="status" name="status" onclick="atualizar_status(sala_id, tk);">
         <label for="status">Away</label>         
         
         <?php echo $this->form->renderField('usercolor'); ?>
                  
      </div>
      <div class="span2">
      
         <span id="botenviar"></span>

      </div>
      
      <div class="span4">
      
         <?php echo $this->form->renderField('privado'); ?>

         <?php echo $this->form->renderField('status'); ?> 

      </div>
      
      <div class="span2">    
         
         <a href="#" onclick="saindo(sala_id, tk);" ><i class="icon-exit"></i>Exit</a>
         
      	<input type="hidden" id="lmsg"/>
                           
      	<input type="hidden" name="task" value="mensagemEnviar"/>
         <?php echo JHtml::_('form.token'); ?>
         
      </form>
      
      </div>

   </div>

</div>
<!--- Please do not delete the code line below. -->
<p style="text-align:center;" >Powered by <a href="https://tabaoca.org">Tabaoca</a></p>