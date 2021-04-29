<?php
/**
 * @package Tabapapo Component for Joomla! 3.9
 * @version 0.7.7
 * @author Jonatas C. Ferreira
 * @copyright (C) 2021 Tabaoca.org
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

class TabaPapoTableTabaPapoMsg extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__tabapapo_msg', 'id', $db);
	}
}