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

// include language file
if(!file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/languages/'.LANGUAGE.'.php')) {
	require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/languages/DE.php'); 
}
else {
	require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/languages/'.LANGUAGE.'.php'); 
}

if (!class_exists('Dwoo')) require_once(WB_PATH.'/modules/dwoo/include.php');

global $parser;
if (!is_object($parser)) $parser = new Dwoo();

// include dbConnect
if (!class_exists('dbConnectLE')) require_once(WB_PATH.'/modules/dbconnect_le/include.php');

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.tools.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.mimetypes.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.link.php');
require_once(WB_PATH.'/framework/functions.php');
require_once(WB_PATH.'/framework/class.wb.php');

class kitDirList {
	
	const request_action					= 'act';
	const request_sort						= 'sort';
	const request_sub_dir					= 'sd';
	const request_file						= 'ufile';
	const request_mkdir						= 'mkd';
	const request_unlink					= 'ul';
	const request_unlink_item			= 'uli';
	const request_unlink_confirm	= 'ulc';
	
	const action_start						= 'go';
	const action_logout						= 'out';
	const action_account					= 'acc';
	const action_upload						= 'upl';
	const action_unlink_file			= 'ulf';
	const action_unlink_dir				= 'uld';
	const action_unlink_pending		= 'ulp';
	const action_unlink_confirmed	= 'ulc';
	
	const sort_asc					= 'asc';
	const sort_desc					= 'desc';
	
	const param_media				= 'media';
	const param_recursive		= 'recursive';
	const param_include			= 'include';
	const param_exclude			= 'exclude';
	const param_kit_intern	= 'kit_intern';
	const param_kit_news		= 'kit_news';
	const param_kit_dist		= 'kit_dist';
	const param_wb_group		= 'wb_group';
	const param_copyright		= 'copyright';
	const param_sort				= 'sort';
	const param_wb_auto			= 'wb_auto';
	const param_kit_auto		= 'kit_auto';
	const param_upload			= 'upload';
	const param_unlink			= 'unlink';
	const param_mkdir				= 'mkdir';
	
	// params come from the droplet [[kit_dirlist]]
	private $params = array(
		self::param_media				=> '',
		self::param_recursive		=> false,
		self::param_include			=> '',
		self::param_exclude			=> '',
		self::param_kit_intern	=> '',
		self::param_kit_news		=> '',
		self::param_kit_dist		=> '',
		self::param_wb_group		=> '',
		self::param_copyright		=> true,
		self::param_sort				=> self::sort_asc,
		self::param_wb_auto			=> false,
		self::param_kit_auto		=> false,
		self::param_upload			=> false,
		self::param_unlink			=> false,
		self::param_mkdir				=> false
	);
	
	const session_prefix		= 'kdl_';
	const session_protect		= 'pct';		// protected access?
	const session_user			= 'usr';		// username
	const session_auth			= 'aut';		// is user authorized?
	const session_wb_grps		= 'grps';   // IDs of WB Groups
	const session_admin			= 'adm';
	
	const protect_none			= 'nn';
	const protect_undefined	= 'udf';
	const protect_group			= 'grp';
	const protect_kit				= 'kit';
	const protect_wb				= 'wb';
	
	const protection_folder = 'kit_protected';
	const contacts_folder		= 'contacts';
	const kdl_anchor				= 'kdl';
	const description_file 	= 'dirlist.txt';
	
	private $message = '';
	private $error = '';
	private $silent = true;
	private $protected_path = '';
	private $protected_url = '';
	private $contacts_path = '';
	private $contacts_url = '';
	private $template_path = '';
	private $old_pass = array();
	private $kit_installed = false;
	private $media_path = '';
	private $media_url = '';
	private $base_path = '';
	private $base_url = '';
	private $wb_login = false;
	private $is_authenticated = false;
	private $page_link = '';
	private $icon_url = '';
	private $descriptions = array();
	//private $is_admin = false;
	
	private $general_excluded_extensions = array(
		'php',
		'php3',
		'php4',
		'php5',
		'php6',
		'phps'
	);
	
	private $general_excluded_files = array(
		self::description_file
	);

	
	public function __construct($silent=true) {
		global $kdlTools;
		$this->silent = $silent;
		$this->media_path = WB_PATH.MEDIA_DIRECTORY.'/';
		$this->media_url = WB_URL.MEDIA_DIRECTORY.'/';
		$this->protected_path = WB_PATH.MEDIA_DIRECTORY.'/'.self::protection_folder.'/';
		$this->protected_url  = WB_URL.MEDIA_DIRECTORY.'/'.self::protection_folder.'/';
		$this->contacts_path = $this->protected_path.self::contacts_folder.'/';
		$this->contacts_url = $this->protected_url.self::contacts_folder.'/';
		$this->template_path = WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/htt/';
		$this->kit_installed = (file_exists(WB_PATH.'/modules/kit/class.contact.php')) ? true : false;
		// check if $_SESSIONs are already defined - protect access by default!
		if (!isset($_SESSION[self::session_prefix.self::session_protect])) $_SESSION[self::session_prefix.self::session_protect] = self::protect_undefined;
		if (!isset($_SESSION[self::session_prefix.self::session_user])) $_SESSION[self::session_prefix.self::session_user] = '';
		if (!isset($_SESSION[self::session_prefix.self::session_auth])) $_SESSION[self::session_prefix.self::session_auth] = 0;
		$this->is_authenticated = ($_SESSION[self::session_prefix.self::session_auth] == 1) ? true : false;
		// check if WB LOGIN is allowed
		$this->wb_login = (defined('LOGIN_URL')) ? true : false;
		$url = '';
		$kdlTools->getPageLinkByPageID(PAGE_ID, $url);
		$this->page_link = $url;  	
		$this->icon_url = WB_URL.'/modules/'.basename(dirname(__FILE__)).'/img/16x16/';
		unset($_SESSION['KIT_EXTENSION']);
	} // __construct()
	
