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

class dbWBusers extends dbConnectLE {
	
	const field_user_id								= 'user_id';
	const field_group_id							= 'group_id';
	const field_groups_id							= 'groups_id';
	const field_active								= 'active';
	const field_username							= 'username';
	const field_password							= 'password';
	const field_remember_key					= 'remember_key';
	const field_last_reset						= 'last_reset';
	const field_display_name					= 'display_name';
	const field_email									= 'email';
	const field_timezone							= 'timezone';
	const field_date_format						= 'date_format';
	const field_time_format						= 'time_format';
	const field_language							= 'language';
	const field_home_folder						= 'home_folder';
	const field_login_when						= 'login_when';
	const field_login_ip							= 'login_ip';
	
	const status_active								= 1;
	const status_inactive							= 0;
	
	public function __construct() {
		parent::__construct();
		$this->setTableName('users');
		$this->addFieldDefinition(self::field_user_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_group_id, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_groups_id, "VARCHAR(255) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_active, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_username, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_password, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_remember_key, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_last_reset, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_display_name, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_email, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_timezone, "INT(11) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_date_format, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_time_format, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_language, "VARCHAR(5) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_home_folder, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_login_when, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_login_ip, "VARCHAR(15) NOT NULL DEFAULT ''");
		// check field definitions
		$this->checkFieldDefinitions();
	} // __construct()
	
	public function sqlCreateTable() {
		$this->setError('Function not implemented for this table!');
		return false;
	}
	
	public function sqlDeleteTable() {
		$this->setError('Function not implemented for this table!');
		return false;
	}
	
} // class dbWBusers


class dbWBgroups extends dbConnectLE {
	
	const field_group_id							= 'group_id';
	const field_name									= 'name';
	const field_system_permissions		= 'system_permissions';
	const field_module_permissions		= 'module_permissions';
	const field_template_permissions	= 'template_permissions';
	
	const kitWBgoup										= 'kitContact';
	
	public function __construct() {
		parent::__construct();
		$this->setTableName('groups');
		$this->addFieldDefinition(self::field_group_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_name, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_system_permissions, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_module_permissions, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_template_permissions, "TEXT NOT NULL DEFAULT ''");
		// check field definitions
		$this->checkFieldDefinitions();
	} // __construct()

	public function sqlCreateTable() {
		$this->setError('Function not implemented for this table!');
		return false;
	}
	
	public function sqlDeleteTable() {
		$this->setError('Function not implemented for this table!');
		return false;
	}
	
} // class dbWBgroups

?>