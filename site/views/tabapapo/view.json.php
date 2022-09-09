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
use Joomla\CMS\Date\Date;
use Joomla\CMS\Form\Form;

class TabaPapoViewTabaPapo extends JViewLegacy
{

   
	/**
	 * Display the Tabapapo view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$this->item = $this->get('Item');
		
		$globalConfig = Factory::getConfig();
		$this->users_limit_global = $globalConfig->get('users_limit', 'No Users Limit');       
      
		parent::display($tpl);
	}
	
   
}