	/**
   * Return Version of Module
   *
   * @return FLOAT
   */
  public function getVersion() {
    // read info.php into array
    $info_text = file(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/info.php');
    if ($info_text == false) {
      return -1; 
    }
    // walk through array
    foreach ($info_text as $item) {
      if (strpos($item, '$module_version') !== false) {
        // split string $module_version
        $value = explode('=', $item);
        // return floatval
        return floatval(preg_replace('([\'";,\(\)[:space:][:alpha:]])', '', $value[1]));
      } 
    }
    return -1;
  } // getVersion()
	
	/**
	 * returns the parameters
	 * @return ARRAY $params
	 */
	public function getParams() {
		return $this->params;
	} // getParams()
	
	/**
	 * Set the params for kitDirList
	 * @param ARRAY $params
	 * @return BOOL
	 */
	public function setParams($params=array()) {
		global $kdlTools;
		// set default values
		foreach ($this->params as $key => $value) {
			switch($key):
			case self::param_media:
				$this->params[$key] = ''; break;
			case self::param_wb_auto:
			case self::param_kit_auto:
			case self::param_recursive:
			case self::param_upload:
			case self::param_unlink:
			case self::param_mkdir:
				$this->params[$key] = false; break;
			case self::param_copyright:
				$this->params[$key] = true; break;
			case self::param_include:
			case self::param_exclude:
			case self::param_kit_intern:
			case self::param_kit_news:
			case self::param_kit_dist:
			case self::param_wb_group:
				$this->params[$key] = ''; break;
			case self::param_sort:
				$this->params[$key] = self::sort_asc; break;
			default:
				$this->params[$key] = 'undefined'; break;
			endswitch;
		}
		// get the new values
		$skip_param_media = false;
		foreach ($params as $key => $value) {
			if (key_exists($key, $this->params)) {
				switch ($key):
				case self::param_media:
					if ($skip_param_media) break;
					if (!empty($value)) $value = $kdlTools->addSlash($value); 
					$this->params[$key] = $value;
					break;
				case self::param_wb_auto:
				case self::param_kit_auto: 
					$this->params[$key] = (bool) $value; 
					if ($this->params[$key]) {
						// if set to auto mode set also media directory
						$this->params[self::param_media] = self::protection_folder.'/'.self::contacts_folder.'/'; 
						$skip_param_media = true;
					}
					break;
				case self::param_recursive:
				case self::param_copyright:	
				case self::param_upload:
				case self::param_unlink:
				case self::param_mkdir:
					$this->params[$key] = (bool) $value;
					break;
				case self::param_kit_intern:
				case self::param_kit_news:
				case self::param_kit_dist:
					if (empty($value)) break;
					if (!$this->kit_installed) {
						$this->setError(sprintf(kdl_error_kit_param_rejected, $key));
						return false;
					}
					$arr = explode(',', $value);
					$para = array();
					foreach ($arr as $item) {
						$para[] = trim($item);
					}
					$this->params[$key] = implode(',', $para);
					break;
				case self::param_include:
				case self::param_exclude:
				case self::param_wb_group:
					$para = array();
					$arr = explode(',', $value);
					foreach ($arr as $item) {
						$val = trim($item);
						if (($key == self::param_exclude || $key == self::param_include) & (!empty($val))) {
							$val = strtolower($val);
							if (strpos($val, '.') !== false) $val = str_replace('.', '', $val); 
						}
						$para[] = $val;
					}
					$this->params[$key] = implode(',', $para);
					break;
				case self::param_sort:
					$this->params[$key] = (strtolower($value) == self::sort_asc) ? self::sort_asc : self::sort_desc;
					break;	
				endswitch;
			}	
			else {
				$this->setError(sprintf(kdl_error_unknown_param_key, $key));
				return false;
			}		
		}
		return true;
	} // setParams()
	
	/**
	 * Art des Schutzes pruefen und festlegen
	 */
	private function checkProtection() { 
		if (strpos($this->base_path, $this->protected_path) !== false) {
			// base path reside within the protected path
			if (!empty($_SESSION[self::session_prefix.self::param_kit_news]) ||
					!empty($_SESSION[self::session_prefix.self::param_kit_dist]) ||
					!empty($_SESSION[self::session_prefix.self::param_kit_intern])) {
				// ok - pruefen ob der angeforderte Schutz mit KIT moeglich ist.
				if (!$this->kit_installed) {
					$this->setError(kdl_error_kit_not_installed);
					return false;
				}		
				// KIT einbinden
				require_once(WB_PATH.'/modules/kit/initialize.php');
				$dbContactArray = new dbKITcontactArrayCfg();
				$categories = array();
				if (!empty($_SESSION[self::session_prefix.self::param_kit_news])) {
					$categories[] = self::param_kit_news;
				}
				elseif (!empty($_SESSION[self::session_prefix.self::param_kit_dist])) {
					$categories[] = self::param_kit_dist;
				}
				elseif (!empty($_SESSION[self::session_prefix.self::param_kit_intern])) {
					$categories[] = self::param_kit_intern;
				}
				foreach ($categories as $category) {
					// Pruefen ob die Kategorien existieren
					$x = explode(',', $_SESSION[self::session_prefix.$category]);
					$cats = '';
					foreach ($x as $c) {
						if (!empty($cats)) $cats .= ' OR ';
						$cats .= sprintf("%s='%s'", dbKITcontactArrayCfg::field_identifier, $c);
					}
					$SQL = sprintf( "SELECT * FROM %s WHERE %s AND %s='%s'",
													$dbContactArray->getTableName(),
													$cats,
													dbKITcontactArrayCfg::field_status,
													dbKITcontactArrayCfg::status_active);
					$cfgArray = array();
					if (!$dbContactArray->sqlExec($SQL, $cfgArray)) {
						$this->setError($dbContactArray->getError());
						return false;
					}
					if (count($cfgArray) < 1) {
						$this->setError(sprintf(kdl_error_missing_kit_category, $category, $_SESSION[self::session_prefix.$category]));
						return false;
					}
				}
				$_SESSION[self::session_prefix.self::session_protect] = self::protect_kit;		
			}
			elseif (!empty($_SESSION[self::session_prefix.self::param_wb_group])) {
				if (!$this->wb_login) {
					// WB Anmeldung ist ausgeschaltet
					$this->setError(sprintf(kdl_error_wb_login_not_enabled, self::param_wb_group));
					return false;
				}
				global $database;
				$groups = explode(',', $_SESSION[self::session_prefix.self::param_wb_group]);
				$wb_groups_id = array();
				foreach ($groups as $group) {
					$SQL = sprintf(	"SELECT group_id FROM %sgroups WHERE name='%s'", TABLE_PREFIX, $group);
					if (false ===($result = $database->query($SQL))) {
						$this->setError($database->get_error());
						return false;
					}
					$data = $result->fetchRow(MYSQL_ASSOC);
					if (!isset($data['group_id'])) {
						$this->setError(sprintf(kdl_error_missing_wb_group, self::param_wb_group, $group));
						return false;
					}
					$wb_groups_id[] = $data['group_id'];
				}			
				$_SESSION[self::session_prefix.self::session_protect] = self::protect_group;
				$_SESSION[self::session_prefix.self::session_wb_grps] = implode(',', $wb_groups_id);
			}
			elseif (!empty($_SESSION[self::session_prefix.self::param_wb_auto])) {
				// use automatic user directories
				if (!$this->wb_login) {
					// WB Anmeldung ist ausgeschaltet
					$this->setError(sprintf(kdl_error_wb_login_not_enabled, self::param_wb_group));
					return false;
				}
				$_SESSION[self::session_prefix.self::session_protect] = self::protect_wb;
			}
			elseif (!empty($_SESSION[self::session_prefix.self::param_kit_auto])) {
				// use automatic user directories
				if (!$this->kit_installed) {
					$this->setError(kdl_error_kit_not_installed);
					return false;
				}		
				$_SESSION[self::session_prefix.self::session_protect] = self::protect_kit;
			}
			else {
				$_SESSION[self::session_prefix.self::session_protect] = self::protect_none;
				$this->setError(kdl_error_protection_undefined);
				return false;
			}
		}
		else { 
			$_SESSION[self::session_prefix.self::session_protect] = self::protect_none;
			if (!empty($_SESSION[self::session_prefix.self::param_kit_news]) ||
					!empty($_SESSION[self::session_prefix.self::param_kit_dist]) ||
					!empty($_SESSION[self::session_prefix.self::param_kit_intern]) ||
					!empty($_SESSION[self::session_prefix.self::param_wb_group])) {
				$this->setError(kdl_error_public_dir_but_protect);
				return false;		
			}			
		}
		return true;
	} // checkSession()
	
	/**
	 * Check the authentication by the desired access method.
	 * Set the different $_SESSION vars for further checks and controls
	 * @return BOOL true on success or STR login dialog or error message
	 */
	private function checkAuthentication() {
		global $kdlTools;
		global $parser;
		global $wb;
		
		if ($_SESSION[self::session_prefix.self::session_protect] == self::protect_none) {
			// no protection needed
			$_SESSION[self::session_prefix.self::session_auth] = 0;
			$this->is_authenticated = true;
			return true;
		}
		elseif ($_SESSION[self::session_prefix.self::session_protect] == self::protect_kit) { 
			// Protection by KeepInTouch login
			if (!$this->kit_installed) {
				$this->setError(kdl_error_kit_not_installed);
				return $this->getError();
			}
			if (isset($_SESSION['kit_aid']) && isset($_SESSION['kit_key'])) { 
				// KIT User ist bereits angemeldet
				require_once(WB_PATH.'/modules/kit/initialize.php');
				global $dbContact;
				global $dbRegister;
				$where = array(
					dbKITregister::field_id => $_SESSION['kit_aid'],
					dbKITregister::field_status => dbKITregister::status_active
				);
				$register = array();
				if (!$dbRegister->sqlSelectRecord($where, $register)) {
					$this->setError($dbRegister->getError());
					return $this->getError();
				}				
				if (count($register) < 1) {
					$this->setError(sprintf(kdl_error_kit_register_id_missing, $_SESSION['kit_aid']));
					return $this->getError();
				}
				$register = $register[0];
				// E-Mail Adresse des Users festhalten
				$_SESSION[self::session_prefix.self::session_user] = $register[dbKITregister::field_email];
				// read contact
				$contact = array();
				if (!$dbContact->getContactByID($register[dbKITregister::field_contact_id], $contact)) {
					$this->setError($dbContact->getError());
					return $this->getError();
				}
				if (count($contact) < 1) {
					$this->setError(sprintf(kdl_error_kit_id_missing, $register[0][dbKITregister::field_contact_id]));
					return $this->getError();
				}
				if ($_SESSION[self::session_prefix.self::param_kit_auto] == false) {
					// Kategorien des Users nur pruefen, wenn kit_auto NICHT aktiv ist
					$kg = $contact[dbKITcontact::field_category];
					if (!empty($contact[dbKITcontact::field_distribution])) {
						if (!empty($kg)) $kg .= ',';
						$kg .= $contact[dbKITcontact::field_distribution];
					}
					if (!empty($contact[dbKITcontact::field_newsletter])) {
						if (!empty($kg)) $kg .= ',';
						$kg .= $contact[dbKITcontact::field_newsletter];
					}
					$kit_groups = explode(',', $kg);
					
					$grps = $_SESSION[self::session_prefix.self::param_kit_dist]; 
					if (!empty($_SESSION[self::session_prefix.self::param_kit_intern])) {
						if (!empty($grps)) $grps .= ',';
						$grps .= $_SESSION[self::session_prefix.self::param_kit_intern];
					}
					if (!empty($_SESSION[self::session_prefix.self::param_kit_news])) {
						if (!empty($grps)) $grps .= ',';
						$grps .= $_SESSION[self::session_prefix.self::param_kit_news];
					}
					$groups = explode(',', $grps);
					$group_ok = false;
					foreach ($groups as $group) {
						if (in_array($group, $kit_groups)) {
							$group_ok = true;
							break;
						}
					}
					if (!$group_ok) {
						// nicht berechtigt
						$data = array(
							'header'		=> kdl_header_access_denied,
							'content'		=> kdl_msg_access_denied
						);
						return $parser->get($this->template_path.'frontend.prompt.htt', $data);
					}
				}
				// Benutzer freigeben
				$_SESSION[self::session_prefix.self::session_auth] = 1;
				$this->is_authenticated = true;
				
				// check if user is admin...
				$admin_emails = array();
				if ($dbRegister->getAdmins($admin_emails) && (in_array($_SESSION[self::session_prefix.self::session_user], $admin_emails))) { 
					$_SESSION[self::session_prefix.self::session_admin] = true;
				} 
				return true;
			}
			else {
				// KIT User ist nicht angemeldet
				return $this->dlgKITaccount();
			}			
		}
		elseif ($_SESSION[self::session_prefix.self::session_protect] == self::protect_group) {
			// Protection by WB Group
			if (!$wb->is_authenticated()) {
				// Benutzer ist nicht angemeldet
				$url = '';
				$kdlTools->getPageLinkByPageID(PAGE_ID, $url);
				$data = array(
					'header'		=> kdl_header_login,
					'content'		=> sprintf(kdl_content_login_wb, LOGIN_URL.'?redirect='.$url)
				);
				// Anmeldedialog anzeigen
				return $parser->get($this->template_path.'frontend.login.wb.htt', $data);
			}
			else {
				// pruefen ob der Anwender berechtigt ist auf die Daten zuzugreifen
				if (!isset($_SESSION['GROUPS_ID']) || !isset($_SESSION[self::session_prefix.self::session_wb_grps])) {
					$this->setError(kdl_error_wb_groups_undefined);
					return $this->getError();
				}
				$groups = explode(',', $_SESSION['GROUPS_ID']);
				$group_ok = false;
				$kdl_wb_groups = explode(',', $_SESSION[self::session_prefix.self::session_wb_grps]);
				foreach ($groups as $group) {
					if (in_array($group, $kdl_wb_groups)) {
						$group_ok = true;
						break;
					}
				}
				if (!$group_ok) {
					// nicht berechtigt
					$data = array(
						'header'		=> kdl_header_access_denied,
						'content'		=> kdl_msg_access_denied
					);
					return $parser->get($this->template_path.'frontend.prompt.htt', $data);
				}
				$_SESSION[self::session_prefix.self::session_user] = $_SESSION['EMAIL'];
				$_SESSION[self::session_prefix.self::session_auth] = 1;
				$this->is_authenticated = true;
				return true;
			}
		}
		elseif ($_SESSION[self::session_prefix.self::session_protect] == self::protect_wb) {
			// Protection by WB USER Authentication
			if (!$wb->is_authenticated()) {
				// Benutzer ist nicht angemeldet
				$url = '';
				$kdlTools->getPageLinkByPageID(PAGE_ID, $url);
				$data = array(
					'header'		=> kdl_header_login,
					'content'		=> sprintf(kdl_content_login_wb, LOGIN_URL.'?redirect='.$url)
				);
				// Anmeldedialog anzeigen
				return $parser->get($this->template_path.'frontend.login.wb.htt', $data);
			}
			require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.users.php');
			$dbGroups = new dbWBgroups();
			$where = array(dbWBgroups::field_name => 'Administrators');
			$groups = array();
			if (!$dbGroups->sqlSelectRecord($where, $groups)) {
				$this->setError($dbGroups->getError());
				return $this->getError();
			}
			if (count($groups) < 1) {
				$this->setError(kdl_error_wb_groups);
				return $this->getError();
			}
			// check if user is admin...
			if ($_SESSION['GROUP_ID'] == $groups[0][dbWBgroups::field_group_id]) {
				$_SESSION[self::session_prefix.self::session_admin] = true;
			}
			return true;
		}
		else {
			$this->setError(sprintf(kdl_error_unknown_param, $_SESSION[self::session_prefix.self::session_protect]));
			return $this->getError();
		}
	} // checkAuthentication()
	
	public function dlgKITaccount($action='') {
		global $dbCfg; // KIT config
		global $dbDialogsRegister;
		global $kdlTools;
		if (!$this->kit_installed) {
			$this->setError(kdl_error_kit_not_installed);
			return $this->getError();
		}
		// get KIT Dialog Framework
		require_once(WB_PATH.'/modules/kit/class.dialogs.php');
		// get the KIT Account dialog which should be used
  	$dialog_account = $dbCfg->getValue(dbKITcfg::cfgRegisterDlgACC);
  	$where = array(dbKITdialogsRegister::field_name => $dialog_account);
  	$regDialogs = array();
  	if (!$dbDialogsRegister->sqlSelectRecord($where, $regDialogs)) {
  		$this->setError($dbDialogsRegister->getError());
  		return $this->getError();
  	}
  	if (count($regDialogs) < 1) {
  		$this->setError(sprintf(kit_error_dlg_missing, $dialog_account));
  		return $this->getError();
  	}
		if (file_exists(WB_PATH.'/modules/kit/dialogs/'.strtolower($dialog_account).'/'.strtolower($dialog_account).'.php')) {
  		require_once(WB_PATH.'/modules/kit/dialogs/'.strtolower($dialog_account).'/'.strtolower($dialog_account).'.php');
  		// call Account Dialog				
  		unset($_SESSION['KIT_REDIRECT']);
  		$_SESSION['KIT_EXTENSION'] = array('link' => $this->page_link, 'name' => MENU_TITLE);
			$callDialog = new $dialog_account(true);
			$callDialog->setDlgID($regDialogs[0][dbKITdialogsRegister::field_id]);
			if (!empty($action)) $_REQUEST['acc_act'] = $action;
			$result = $callDialog->action();
			$url = '';				
			$kdlTools->getPageLinkByPageID(PAGE_ID, $url);
			$_SESSION['KIT_REDIRECT'] = $url;
			return $result;
		}
		else {
			$this->setError(sprintf(kit_error_dlg_missing, $dialog));
			return $this->getError();
		}  		
	} // dlgKITaccount()
	
	/**
    * Set $this->error to $error
    * 
    * @param STR $error
    */
  public function setError($error) {
  	$caller = next(debug_backtrace());
  	$this->error = sprintf('[%s::%s - %s] %s', basename($caller['file']), $caller['function'], $caller['line'], $error);
  } // setError()

  /**
    * Get Error from $this->error;
    * 
    * @return STR $this->error
    */
  public function getError() {
    return $this->error;
  } // getError()

  /**
    * Check if $this->error is empty
    * 
    * @return BOOL
    */
  public function isError() {
    return (bool) !empty($this->error);
  } // isError
  
  /** Set $this->message to $message
    * 
    * @param STR $message
    */
  public function setMessage($message) {
    $this->message = $message;
  } // setMessage()

  /**
    * Get Message from $this->message;
    * 
    * @return STR $this->message
    */
  public function getMessage() {
    return $this->message;
  } // getMessage()

  /**
    * Check if $this->message is empty
    * 
    * @return BOOL
    */
  public function isMessage() {
    return (bool) !empty($this->message);
  } // isMessage
  
	/**
   * Prevents XSS Cross Site Scripting
   * 
   * @param REFERENCE $_REQUEST Array
   * @return $request
   */
	public function xssPrevent(&$request) {
  	if (is_string($request)) {
	    $request = html_entity_decode($request);
	    $request = strip_tags($request);
	    $request = trim($request);
	    $request = stripslashes($request);
  	}
	  return $request;
  } // xssPrevent()
	
  /**
   * Action handler of class kitDirList
   */
	public function action() {
		// check if there are errors...
		if ($this->isError()) return $this->show();
		
		// get params to $_SESSION...
		foreach ($this->params as $key => $value) {
			if (!isset($_SESSION[self::session_prefix.$key]) || ($_SESSION[self::session_prefix.$key] !== $value)) $_SESSION[self::session_prefix.$key] = $value;
		}
		// fields with HTML code
  	$html_allowed = array();
  	foreach ($_REQUEST as $key => $value) {
  		if (!in_array($key, $html_allowed)) {
  			$_REQUEST[$key] = $this->xssPrevent($value);
  		}
  	}
  	// check the media paths
  	if (!$this->checkPaths()) return $this->show();
  	// check the session
 		if (!$this->checkProtection()) return $this->show();
  	// get action...
    isset($_REQUEST[self::request_action]) ? $action = $_REQUEST[self::request_action] : $action = self::action_start;
  	// check authentication and return login if neccessary...
  	if ((!$this->is_authenticated) && (is_string($login = $this->checkAuthentication()))) return $login;
  	
  	$account = false;
  	switch ($action):
		case self::action_logout:
			$result = $this->logout();
			break;
		case self::action_account:
			$result = $this->dlgKITaccount();
			$account = true;
			break;
		case self::action_start:
		default: 
			$result = $this->directoryListing();	
		endswitch;		
		
		return $this->show($result, $account);
	} // action()
	
	/**
	 * ECHO or RETURN the result dialog depending on switch SILENT
	 * @param STR $result
	 */
	public function show($result='- no content -', $account=false) {
		// check if there was an error...
		if ($this->isError()) $result = sprintf('<div class="kdl_error"><h1>%s</h1>%s</div>', kdl_header_error, $this->getError());
		if (isset($_SESSION[self::session_prefix.self::param_copyright]) && ($_SESSION[self::session_prefix.self::param_copyright] == true)) {
			// display copyright informations
			$result = sprintf('%s<div style="margin:0;padding:10px 0;font-size:7pt;text-align:center;color:#808080;background-color:transparent;">'.
												'<b>kitDirList</b> %01.2f - &copy; %d by <a href="mailto:ralf.hertsch@phpmanufaktur.de">Ralf Hertsch</a>, Berlin (Germany)<br />'.
												'Please visit <a href="http://phpmanufaktur.de" target="_blank">phpManufaktur</a> for more informations about <a href="http://phpmanufaktur.de/kit_dirlist" target="_blank">kitDirList</a>.</div>', 
												$result, $this->getVersion(), date('Y'));
		}
		if (!$account && isset($_SESSION[self::session_prefix.self::session_protect]) &&
				($_SESSION[self::session_prefix.self::session_protect] == self::protect_kit)) {
			// display logout link if necessary...
			$result = sprintf('<a name="%s"></a><div class="kdl_body"><div class="kdl_logout"><a href="%s">%s</a> &bull; <a href="%s">%s</a></div>%s</div>',
												self::kdl_anchor,
												sprintf('%s?%s=%s', $this->page_link, self::request_action, self::action_account),
												kdl_btn_account,
												sprintf('%s?%s=%s', $this->page_link, self::request_action, self::action_logout),
												kdl_btn_logout,
												$result); 		
		}
		elseif (isset($_SESSION[self::session_prefix.self::session_protect]) && 
						(($_SESSION[self::session_prefix.self::session_protect] == self::protect_wb) ||
						 ($_SESSION[self::session_prefix.self::session_protect] == self::protect_group))) {
			// display logout link if necessary...
			$result = sprintf('<a name="%s"></a><div class="kdl_body"><div class="kdl_logout"><a href="%s">%s</a></div>%s</div>',
												self::kdl_anchor,
												sprintf('%s?%s=%s', $this->page_link, self::request_action, self::action_logout),
												kdl_btn_logout,
												$result);
		}
		else {
			$result = sprintf('<a name="%s"></a><div class="kdl_body">%s</div>', self::kdl_anchor, $result);
		}
		if ($this->silent) return $result;
		echo $result;
	} // show()
	
	public function logout() {		
		// unset all session vars...
		unset($_SESSION[self::session_prefix.self::session_user]);
		unset($_SESSION[self::session_prefix.self::session_auth]);
		unset($_SESSION[self::session_prefix.self::session_wb_grps]);
		unset($_SESSION[self::session_prefix.self::session_admin]);
		foreach ($this->params as $param) {
			unset($_SESSION[self::session_prefix.$param]);
		}
		if (($_SESSION[self::session_prefix.self::session_protect] == self::protect_group) ||
				($_SESSION[self::session_prefix.self::session_protect] == self::protect_wb)) {
			// WebsiteBaker Logout
			unset($_SESSION[self::session_prefix.self::session_protect]);
			header("Location: ".LOGOUT_URL);
		}
		elseif ($_SESSION[self::session_prefix.self::session_protect] == self::protect_kit) {
			// KeepInTouch Logout
			unset($_SESSION[self::session_prefix.self::session_protect]);
			return $this->dlgKITaccount('out');			
		}
		// otherwise only unset the protected session...
		unset($_SESSION[self::session_prefix.self::session_protect]);
	} // logout()
	
	private function checkPaths() {
		// check protected path
		if (!file_exists($this->protected_path)) {
			// create directory
			if (!mkdir($this->protected_path, 0755)) {
				$this->setError(sprintf(kdl_error_create_dir, $this->protected_path));
				return false;
			}
			// create directory protection
			if (!$this->createProtection()) return false;
		}
		// check directory protection
		if (!file_exists($this->protected_path.'.htaccess') || !file_exists($this->protected_path.'.htpasswd')) {
			if (!$this->createProtection()) return false;
		}		
		// check contacts path
		if (!file_exists($this->contacts_path)) {
			if ($this->kit_installed) {
				// KIT installed
				require_once(WB_PATH.'/modules/kit/class.config.php');
				require_once(WB_PATH.'/modules/kit/class.contact.php');
				global $dbRegister;
				$SQL = sprintf( "SELECT * FROM %s WHERE %s='%s'",
												$dbRegister->getTableName(),
												dbKITregister::field_status,
												dbKITregister::status_active);
				if (!$dbRegister->sqlExec($SQL, $registers)) {
					$this->setError($dbRegister->getError());
					return false;
				}
				foreach ($registers as $register) {
					$email = $register[dbKITregister::field_email];
					$user_path = $this->contacts_path.$email[0].'/'.$email.'/user/';
					$admin_path = $this->contacts_path.$email[0].'/'.$email.'/admin/';
					foreach (array($user_path, $admin_path) as $path) {
						if (!file_exists($path)) {
							if (!mkdir($path, 0755, true)) {
								$this->setError(sprintf(kdl_error_create_dir, $path));
								return false;
							}
						}
					}
				}
			}
			else {
				// WB USER administration
				require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.users.php');
				
				$dbGroups = new dbWBgroups();
				$where = array(dbWBgroups::field_name => 'Administrators');
				$admin_grps = array();
				if (!$dbGroups->sqlSelectRecord($where, $admin_grps)) {
					$this->setError($dbGroups->getError());
					return false;
				}
				$admin_grp = $admin_grps[0][dbWBgroups::field_group_id];
				
				$dbUsers = new dbWBusers();
				$SQL = sprintf( "SELECT * FROM %s WHERE %s='%s' AND %s!='%s'",
												$dbUsers->getTableName(),
												dbWBusers::field_active,
												dbWBusers::status_active,
												dbWBusers::field_group_id,
												$admin_grp);
				if (!$dbUsers->sqlExec($SQL, $users)) {
					$this->setError($dbUsers->getError());
					return false;
				}
				foreach ($users as $user) {
					$email = $user[dbWBusers::field_email];
					$user_path = $this->contacts_path.$email[0].'/'.$email.'/user/';
					$admin_path = $this->contacts_path.$email[0].'/'.$email.'/admin/';
					foreach (array($user_path, $admin_path) as $path) {
						if (!file_exists($path)) {
							if (!mkdir($path, 0755, true)) {
								$this->setError(sprintf(kdl_error_create_dir, $path));
								return false;
							}
						}
					}
				}
			}
		} // check contacts path
		// set base_path
		if (empty($this->base_path)) $this->base_path = $this->media_path.$_SESSION[self::session_prefix.self::param_media];
		// check base path
		if (!file_exists($this->base_path)) {
			// base directory does not exists
			$this->setError(sprintf(kdl_error_dir_not_exists, $this->base_path));
			return false;
		}
		return true;
	}  // checkPaths()
	
	/**
   * Generiert ein neues Passwort der Laenge $length
   *
   * @param INT $length
   * @return STR
   */
  public function generatePassword($length=7) {
    $r = array_merge(
      range("a", "z"),
      range("a", "z"),
      range("A", "Z"),
      range(1, 9),
      range(1, 9)
    );
		$not = array_merge(
			array('i', 'l', 'o', 'I','O'),
			$this->old_pass
		);		
		$r = array_diff($r, $not);
    shuffle($r);
		$this->old_pass = array_slice($r, 0, intval($length) );
    return implode("", $this->old_pass );
  } // generatePassword()
	
  /**
   * Sucht nach der Datei dirlist.txt im Verzeichnis und
   * versucht aus dieser Datei Beschreibungen zu den einzelnen
   * Dateien auszulesen
   * 
   * @param STR directory path
   */
  private function getFileDescriptions($directory) {
  	// reset description array
  	$this->file_descriptions = array();
  	$desc_file = $directory.self::description_file;
  	if (!file_exists($desc_file)) return false;
  	if (false === ($descriptions = file($desc_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES))) return false;
  	foreach ($descriptions as $line) {
  		if (strpos($line, '|') !== false) {
  			$desc = explode('|', $line);
  			if (count($desc) == 2) {
  				// get file description - lower filename and replace " with ' to avoid conflicts with title tag
  				$this->descriptions[strtolower($desc[0])] = str_replace('"', "'", $desc[1]); 
  			}
  		}
  	}
  	return true;
  } // getFileDescriptions()
  
	/**
	 * Anzeige des Verzeichnis
	 * 
	 * @return STR Directory
	 */
	public function directoryListing() {
		global $parser;
		global $kdlTools;
		global $wb;
		
		$is_admin = false;
		$message = '';
		if (isset($_SESSION[self::session_prefix.self::session_admin]) && $_SESSION[self::session_prefix.self::session_admin]) {
			$is_admin = true;
			$message = kdl_msg_admin_mode;
			// admins get full access, so set some params...
			$_SESSION[self::session_prefix.self::param_upload] = true;
			$this->params[self::param_upload] = true;
			$_SESSION[self::session_prefix.self::param_recursive] = true;
			$this->params[self::param_recursive] = true;
			$_SESSION[self::session_prefix.self::param_mkdir] = true;
			$this->params[self::param_mkdir] = true;
			$_SESSION[self::session_prefix.self::param_unlink] = true;
			$this->params[self::param_unlink] = true;
			$_SESSION[self::session_prefix.self::param_copyright] = true;
			$this->params[self::param_copyright] = true;
		}
		
		if ((isset($_SESSION[self::session_prefix.self::param_mkdir]) && ($_SESSION[self::session_prefix.self::param_mkdir] == true)) &&
				(!isset($_SESSION[self::session_prefix.self::param_recursive]) || 
				(isset($_SESSION[self::session_prefix.self::param_recursive]) && ($_SESSION[self::session_prefix.self::param_recursive] == false)))) {
			// if set param mkdir the param recursive must be set too...
			$_SESSION[self::session_prefix.self::param_recursive] = true;
			$this->params[self::param_recursive] = true;		
		}
		
		// Access to Mime Types
		$mimeType = new mimeTypes();
		// Sorting files
		$files_sort = (isset($_REQUEST[self::request_sort])) ? $_REQUEST[self::request_sort] : $_SESSION[self::session_prefix.self::param_sort];
				
		if (($_SESSION[self::session_prefix.self::session_protect] == self::protect_group) ||
				($_SESSION[self::session_prefix.self::session_protect] == self::protect_kit) ||
				($_SESSION[self::session_prefix.self::session_protect] == self::protect_wb)) {
			$redirect = true;		
			$dbLink = new dbKITdirList();
		}
		else {
			$redirect = false;
		}
		
		// email address
		if (isset($_SESSION[self::session_prefix.self::session_user]) && !empty($_SESSION[self::session_prefix.self::session_user])) {
			$email = $_SESSION[self::session_prefix.self::session_user];
		}
		elseif (isset($_SESSION['EMAIL']) && !empty($_SESSION['EMAIL'])) {
			$email = $_SESSION['EMAIL'];
		}
		else {
			$email = '';
		}

		if (!$is_admin && ($this->params[self::param_kit_auto] || $this->params[self::param_wb_auto])) { 
			// use automatic user directories
			$dir = $this->base_path.$email[0].'/'.$email.'/user/';
			if (!file_exists($dir)) {
				if (!mkdir($dir, 0755, true)) {
					$this->setError(sprintf(kdl_error_create_dir, $dir));
					return false;
				}
			}
			$adm = $this->base_path.$email[0].'/'.$email.'/admin/';
			if (!file_exists($adm)) {
				if (!mkdir($adm, 0755, true)) {
					$this->setError(sprintf(kdl_error_create_dir, $adm));
					return false;
				}
			}
		}
		else {
			if (!$is_admin && stripos($this->base_path, $this->media_path.self::protection_folder.'/'.self::contacts_folder.'/') !== false) {
				$this->setError(kdl_error_contacts_access);
				return false;
			}			
			$dir = $this->base_path; 
		} 
		
		// sub directories
		$is_sub_dir = false;
		$sub_dir = '';
				
		if (isset($_REQUEST[self::request_sub_dir])) {
			// Unterverzeichnis angefordert
			if (file_exists($dir.$_REQUEST[self::request_sub_dir])) {
				$dir = $dir.$_REQUEST[self::request_sub_dir].'/';
				$is_sub_dir = true;
				$sub_dir = $_REQUEST[self::request_sub_dir];
			}
		}
		$dir_url = str_replace(WB_PATH, WB_URL, $dir);
		
		if (!file_exists($dir))	{
			$this->setError(sprintf(kdl_error_dir_not_exists, $dir));
			return false;
		}
		
		if (isset($_REQUEST[self::request_mkdir]) && !empty($_REQUEST[self::request_mkdir])) {
			// create directory
			$mkdir = media_filename($_REQUEST[self::request_mkdir]);
			if (!file_exists($dir.'/'.$mkdir)) {
				if (!mkdir($dir.'/'.$mkdir, 0755)) {
					$this->setError(sprintf(kdl_error_create_dir, $mkdir));
					return false;
				}
				else {
					$message .= sprintf(kdl_msg_mkdir_success, $mkdir);
				}
			}
		}
		
		if (isset($_REQUEST[self::request_action]) && ($_REQUEST[self::request_action] == self::action_upload)) {
			// probably file uploaded...
			if (isset($_FILES[self::request_file]) && (is_uploaded_file($_FILES[self::request_file]['tmp_name']))) {
				if ($_FILES[self::request_file]['error'] == UPLOAD_ERR_OK) {
					// check if uploaded file is forbidden
					$ext = end(explode('.', $_FILES[self::request_file]['name']));
					if ((in_array($_FILES[self::request_file]['tmp_name'], $this->general_excluded_files)) ||
							(in_array($ext, $this->general_excluded_extensions))) {
						// disallowed file or filetype - delete uploaded file
						@unlink($_FILES[self::request_file]['tmp_name']);
						$message .= sprintf(kdl_error_file_type_forbidden, basename($_FILES[self::request_file]['name']));
					}
					else {
						$tmp_file = $_FILES[self::request_file]['tmp_name'];
						$upl_file =$dir. media_filename($_FILES[self::request_file]['name']);
						if (!move_uploaded_file($tmp_file, $upl_file)) {
							// error moving file
							$this->setError(sprintf(kdl_error_upload_move_file, $upl_file)); 
							return false;
						}
						else {
							$message .= sprintf(kdl_msg_upload_success, basename($upl_file));
							$data = array(
								'email' => (empty($email)) ? kdl_text_anonymous : $email,
								'file'	=> basename($upl_file) 
							);
							$body = $parser->get($this->template_path.'mail.upload.success.admin.htt', $data);
							$to_emails = array(SERVER_EMAIL);
							if ($_SESSION[self::session_prefix.self::session_protect] == self::protect_kit) {
								// if use KIT authtentification send 
								require_once(WB_PATH.'/modules/kit/class.config.php');
								$dbKITcfg = new dbKITcfg();
								$to_emails = $dbKITcfg->getValue(dbKITcfg::cfgKITadmins);	
								if (count($to_emails) < 1) $to_emails = array(SERVER_EMAIL);						
							}
							foreach ($to_emails as $to_email) {
								if (!$wb->mail(SERVER_EMAIL, $to_email, kdl_text_upload_subject, $body)) {
									$this->setError(sprintf(kdl_error_send_mail, SERVER_EMAIL));
									return false;
								}
							}
						}
					}
				}	
				else {
					switch ($_FILES[self::request_file]['error']):
					case UPLOAD_ERR_INI_SIZE:
						$error = sprintf(kdl_error_upload_ini_size, ini_get('upload_max_filesize'));
						break;
					case UPLOAD_ERR_FORM_SIZE:
						$error = kdl_error_upload_form_size;
						break;
					case UPLOAD_ERR_PARTIAL:
						$error = sprintf(kld_error_upload_partial, $_FILES[self::request_file]['name']);
						break;
					default:
						$error = kdl_error_upload_undefined_error;
					endswitch;
					$this->setError($error);
					return false;
				}
			}
		}
		
		if (isset($_REQUEST[self::request_unlink]) && isset($_REQUEST[self::request_unlink_confirm]) && isset($_REQUEST[self::request_unlink_item])) {
			// check unlink of a file or a directory
			if ($_REQUEST[self::request_unlink_confirm] == self::action_unlink_confirmed) {
				// unlink is already confirmed!
				if ($_REQUEST[self::request_unlink] == self::action_unlink_dir) {
					// unlink directory
					if (file_exists($dir.$_REQUEST[self::request_unlink_item])) {
						if ($kdlTools->removeDirectory($dir.$_REQUEST[self::request_unlink_item])) {
							$message .= sprintf(kdl_msg_unlink_dir_success, $_REQUEST[self::request_unlink_item]);
						}
						else {
							$this->setError(sprintf(kdl_error_unlink_dir, $_REQUEST[self::request_unlink_item]));
							return false;
						}
					}
				}
				elseif (file_exists($dir.$_REQUEST[self::request_unlink_item])) {
					// unlink file
					if (!@unlink($dir.$_REQUEST[self::request_unlink_item])) {
						$this->setError(sprintf(kdl_error_unlink_file, $_REQUEST[self::request_unlink_item]));
						return false;
					}
					else {
						$message .= sprintf(kdl_msg_unlink_file_success, $_REQUEST[self::request_unlink_item]);
					}
				}
			}
			else {
				// need confirmation!
				if ($_REQUEST[self::request_unlink] == self::action_unlink_dir) {
					$message .= sprintf(kdl_msg_confirm_unlink_dir,
															$_REQUEST[self::request_unlink_item],
															sprintf('%s?%s=%s&%s=%s&%s=%s%s',
																			$this->page_link,
																			self::request_unlink,
																			self::action_unlink_dir,
																			self::request_unlink_confirm,
																			self::action_unlink_confirmed,
																			self::request_unlink_item,
																			$_REQUEST[self::request_unlink_item],
																			($is_sub_dir) ? sprintf('&%s=%s', self::request_sub_dir, $sub_dir) : ''));
				}
				else {
					$message .= sprintf(kdl_msg_confirm_unlink_file,
															$_REQUEST[self::request_unlink_item],
															sprintf('%s?%s=%s&%s=%s&%s=%s%s',
																			$this->page_link,
																			self::request_unlink,
																			self::action_unlink_file,
																			self::request_unlink_confirm,
																			self::action_unlink_confirmed,
																			self::request_unlink_item,
																			$_REQUEST[self::request_unlink_item],
																			($is_sub_dir) ? sprintf('&%s=%s', self::request_sub_dir, $sub_dir) : ''));
				}
			}	
		}
		
		// check for file descriptions
		$this->getFileDescriptions($dir);

		// scan directory
		$complete = scandir($dir);
		$files = array();
		$dirs = array();
		// separate directories from files...
		foreach ($complete as $item) {
			if (is_file($dir.$item)) {
				$files[] = $item;
			}
			elseif ($_SESSION[self::session_prefix.self::param_recursive]) {
				$dirs[] = $item;
			}
		}
		// sort files
		if ($files_sort == self::sort_asc) {
			sort($dirs);
			sort($files);
			$sort_link = sprintf('%s?%s=%s#%s', $this->page_link, self::request_sort, self::sort_desc, self::kdl_anchor);
		}
		else {
			rsort($dirs);
			rsort($files);
			$sort_link = sprintf('%s?%s=%s#%s', $this->page_link, self::request_sort, self::sort_asc, self::kdl_anchor);
		}
		if ($is_sub_dir) $sort_link = sprintf('%s&%s=%s', $sort_link, self::request_sub_dir, $sub_dir);
		
		// display first directories and then files...
		$directory = array_merge($dirs, $files);

		$row = new Dwoo_Template_File($this->template_path.'frontend.dirlist.td.htt');
		$items = '';
		// headline
		$data = array(
			'icon'					=> '',
			'files'					=> sprintf(	'<a href="%s"><img src="%s" width="16" height="16" alt="%s" title="%s" /></a> %s', 
																	$sort_link, 
																	$this->icon_url.'switch.gif', 
																	kdl_header_list_sort, 
																	kdl_header_list_sort, 
																	kdl_header_list_files),
			'size'					=> kdl_header_list_size,
			'date'					=> kdl_header_list_date
		);
		$items .= $parser->get($this->template_path.'frontend.dirlist.th.htt', $data);
		$flipflop = false;
		$no_entries = true;
		foreach ($directory as $item) {
			($flipflop) ? $flipflop = false : $flipflop = true;
			($flipflop) ? $class = 'flip' : $class = 'flop';			
			if ($item == '.') continue;
			// bei Fehler Datei ueberspringen
			if (!file_exists($dir.$item)) continue;
			
			$unlink_type = 'none';
			
			if ($item == '..') {
				// Link auf das uebergeordnete Verzeichnis
				if (empty($sub_dir)) continue;
				$size = '';
				$date = '';
				$up = $this->page_link;
				if (strpos($sub_dir, '/') > 0) {
					$up = substr($sub_dir, 0, strrpos($sub_dir, '/'));
					$up = sprintf('%s?%s=%s', $this->page_link, self::request_sub_dir, $up);					
				}
				$file = sprintf('<a href="%s">%s</a>', 
												$up, 
												sprintf('<img src="%s" width="32" height="16" alt="%s" />',
																$this->icon_url.'up.gif',
																kdl_alt_folder));
			}
			elseif (is_file($dir.$item)) {
				$unlink_type = 'file';
				// Datei...
				$file_info = pathinfo($dir.$item);
				// don't show any system files...
				if (empty($file_info['filename'])) continue;
				// check for general excluded files...
				if (in_array(strtolower($item), $this->general_excluded_files)) continue;
				// check for general excluded extensions...
				if (in_array(strtolower($file_info['extension']), $this->general_excluded_extensions)) continue;
				if (!empty($_SESSION[self::session_prefix.self::param_include])) {
					// show only files with included extensions
					$include = explode(',', $_SESSION[self::session_prefix.self::param_include]);
					if (!in_array(strtolower($file_info['extension']), $include)) continue;
				}
				if (!empty($_SESSION[self::session_prefix.self::param_exclude])) {
					// don't show files with excluded extensions
					$exclude = explode(',', $_SESSION[self::session_prefix.self::param_exclude]);
					if (in_array(strtolower($file_info['extension']), $exclude)) continue;
				}
				// ok - this file should be shown...
				if ($redirect) {
					// protected file don't allow direct access...
					if (file_exists(WB_PATH.'/kdl.php')) {
						// link file exists in Root...
						$file_link = WB_URL.'/kdl.php';
					}
					else {
						$file_link = WB_URL.'/modules/'.basename(dirname(__FILE__)).'/kdl.php';
					}
					$where = array(
						dbKITdirList::field_path => $dir.$item,
						dbKITdirList::field_user => $_SESSION[self::session_prefix.self::session_user]
					);
					$link = array();
					if (!$dbLink->sqlSelectRecord($where, $link)) {
						$this->setError($dbLink->getError());
						return false;
					}
					if (count($link) < 1) {
						// create a new entry
						$data = array(
							dbKITdirList::field_file 		=> $item,
							dbKITdirList::field_path		=> $dir.$item,
							dbKITdirList::field_date		=> date('Y-m-d H:i:s'),
							dbKITdirList::field_user		=> $_SESSION[self::session_prefix.self::session_user],
							dbKITdirList::field_count		=> 0 
						);
						$id = -1;
						if (!$dbLink->sqlInsertRecord($data, $id)) {
							$this->setError($dbLink->getError());
							return false;
						}
					}
					else {
						$id = $link[0][dbKITdirList::field_id];
					}
					$file_link = sprintf('%s?id=%d', $file_link, $id);
				}
				else {
					// public directory, link directly to all files
					$file_link = $dir_url.$item;
				}
				$size = $kdlTools->bytes2Str(filesize($dir.$item));
				$date = date(kdl_cfg_date_time, filemtime($dir.$item));
				$desc = (isset($this->descriptions[strtolower($item)])) ? sprintf(' title="%s"', $this->descriptions[strtolower($item)]) : '';
				$file = sprintf('<a href="%s" %starget="_blank">%s %s</a>', 
												$file_link, 
												$desc,
												sprintf('<img src="%s" width="16" height="16" alt="%s" />',
																$this->icon_url.$mimeType->getIconByType($dir.$item),
																$mimeType->getMimeType($item)),
												$item);
			}
			else {
				// Verzeichnis...
				$unlink_type = 'dir';
				if (($redirect == false) && ($item == self::protection_folder)) continue;
				$size = '';
				$date = '';
				$file = sprintf('<a href="%s?%s=%s">%s %s</a>', 
												$this->page_link, 
												self::request_sub_dir, 
												($is_sub_dir) ? $sub_dir.'/'.$item : $item, 
												$icon = sprintf('<img src="%s" width="16" height="16" alt="%s" />',
																				$this->icon_url.'folder.gif',
																				kdl_alt_folder),
												$item);
			}	
			
			if ($this->params[self::param_unlink] && ($unlink_type !== 'none')) {
				// user is allowed to delete files...
				$params = sprintf('%s%s=%s&%s=%s&%s=%s',
													($is_sub_dir) ? sprintf('%s=%s&', self::request_sub_dir, $sub_dir) : '',
													self::request_unlink,
													($unlink_type == 'dir') ? self::action_unlink_dir : self::action_unlink_file,
													self::request_unlink_confirm,
													self::action_unlink_pending,
													self::request_unlink_item,
													$item);  
				$unlink = sprintf('<a href="%s?%s"><img src="%s" width="16" height="16" alt="%s" /></a>',
													$this->page_link,
													$params, 
													$this->icon_url.'unlink.gif',
													kdl_alt_unlink);	
			}
			else {
				$unlink = '';
			}
			
			$data = array(
				'class'		=> $class,
				'file'		=> $file,
				'option'	=> $unlink,
				'size'		=> $size,
				'date'		=> $date
			);
			$items .= $parser->get($row, $data);
			$no_entries = false;
		}	
		
		if ($no_entries == true) {
			// no files...
			$data = array(
				'class'		=> 'flop',
				'file'		=> kdl_msg_no_files,
				'size'		=> '',
				'date'		=> ''
			);	
			$items .= $parser->get($row, $data);
		}
		
		// uploads allowed?
		if ($this->params[self::param_upload]) {
			if ((bool) ini_get('file_uploads') !== true) {
				$this->setError(kdl_error_file_uploads_forbidden);
				return false;
			}
			$post_max_size = $kdlTools->convertBytes(ini_get('post_max_size'));
			$upload_max_filesize = $kdlTools->convertBytes(ini_get('upload_max_filesize'));
			$max_size = ($post_max_size >= $upload_max_filesize) ? $upload_max_filesize : $post_max_size;
			$max_size = $kdlTools->bytes2Str($max_size);
			
			$data = array(
				'url'						=> ($is_sub_dir) ? sprintf('%s?%s=%s', $this->page_link, self::request_sub_dir, $sub_dir) : $this->page_link,
				'action_name'		=> self::request_action,
				'action_value'	=> self::action_upload,
				'label_upload'	=> sprintf(kdl_label_upload, $max_size),
				'file'					=> self::request_file,
				'btn_ok'				=> kdl_btn_upload_file
			);	
			$upload = $parser->get($this->template_path.'frontend.upload.htt', $data);
		}
		else {
			$upload = '';
		}
		
		// create directories allowed?
		if ($this->params[self::param_mkdir]) {
			$data = array(
				'url'						=> ($is_sub_dir) ? sprintf('%s?%s=%s', $this->page_link, self::request_sub_dir, $sub_dir) : $this->page_link,
				'label_mkdir'		=> kdl_label_mkdir,
				'mkdir_name'		=> self::request_mkdir,
				'btn_mkdir'			=> kdl_btn_mkdir
			);
			$upload .= $parser->get($this->template_path.'frontend.mkdir.htt', $data);
		}
		
		$data = array(
			'message' => (!empty($message)) ? sprintf('<div class="kdl_message">%s</div>', $message) : '',
			'header'	=> '',
			'items'		=> $items,
			'footer'	=> $upload
		);
		return $parser->get($this->template_path.'frontend.dirlist.htt', $data);
	} // showDirectory()
	
	
	private function createProtection() {
		$data = sprintf("# .htaccess generated by kitDirList\nAuthUserFile %s\nAuthGroupFile /dev/null".
										"\nAuthName \"KIT - Protected Media Directory\"\nAuthType Basic\n<Limit GET>\n".
										"require valid-user\n</Limit>",$this->protected_path.'.htpasswd'); 
		if (false === file_put_contents($this->protected_path.'.htaccess', $data)) {
			$this->setError(kdl_error_writing_htaccess);
			return false;
		}
		$data = sprintf("# .htpasswd generated by kitDirList\nkit_protector:%s", crypt($this->generatePassword()));
		if (false === file_put_contents($this->protected_path.'.htpasswd', $data)) {
			$this->setError(kdl_error_writing_htpasswd);
			return false;
		}		
		return true;
	} // createProtection()

} // class kitDirList
?>