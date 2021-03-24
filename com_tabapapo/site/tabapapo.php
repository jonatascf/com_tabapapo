<?php
/**
 * @package     Tabapapo.Administrator
 * @subpackage  com_tabapapo
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

// Get an instance of the controller prefixed by TabaPapo
$controller = JControllerLegacy::getInstance('tabapapo');

//$controllerform = JControllerLegacy::getInstance('tabapapoform');

// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));
//$controllerform->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();