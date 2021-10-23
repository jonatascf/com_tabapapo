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

JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');

$currentuser = JFactory::getuser();
$document = JFactory::getDocument();

if ( !$currentuser->get("id")){

   $app = JFactory::getApplication(); 
   $app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
   $app->setHeader('status', 403, true);
   return;

}

if ( !$this->item->id){

   $app = JFactory::getApplication(); 
   $app->enqueueMessage(Text::_('COM_TABAPAPO_CHATROOM_ERROR'), 'error');
   $app->setHeader('status', 403, true);
   return;

}

?>

<div class="taba-content">
   
   <div class="row">
      
      <div class="resizable_chat col-md-8">
         <div id="divframechat" onMouseOver="document.getElementById('rolagem').value = 0" onMouseOut="document.getElementById('rolagem').value = 1; row_frame();">        
           
            <div class="taba-head">
               <span>
                  <b><?php echo $this->item->title; ?></b>
               </span>
               <span class="taba-small">
      				<?php if ($this->item->category and $this->item->params->get('show_category')) {
                  echo '('.Text::_('JCATEGORY').': '. $this->item->category.')'; }?>
      			</span>
            </div>
            <div>
               <iframe
                  id="frameread"
                  name="fread"
                  class="taba-frame"
                  src="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapo&layout=messages&tmpl=messages'); ?>"
                  marginwidth="0"
                  marginheight="0"
                  scrolling="auto"
                  frameborder="1"
                  onload="begin_room(); getin_room();">
       		   </iframe>
            </div>
         </div>
         <div id="resize-bottom-chat" class="resizer_chat">
         </div>
      </div>
      <div class="resizable_users col-md-4">      
         <div id="divframeusers">
            <div id="users-head" class="taba-head taba-hover" onclick="select_talkto(0,'');">
            </div>
            <div>
               <iframe
                 id="frameusers"
                 name="fusers"
                 class="taba-frame"
                 src="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapo&layout=usuarios&tmpl=usuarios'); ?>"
                 marginwidth="0"
                 marginheight="0"
                 scrolling="auto"
                 frameborder="1">
        		   </iframe>
            </div>
         
         </div>
         <div id="resize-bottom-users" class="resizer_users">
         </div>
      </div>
   </div>
   
</div>

<div class="taba-content">
   
   <div class="row">
      <div>
         <br>
         <i id="statusb" class="icon-circle" style="color:#72bf44;"></i>
         <span id="exibefrase" class="taba-msghead"></span>
         
      </div>
   </div>
   
</div>
      
      <form action="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapo&layout=default'); ?>"
         method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
      

