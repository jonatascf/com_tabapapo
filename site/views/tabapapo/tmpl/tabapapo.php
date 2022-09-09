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
$app = JFactory::getApplication();

$users_limit = '';

if (!$currentuser->get("id")){

   $app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
   $app->setHeader('status', 403, true);
   return;

}

if (!$this->item->id){
 
   $app->enqueueMessage(Text::_('COM_TABAPAPO_CHATROOM_ERROR'), 'error');
   $app->setHeader('status', 403, true);
   return;

}

if ($this->item->params->get("users_limit") == '') {
	
	$users_limit = 10; //app->get('users_limit'); global
	
} else {
	
	$users_limit = $this->item->params->get("users_limit");
	
}

if ($this->item->users_on >= $users_limit){

   $app->enqueueMessage(Text::_('COM_TABAPAPO_CHATROOM_CROWDED'), 'error');
   $app->setHeader('status', 403, true);
   return;

}

?>

<div class="container border bg-light taba-padding">

   <div class="row">
	  
	  <div class="resizable_chat col-md-8">
		 <div id="divframechat" onMouseOver="document.getElementById('jform_rolagem').value = 0" onMouseOut="document.getElementById('jform_rolagem').value = 1; row_frame();">        
		   
			<div class="taba-head">
			   <span>
				  <i class="icon-comments-2"></i><b><?php echo ' ' . $this->item->title; ?></b>
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
				  class="taba-framechat"
				  src="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapo&layout=messages&tmpl=messages'); ?>"
				  marginwidth="0"
				  marginheight="0"
				  scrolling="auto"
				  frameborder="1"
				  onload="begin_room(); getin_room();">
			   </iframe>
			</div>
		 </div>
		 <div id="resize-bottom-chat" class="resizer_chat"><div class="resizer_chat_b"></div></div>
	  </div>
	  <div class="resizable_users col-md-4">      
		 <div id="divframeusers">
			<div id="users-head" class="taba-head taba-hover" onclick="select_talkto(0,'');">
			</div>
			<div>
				<iframe
				 id="frameusers"
				 name="fusers"
				 class="taba-framechat"
				 src="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapo&layout=usuarios&tmpl=usuarios'); ?>"
				 marginwidth="0"
				 marginheight="0"
				 scrolling="auto"
				 frameborder="1">
				</iframe>
			</div>
		 
		 </div>
		 <div id="resize-bottom-users" class="resizer_users"><div class="resizer_users_b"></div></div>
	  </div>
   </div>
   
	<div class="taba-padding">
		<i id="statusb" class="icon-circle" style="color:#72bf44;"></i>
		<span id="exibefrase" class="taba-msghead"></span>
	</div>

	<form action="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapo&layout=tabapapo'); ?>"
			method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data" autocomplete="off">
		
	<div class="row">

		<div class="col-md-8">
			   
			<div class="row">
	  
			   <div class="col-md-10">
					<?php echo $this->form->renderField('msg'); ?>
			   </div>
			   
			   <div class="col-md-2">
				  <br><span id="botenviar"></span>
			   </div>
			
			</div>
			
			<div class="container">
				<div class="row">
				
				   <div class="col-md-2 taba-msghead">
				   
					  <?php echo $this->form->renderField('status'); ?>
				   
				   </div>
				   
				   <div id="cb_private" class="col-md-2 taba-msghead" style="visibility: hidden;">

					  <?php echo $this->form->renderField('privado'); ?>
				   
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
						 
								<?php if ($this->item->params->get('show_dice')) {
							   echo '<span class="taba-hover icon-cube" title="'.Text::_('COM_TABAPAPO_DICE').'" onclick="show_info('."'".'divdice'."'".');"></span>'; }?>             
						 
							</div>
						 
							<div class="col-md-4">
						 
							   <span class="taba-hover icon-info-2" title="<?php echo Text::_('COM_TABAPAPO_INFORMATION');?>" onclick="show_info('divinfo');"></span>
						 
							</div>
						 
							<div class="col-md-4">
						 
							   <span class="taba-hover icon-smiley-2" title="<?php echo Text::_('COM_TABAPAPO_EMOJIS');?>" onclick="show_info('divemojis');"></span>
						 
							</div>
						 
						 </div>
				   
				   </div>
				
				</div>
			</div>
			
			<div class="container">
				<div id="divinfo" class="row" hidden="true">
					<div id="divdesc" class="col-md-6" hidden="true">
						<br>
						<b><?php echo Text::_('COM_TABAPAPO_DESCRIPTION');?></b>
						<div class="taba-msghead"><p><?php echo $this->item->description; ?></p></div>
					</div>
					<div class="col-md-6">            	
						<br>
						<b><?php echo Text::_('COM_TABAPAPO_INFORMATION');?></b>
						<div class="taba-msghead">
							<ul>
								<li><?php echo Text::_('COM_TABAPAPO_INFORMATION_1');?></li>
								<li><?php echo Text::_('COM_TABAPAPO_INFORMATION_2');?></li>
								<li><?php echo Text::_('COM_TABAPAPO_INFORMATION_3');?></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	  
	  <div class="col-md-4">
	  
		 <div id="divdice" hidden="true">
			<br>
			<b><?php echo Text::_('COM_TABAPAPO_DICE');?></b>
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
			<table>
				<tr>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#128512');">&#128512</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#128518');">&#128518</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#128521');">&#128521</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#128525');">&#128525</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#128526');">&#128526</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#128533');">&#128533</td>
				</tr>
				<tr>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#127775');">&#127775</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#127774');">&#127774</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#127771');">&#127771</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#127794');">&#127794</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#127796');">&#127796</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#127797');">&#127797</td>
				</tr>
				<tr>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#127799');">&#127799</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#127801');">&#127801</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#127803');">&#127803</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#127812');">&#127812</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#127817');">&#127817</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#127818');">&#127818</td>
				</tr>
				<tr>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#128004');">&#128004</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#128007');">&#128007</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#128010');">&#128010</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#128011');">&#128011</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#128029');">&#128029</td>
					<td class="taba-msghead taba-hover" onclick="insert_emoji('&#128030');">&#128030</td>
				</tr>
			</table>
		 </div>
		 
	  </div>
		<?php
			
			if ($this->item->params->get('show_private')) { $show_private = '1';} else { $show_private = '0'; }
			
			$this->form->setFieldAttribute("sala_id", "default", $this->item->id);
			$this->form->setFieldAttribute("usu_id", "default", $currentuser->get("id"));
			$this->form->setFieldAttribute("owner", "default", $this->item->created_by);
			$this->form->setFieldAttribute("description", "default", $this->item->description);
			$this->form->setFieldAttribute("show_private", "default", $show_private);
			$this->form->setFieldAttribute("users_limit", "default", $users_limit);
			$this->form->setFieldAttribute("tk", "default", JSession::getFormToken());
			
			echo $this->form->renderField('privadob');
			echo $this->form->renderField('sala_id');
			echo $this->form->renderField('usu_id');
			echo $this->form->renderField('talkto_id');
			echo $this->form->renderField('talkto_name');
			echo $this->form->renderField('owner');
			echo $this->form->renderField('description');
			echo $this->form->renderField('show_info');
			echo $this->form->renderField('show_private');
			echo $this->form->renderField('users_limit');
			echo $this->form->renderField('lmsg_id');
			echo $this->form->renderField('tk');
			echo $this->form->renderField('rolagem');
			
			echo JHtml::_('form.token');	
		?>
	  </form>

	  <form action="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapo'); ?>"
		 method="post" name="adminButtons" id="adminButtons" class="form-validate" enctype="multipart/form-data">
		  <div>
			 <br>
			 <button class="btn btn-danger" onclick="saindo();" ><i class="icon-exit"></i><?php echo Text::_('COM_TABAPAPO_EXIT');?></button>
		  </div>
	  </form>

   </div>
	   
</div>
<p style="text-align:right;" ><?php echo Text::_('COM_TABAPAPO_POWERED');?><a href="https://tabaoca.org">Tabaoca</a></p>
