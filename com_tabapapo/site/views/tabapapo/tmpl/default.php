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

<div class="taba-square">

	<form action="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapo'); ?>"
		  method="post" id="adminForm" name="adminForm">

	<div class="row">

		<div class="col-md-2"><div class="taba-head-title"><b><?php echo Text::_('COM_TABAPAPO_FIELD_USERNAME_LABEL');?></b></div><div class="taba-head-data"><?php echo $currentuser->get("username"); ?></div></div>
		<div class="col-md-2"><div class="taba-head-title"><b><?php echo Text::_('COM_TABAPAPO_FIELD_YOURIP_LABEL');?></b></div><div class="taba-head-data"><?php echo $_SERVER["REMOTE_ADDR"]; ?></div></div>

		<!--<div class="col-md-4">
			<input type="text" name="filter[search]" id="filter_search" value="" class="taba-input-text" placeholder="Busca">
			<button type="submit" class="taba-input-button"><span class="icon-search"></span></button>
		</div>-->

		<div class="col-md-4">
			<a class="btn btn-primary btn-sm" href="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapoadd&layout=tabapapoadd'); ?>" role="button"><i class="icon-new"></i><?php echo ' '.Text::_('COM_TABAPAPO_ADD_CHAT_ROOM');?></a>
			<a class="btn btn-info btn-sm" href="#" role="button" onclick="start_page();"><i class="icon-refresh"></i><?php echo ' '.Text::_('COM_TABAPAPO_REFRESH');?></a>
		</div>
		<div class="col-md-2"></div>
		<div class="col-md-1"></div>
			
		<div class="col-md-1">
			<select id="list_limit" name="list_limit" class="taba-select" onchange="start_page();">
				<option value="5" selected="selected">5</option>
				<option value="10">10</option>
				<option value="15">15</option>
				<option value="20">20</option>
				<option value="25">25</option>
				<option value="30">30</option>
				<option value="50">50</option>
				<option value="100">100</option>
				<option value="200">200</option>
				<option value="500">500</option>
				<option value="0">All</option>
			</select>
		</div>

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

		
		<input type="hidden" id="users_limit_global" value="10"/>	
		<input type="hidden" id="username" value="<?php echo $currentuser->get("username") ?>"/>	
		<input type="hidden" id="page_actual" value="1">
		<input type="hidden" id="tk" value="<?php echo JSession::getFormToken(); ?>">

	</div>
	</form>
	
</div>
<p style="text-align:right;" ><?php echo Text::_('COM_TABAPAPO_POWERED');?><a href="https://tabaoca.org">Tabaoca</a></p>
