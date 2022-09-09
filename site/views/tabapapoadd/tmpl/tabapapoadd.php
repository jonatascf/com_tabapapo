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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Registry\Registry;

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

if (!empty($this->item->id)) {
	if ( $this->item->created_by != $currentuser->get("id")){

	   $app = JFactory::getApplication(); 
	   $app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
	   $app->setHeader('status', 403, true);
	   return;

	}
}

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');

$app = Factory::getApplication();
$input = $app->input;

$assoc = Associations::isEnabled();

// Fieldsets to not automatically render by /layouts/joomla/edit/params.php
$this->ignore_fieldsets = ['item_associations', 'jmetadata'];
$this->useCoreUI = true;

// In case of modal
//$isModal = $input->get('layout') === 'modal';
//$layout  = $isModal ? 'modal' : 'edit';
//$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';

?>
<div class="container border bg-light taba-padding">
	
	<form action="<?php echo JRoute::_('index.php?option=com_tabapapo&view=tabapapoadd&layout=tabapapoadd&id=' . (int) $this->item->id); ?>"
		  method="post" 
		  name="adminForm" 
		  id="item-form" 
		  aria-label="<?php echo Text::_('COM_TABAPAPO_FORM_TITLE_' . ((int) $this->item->id === 0 ? 'NEW' : 'EDIT'), true); ?>"
		  class="form-validate">

		<div>
			<?php echo $this->addToolBar(); ?>
		</div>
		
	   <div class="main-card">
		<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>   

		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'details')); ?>
	  
		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', 
			empty($this->item->id) ? Text::_('COM_TABAPAPO_TAB_NEW_CHAT') : Text::_('COM_TABAPAPO_TAB_EDIT_CHAT')); ?>

		<div class="row">
		  <div class="col-lg-6">
			 <div class="col-lg-12">
			 <fieldset class="adminform">
					<legend><?php echo $this->form->getLabel('description'); ?></legend>
					<?php echo $this->form->getInput('description'); ?>
			 </fieldset>
			 </div>
		  </div>
		  <div class="col-lg-6">
			 <?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		  <?php echo LayoutHelper::render('joomla.edit.params', $this); ?>

			<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'publishing', Text::_('COM_TABAPAPO_TAB_PUBLISHING')); ?>
			<div class="row">
				<div class="col-md-6">
					<fieldset id="fieldset-publishingdata" class="options-form">
						<legend><?php echo Text::_('COM_TABAPAPO_TAB_PUBLISHING'); ?></legend>
						<div>
							<?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
						</div>
					</fieldset>
				</div>
				<div class="col-md-6">
					<fieldset id="fieldset-metadata" class="options-form">
						<legend><?php echo Text::_('COM_TABAPAPO_METADATA_OPTIONS'); ?></legend>
						<div>
							<?php echo LayoutHelper::render('joomla.edit.metadata', $this); ?>
						</div>
					</fieldset>
				</div>
			</div>
			<?php //echo HTMLHelper::_('uitab.endTab'); ?>

			<?php //if (!$isModal && $assoc) : ?>
				<?php //echo HTMLHelper::_('uitab.addTab', 'myTab', 'associations', Text::_('JGLOBAL_FIELDSET_ASSOCIATIONS')); ?>
				<fieldset id="fieldset-associations" class="options-form">
					<legend><?php //echo Text::_('JGLOBAL_FIELDSET_ASSOCIATIONS'); ?></legend>
					<div>
						<?php //echo LayoutHelper::render('joomla.edit.associations', $this); ?>
					</div>
				</fieldset>
				<?php //echo HTMLHelper::_('uitab.endTab'); ?>
			<?php //elseif ($isModal && $assoc) : ?>
				<div class="hidden"><?php //echo LayoutHelper::render('joomla.edit.associations', $this); ?></div>
			<?php //endif; ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="forcedLanguage" value="<?php echo $input->get('forcedLanguage', '', 'cmd'); ?>">
		<?php echo HTMLHelper::_('form.token'); ?>

	   </div>

	</form>
</div>
<p style="text-align:right;" ><?php echo Text::_('COM_TABAPAPO_POWERED');?><a href="https://tabaoca.org">Tabaoca</a></p>
