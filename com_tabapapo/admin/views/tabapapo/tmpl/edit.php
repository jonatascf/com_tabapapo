<?php
/**
 * @package Tabapapo Component for Joomla! 3.9
 * @version 0.7.7
 * @author Jonatas C. Ferreira
 * @copyright (C) 2021 Tabaoca.org
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.formvalidator');

// The following is to enable setting the permission's Calculated Setting 
// when you change the permission's Setting. 
// The core javascript code for initiating the Ajax request looks for a field
// with id="jform_title" and sets its value as the 'title' parameter to send in the Ajax request
JFactory::getDocument()->addScriptDeclaration('
jQuery(document).ready(function() {
        title = jQuery("#jform_title").val();
		jQuery("#jform_title2").val(title);
	});
');

?>
<form action="<?php echo JRoute::_('index.php?option=com_tabapapo&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate">
    <input id="jform_title2" type="hidden" name="tabapapo-message-title"/>
    <div class="form-horizontal">
    <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', 
        empty($this->item->id) ? JText::_('COM_TABAPAPO_TAB_NEW_MESSAGE') : JText::_('COM_TABAPAPO_TAB_EDIT_MESSAGE')); ?>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_TABAPAPO_LEGEND_DETAILS') ?></legend>
            <div class="row-fluid">
                <div class="span3">
                    <?php echo $this->form->renderFieldset('details');  ?>
                </div>
                <div class="span9">
                    <?php echo $this->form->getInput('description');  ?>
                </div>
            </div>
        </fieldset>
    <?php echo JHtml::_('bootstrap.endTab'); ?>

    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'imagem', JText::_('COM_TABAPAPO_TAB_IMAGE')); ?>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_TABAPAPO_LEGEND_IMAGE') ?></legend>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo $this->form->renderFieldset('imagem-info');  ?>
                </div>
            </div>
        </fieldset>
    <?php echo JHtml::_('bootstrap.endTab'); ?>

    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'params', JText::_('COM_TABAPAPO_TAB_PARAMS')); ?>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_TABAPAPO_LEGEND_PARAMS') ?></legend>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo $this->form->renderFieldset('params');  ?>
                </div>
            </div>
        </fieldset>
    <?php echo JHtml::_('bootstrap.endTab'); ?>

    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_TABAPAPO_TAB_PERMISSIONS')); ?>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_TABAPAPO_LEGEND_PERMISSIONS') ?></legend>
            <div class="row-fluid">
                <div class="span12">
                    <?php echo $this->form->renderFieldset('accesscontrol');  ?>
                </div>
            </div>
        </fieldset>
    <?php echo JHtml::_('bootstrap.endTab'); ?>
    <?php echo JHtml::_('bootstrap.endTabSet'); ?>

    </div>
    <input type="hidden" name="task" value="tabapapo.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>