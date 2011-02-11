<?php

/**
 * kitDirList
 * 
 * @author Ralf Hertsch (ralf.hertsch@phpmanufaktur.de)
 * @link http://phpmanufaktur.de
 * @copyright 2011
 * @license GNU GPL (http://www.gnu.org/licenses/gpl.html)
 * @version $Id$
 * 
 * FOR VERSION- AND RELEASE NOTES PLEASE LOOK AT INFO.TXT!
 */

// prevent this file from being accessed directly
if (!defined('WB_PATH')) die('invalid call of '.$_SERVER['SCRIPT_NAME']);

// include dbConnect
if (!class_exists('dbConnectLE')) require_once(WB_PATH.'/modules/dbconnect_le/include.php');

class dbKITdirList extends dbConnectLE {
	
	const field_id				= 'dl_id';
	const field_file			= 'dl_file';
	const field_path			= 'dl_path';
	const field_count			= 'dl_count';
	const field_date			= 'dl_date';
	const field_user			= 'dl_user';
	const field_update		= 'dl_update';
	
	private $create_tables = false;

  /**
   * Constructor for dbContact
   * @param bool $create_tables
   */
	public function __construct($create_tables = false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_kit_dirlist');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_file, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_path, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_count, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_date, "DATETIME DEFAULT '0000-00-00 00:00:00'");
		$this->addFieldDefinition(self::field_user, "VARCHAR(128) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_update, "TIMESTAMP");
		// check field definitions
		$this->checkFieldDefinitions();
		// create tables
		if ($this->create_tables) {
			if (!$this->sqlTableExists()) {
				if (!$this->sqlCreateTable()) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
					return false;
				}
			}
		}
		return true;
	} // __construct()
	
} // class dbKITdirList



?>