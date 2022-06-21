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

$user      = Factory::getUser();
$userId    = $user->get('id');

?>
<div class="taba-content">
	
	<form action="index.php?option=com_tabapapo&view=tabapapo" method="post" id="adminForm" name="adminForm">
	
	<div class="row">
		
		<div class="col-md-12">
			<h2><?php echo Text::_('COM_TABAPAPO_DASHBOARD'); ?></h2>
			<br>
			<p><a class="btn btn-info btn-lg" href="index.php?option=com_tabapapo&view=tabapapo&layout=tabapapo" role="button"><i class="icon-comments-2"></i><?php echo ' '.Text::_('COM_TABAPAPO_SUBMENU_ROOMS');?></a></p>
			<p><a class="btn btn-info btn-lg" href="index.php?option=com_categories&extension=com_tabapapo" role="button"><i class="icon-flag"></i><?php echo ' '.Text::_('COM_TABAPAPO_SUBMENU_CATEGORIES');?></a></p>
			<p><a class="btn btn-info btn-lg" href="index.php?option=com_config&view=component&component=com_tabapapo"><i class="icon-options"></i><?php echo ' '.Text::_('COM_TABAPAPO_CONFIGURATION');?></a></p>
		</div>

        <?php echo JHtml::_('form.token'); ?>

	</div>
	
	</form>
	
</div>
<p style="text-align:right;" ><?php echo Text::_('COM_TABAPAPO_POWERED');?><a href="https://tabaoca.org">Tabaoca</a></p>
