<?php
/**
 * @package Tabapapo Component for Joomla! 3.9
 * @version 0.8.5
 * @author Jonatas C. Ferreira
 * @copyright (C) 2021 Tabaoca.org
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\String\PunycodeHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Tag\TaggableTableInterface;
use Joomla\CMS\Tag\TaggableTableTrait;
use Joomla\CMS\Versioning\VersionableTableInterface;
use Joomla\Database\DatabaseDriver;
use Joomla\String\StringHelper;

/**
 * Tabapapo Table class.
 *
 * @since  1.0
 */

class TabaPapoTableTabaPapo extends JTable {

	use TaggableTableTrait;

	/**
	 * Indicates that columns fully support the NULL value in the database
	 *
	 * @var    boolean
	 * @since  4.0.0
	 */
	protected $_supportNullValue = true;

	/**
	 * Ensure the params and metadata in json encoded in the bind method
	 *
	 * @var    array
	 * @since  3.3
	 */
	protected $_jsonEncode = array('params', 'metadata');

	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  Database connector object
	 *
	 * @since   1.0
	 */


	function __construct(DatabaseDriver $db)	{

      $this->typeAlias = 'com_contact.contact';
      
      parent::__construct('#__tabapapo', 'id', $db);
	}
	
	/**
	 * Stores a chat room.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   0.9
	 */
	public function store($updateNulls = true)
	{
		$date   = Factory::getDate()->toSql();
		$userId = Factory::getUser()->id;

		// Set created date if not set.
		if (!(int) $this->created)
		{
			$this->created = $date;
		}

		if ($this->id)
		{
			// Existing item
			$this->modified_by = $userId;
			$this->modified    = $date;
		}
		else
		{
			// Field created_by field can be set by the user, so we don't touch it if it's set.
			if (empty($this->created_by))
			{
				$this->created_by = $userId;
			}

			if (!(int) $this->modified)
			{
				$this->modified = $date;
			}

			if (empty($this->modified_by))
			{
				$this->modified_by = $userId;
			}
		}

		// Verify that the alias is unique
		$table = Table::getInstance('tabapapo', __NAMESPACE__ . '\\', array('dbo' => $this->getDbo()));

		/*if ($table->load(array('alias' => $this->alias, 'catid' => $this->catid)) && ($table->id != $this->id || $this->id == 0))
		{
			$this->setError(Text::_('COM_TABAPAPO_ERROR_UNIQUE_ALIAS'));

			return false;
		}*/

		return parent::store($updateNulls);
	}

	/**
	 * Overloaded check function
	 *
	 * @return  boolean  True on success, false on failure
	 *
	 * @see     \JTable::check
	 * @since   1.5
	 */
	public function check()
	{
		try
		{
			parent::check();
		}
		catch (\Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}


		// Check for valid name
		if (trim($this->title) == '')
		{
			$this->setError(Text::_('COM_TABAPAPO_WARNING_PROVIDE_VALID_TITLE'));

			return false;
		}

		// Generate a valid alias
		$this->generateAlias();

		// Check for a valid category.
		if (!$this->catid = (int) $this->catid)
		{
			$this->setError(Text::_('JLIB_DATABASE_ERROR_CATEGORY_REQUIRED'));

			return false;
		}


		// Check the publish down date is not earlier than publish up.
		if ((int) $this->publish_down > 0 && $this->publish_down < $this->publish_up)
		{
			$this->setError(Text::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));

			return false;
		}

		if (!$this->id)
		{
			// Hits must be zero on a new item
			$this->hits = 0;
		}

		// Clean up description -- eliminate quotes and <> brackets
		if (!empty($this->metadesc))
		{
			// Only process if not empty
			$badCharacters = array("\"", '<', '>');
			$this->metadesc = StringHelper::str_ireplace($badCharacters, '', $this->metadesc);
		}
		else
		{
			$this->metadesc = '';
		}

		if (empty($this->params))
		{
			$this->params = '{}';
		}

		if (empty($this->metadata))
		{
			$this->metadata = '{}';
		}

		// Set publish_up, publish_down to null if not set
		if (!$this->publish_up)
		{
			$this->publish_up = null;
		}

		if (!$this->publish_down)
		{
			$this->publish_down = null;
		}

		if (!$this->modified)
		{
			$this->modified = $this->created;
		}

		if (empty($this->modified_by))
		{
			$this->modified_by = $this->created_by;
		}

		return true;
	}

	/**
	 * Generate a valid alias from title / date.
	 * Remains public to be able to check for duplicated alias before saving
	 *
	 * @return  string
	 */
	public function generateAlias()
	{
		if (empty($this->alias))
		{
			$this->alias = $this->title;
		}

		$this->alias = ApplicationHelper::stringURLSafe($this->alias, $this->language);

		if (trim(str_replace('-', '', $this->alias)) == '')
		{
			$this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
		}

		return $this->alias;
	}


	/**
	 * Get the type alias for the history table
	 *
	 * @return  string  The alias as described above
	 *
	 * @since   4.0.0
	 */
	public function getTypeAlias()
	{
		return $this->typeAlias;
	}
   
   
	public function bind($array, $ignore = '') {
   
		if (isset($array['params']) && is_array($array['params']))
		{
			// Convert the params field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($array['params']);
			$array['params'] = (string)$parameter;
		}

		// Bind the rules.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			$rules = new JAccessRules($array['rules']);
			$this->setRules($rules);
		}
		return parent::bind($array, $ignore);
	}
	
	/**
	 * Method to compute the default name of the asset.
	 * The default name is in the form `table_name.id`
	 * where id is the value of the primary key of the table.
	 *
	 * @return	string
	 * @since	2.5
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_tabapapo.tabapapo.'.(int) $this->$k;
	}
	/**
	 * Method to return the title to use for the asset table.
	 *
	 * @return	string
	 * @since	2.5
	 */
	protected function _getAssetTitle()
	{
		return $this->title;
	}
	/**
	 * Method to get the asset-parent-id of the item
	 *
	 * @return	int
	 */
	protected function _getAssetParentId(JTable $table = NULL, $id = NULL)
	{
		// We will retrieve the parent-asset from the Asset-table
		$assetParent = JTable::getInstance('Asset');
		// Default: if no asset-parent can be found we take the global asset
		$assetParentId = $assetParent->getRootId();

		// Find the parent-asset
		if (($this->catid)&& !empty($this->catid))
		{
			// The item has a category as asset-parent
			$assetParent->loadByName('com_tabapapo.category.' . (int) $this->catid);
		}
		else
		{
			// The item has the component as asset-parent
			$assetParent->loadByName('com_tabapapo');
		}

		// Return the found asset-parent-id
		if ($assetParent->id)
		{
			$assetParentId=$assetParent->id;
		}
		return $assetParentId;
	}
}