<?php
/**
 * @package Tabapapo Component for Joomla! 3.9
 * @version 0.8.5
 * @author Jonatas C. Ferreira
 * @copyright (C) 2021 Tabaoca.org
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

/**
 * Tabapapo component helper.
 *
 * @param   string  $submenu  The name of the active view.
 *
 * @return  void
 *
 * @since   1.6
 */
abstract class TabaPapoHelper extends JHelperContent
{
	/**
	 * Configure the Linkbar.
	 *
	 * @return Bool
	 */

	public static function addSubmenu($submenu) 
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_TABAPAPO_SUBMENU_ROOMS'),
			'index.php?option=com_tabapapo',
			$submenu == 'tababapapos'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_TABAPAPO_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&view=categories&extension=com_tabapapo',
			$submenu == 'categories'
		);

		// Set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-tabapapo ' .
										'{background-image: url(../media/com_tabapapo/images/tux-48x48.png);}');
		if ($submenu == 'categories') 
		{
			$document->setTitle(JText::_('COM_TABAPAPO_ADMINISTRATION_CATEGORIES'));
		}
	}
}