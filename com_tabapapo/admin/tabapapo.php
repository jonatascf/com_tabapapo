<?php
/**
 * @package     Tabapapo.Administrator
 * @subpackage  com_tabapapo
 */

defined('_JEXEC') or die('Restricted access');

// Set some global property
$document = JFactory::getDocument();
$document->addStyleDeclaration('.icon-tabapapo {background-image: url(../media/com_tabapapo/images/tux-16x16.png);}');

// Access check: is this user allowed to access the backend of this component?
if (!JFactory::getUser()->authorise('core.manage', 'com_tabapapo'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Require helper file
JLoader::register('TabaPapoHelper', JPATH_COMPONENT . '/helpers/tabapapo.php');

// Get an instance of the controller prefixed by TabaPapo
$controller = JControllerLegacy::getInstance('TabaPapo');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();