<div class="taba-content">
   <div class="row">

      <div class="col-md-8">
               
            <div class="row">
      
               <div class="col-md-10">

            	   <br>
                  <textarea
                     form="adminForm"
                     name="jform[msg]"
                     id="jform[msg]"
                     onKeyPress="verify_msg(); if (event.keyCode==13){ send_msg();}"
                     onKeyDown="verify_msg();"
                     onKeyUp="verify_msg();"
                     onFocus="verify_msg();"
                     onChange="verify_msg();"
                     title="Digite sua mensagem"
                     rows="5"
                     cols="85"></textarea>

               </div>
               
               <div class="col-md-2">
            
                  <br><span id="botenviar" class="taba-send"></span>
               
               </div>
            
            </div>
         <br>
            <div class="row">
            
               <div class="col-md-2 taba-msghead">
               
                  <input type="checkbox" id="jform[status]" name="jform[status]" onclick="atualizar_status(); document.getElementById('jform[msg]').focus();">
                  <label for="jform[status]" title="Your user name will be shown yellow in the list of users online."><?php echo Text::_('COM_TABAPAPO_AWAY');?></label>         
               
               </div>
               
               <div id="cb_private" class="col-md-2 taba-msghead" style="visibility: hidden;">
               
                  <input type="checkbox" id="privado" name="privado" onclick="select_private(); document.getElementById('jform[msg]').focus();">
                  <label for="privado" title="This option sends private message to selected user."><?php echo Text::_('COM_TABAPAPO_PRIVATE');?></label>
               
               </div>
               
               <div class="col-md-2">
                  <!-- -->
               </div>
               
               <div class="col-md-2">
                  <!-- -->
               </div>
               
               <div class="col-md-2">
               
                     <div class="row">
                  
                        <div class="col-md-4">
                     
               				<?php if ($this->item->params->get('show_dices')) {
                           echo '<span class="taba-hover icon-cube" title="Dices" onclick="show_info('."'".'divdices'."'".');"></span>'; }?>             
                     
                        </div>
                     
                        <div class="col-md-4">
                     
                           <span class="taba-hover icon-info" title="Information" onclick="show_info('divinfo');"></span>
                     
                        </div>
                     
                        <div class="col-md-4">
                     
                           <span class="taba-hover icon-smiley-2" title="Emojis" onclick="show_info('divemojis');"></span>
                     
                        </div>
                     
                     </div>
               
               </div>
            
            </div>
            
				<div id="divinfo" class="row" hidden="true">
					<div id="divdesc" class="col-md-6" hidden="true">
            	<br>
						<b><?php echo Text::_('COM_TABAPAPO_DESCRIPTION');?></b>
						<p><?php echo $this->item->description; ?></p>					
					</div>
					<div class="col-md-6">            	
            	<br>
            		<b><?php echo Text::_('COM_TABAPAPO_INFORMATION');?></b>
            		<ul>
               		<li><?php echo Text::_('COM_TABAPAPO_INFORMATION_1');?></li>
               		<li><?php echo Text::_('COM_TABAPAPO_INFORMATION_2');?></li>
               		<li><?php echo Text::_('COM_TABAPAPO_INFORMATION_3');?></li>
               		<li><?php echo Text::_('COM_TABAPAPO_INFORMATION_4');?></li>
            		</ul>
					</div>
         	</div>

         
      </div>
      
      <div class="col-md-4">
      
         <div id="divdices" hidden="true">
            <br>
            <b><?php echo Text::_('COM_TABAPAPO_DICES');?></b>
            <table>
               <tr>
                  <td class="taba-msghead taba-hover" onclick="roll_dice(4);"><img src="<?php echo JRoute::_('media/com_tabapapo/images/d4.png'); ?>" alt="D4" width="50" height="50"></td>
   					<td class="taba-msghead taba-hover" onclick="roll_dice(6);"><img src="<?php echo JRoute::_('media/com_tabapapo/images/d6.png'); ?>" alt="D6" width="50" height="50"></td>
   					<td class="taba-msghead taba-hover" onclick="roll_dice(8);"><img src="<?php echo JRoute::_('media/com_tabapapo/images/d8.png'); ?>" alt="D8" width="50" height="50"></td>
   				</tr>
               <tr>
                  <td class="taba-msghead taba-hover" onclick="roll_dice(10);"><img src="<?php echo JRoute::_('media/com_tabapapo/images/d10.png'); ?>" alt="D10" width="50" height="50"></td>
   					<td class="taba-msghead taba-hover" onclick="roll_dice(12);"><img src="<?php echo JRoute::_('media/com_tabapapo/images/d12.png'); ?>" alt="D12" width="50" height="50"></td>
   					<td class="taba-msghead taba-hover" onclick="roll_dice(20);"><img src="<?php echo JRoute::_('media/com_tabapapo/images/d20.png'); ?>" alt="D20" width="50" height="50"></td>
               </tr>
            </table>
         </div>
         
         <div id="divemojis" hidden="true">
            <br>
            <b><?php echo Text::_('COM_TABAPAPO_EMOJIS');?></b>
					<div>&# 128512= &#128512</div>
               <div>&# 128518 = &#128518</div>
               <div>&# 128521 = &#128521</div>
               <div>&# 128525 = &#128525</div>
               <div>&# 128526 = &#128526</div>
               <div>&# 128533 = &#128533</div>
         </div>
         
         <div>
            <br>
            <button class="btn btn-success" onclick="saindo();" ><i class="icon-exit"></i><?php echo Text::_('COM_TABAPAPO_EXIT');?></button>
         </div>

      	<input type="hidden" id="jform[sala_id]" name="jform[sala_id]" value="<?php echo $this->item->id; ?>"/>
         
      	<input type="hidden" id="jform[usu_id]" name="jform[usu_id]" value="<?php echo $currentuser->get("id") ?>"/>
         
      	<input type="hidden" id="jform[talkto_id]" name="jform[talkto_id]" value="0"/>

      	<input type="hidden" id="jform[privado]" name="jform[privado]" value="0"/>

      	<input type="hidden" id="jform[talkto_name]" name="jform[talkto_name]" value=""/>

         <input type="hidden" id="description" value="<?php echo $this->item->description; ?>">

         <input type="hidden" id="show_info" value="0">
         
         <input type="hidden" id="lmsg_id" value="0">
         
         <input type="hidden" id="tk" value="<?php echo JSession::getFormToken(); ?>">
         
         <input type="hidden" id="rolagem" value="1">
         
         <?php echo JHtml::_('form.token'); ?>
         
      </div>

   </div>
   
</div>

      </form>
      
<!--- Please do not delete the code line below. -->
<br>
<p style="text-align:right;" ><?php echo Text::_('COM_TABAPAPO_POWERED');?><a href="https://tabaoca.org">Tabaoca</a></p>
