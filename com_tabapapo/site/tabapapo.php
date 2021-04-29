<?php
/**
 * @package Tabapapo Component for Joomla! 3.9
 * @version 0.7.7
 * @author Jonatas C. Ferreira
 * @copyright (C) 2021 Tabaoca.org
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

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