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

?>

<div class="container taba-square">

	<form action="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapo'); ?>"
		  method="post" id="adminForm" name="adminForm">

	<div class="row">
		<div class="col-md-3">
			<a class="btn btn-primary btn-sm" href="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapoadd&layout=tabapapoadd'); ?>" role="button"><i class="icon-new"></i><?php echo ' '.Text::_('COM_TABAPAPO_ADD_CHAT_ROOM');?></a>
			<a class="btn btn-info btn-sm" href="#" role="button" onclick="start_page();"><i class="icon-refresh"></i><?php echo ' '.Text::_('COM_TABAPAPO_REFRESH');?></a>
		</div>
		<div class="col-md-3">
			<div class="input-group mb-3">
				<?php echo $this->form->renderField('search');?>
				<div class="taba-button-seach input-group-append">
					<span class="input-group-text btn btn-primary btn-sm" onclick="start_page();" role="button"><i class="icon-search"></i></span>
				</div>
			</div>
		</div>
		<div class="col-md-2"><?php echo $this->form->renderField('list_filter');?></div>
		<div class="col-md-2"><?php echo $this->form->renderField('list_asc');?></div>
		<div class="col-md-2">
			<?php echo $this->form->renderField('list_limit');?>
		</div>
		<div class="col-md-4"><div class="taba-head-title"><b><?php echo Text::_('COM_TABAPAPO_FIELD_USERNAME_LABEL').' ';?></b><i class="icon-info-2" title="<?php echo Text::_('COM_TABAPAPO_FIELD_YOURIP_LABEL').': '.$_SERVER["REMOTE_ADDR"]; ?>"></i></div><div class="taba-head-data"><?php echo $currentuser->get("username"); ?></div></div>
		<div class="col-md-12">
			<br>
			<iframe
			  id="framelist"
			  name="flist"
			  class="taba-frame"
			  src="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapo&layout=listrooms&tmpl=listrooms'); ?>"
			  marginwidth="0"
			  marginheight="0"
			  scrolling="no"
			  onload="start_page();">
			</iframe>
		</div>
		
		<center><div id="pagination" class="pagination pagination-toolbar"></div></center>

		<?php		
			$this->form->setFieldAttribute("users_limit_global", "default", "10");
			$this->form->setFieldAttribute("username", "default", $currentuser->get("username"));
			$this->form->setFieldAttribute("tk", "default", JSession::getFormToken());

			echo $this->form->renderField('users_limit_global');
			echo $this->form->renderField('username');
			echo $this->form->renderField('page_actual');
			echo $this->form->renderField('tk');
			
			echo JHtml::_('form.token');
		?>
	</div>
	</form>
	
</div>
<p style="text-align:right;" ><?php echo Text::_('COM_TABAPAPO_POWERED');?><a href="https://tabaoca.org">Tabaoca</a></p>
