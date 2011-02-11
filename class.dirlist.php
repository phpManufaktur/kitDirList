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

class kitDirList {
	
	const request_action		= 'act';
	
	const action_start			= 'go';
	const action_logout			= 'out';
	
	const param_media				= 'media';
	const param_recursive		= 'recursive';
	const param_include			= 'include';
	const param_exclude			= 'exclude';
	const param_redirect_id	= 'redirect_id';
	const param_kit_intern	= 'kit_intern';
	const param_kit_news		= 'kit_news';
	const param_kit_dist		= 'kit_dist';
	const param_wb_group		= 'wb_group';
	const param_copyright		= 'copyright';
	
	// params come from the droplet [[kit_dirlist]]
	private $params = array(
		self::param_media				=> '',
		self::param_recursive		=> 0,
		self::param_include			=> '',
		self::param_exclude			=> '',
		self::param_redirect_id	=> -1,
		self::param_kit_intern	=> '',
		self::param_kit_news		=> '',
		self::param_kit_dist		=> '',
		self::param_wb_group		=> '',
		self::param_copyright		=> 1
	);
	
	const session_prefix		= 'kdl_';
	const session_protect		= 'pct';		// protected access?
	const session_user			= 'usr';		// username
	const session_auth			= 'aut';		// is user authorized?
	const session_wb_grps		= 'grps';   // IDs of WB Groups
	
	const protect_none			= 'nn';
	const protect_undefined	= 'udf';
	const protect_group			= 'grp';
	const protect_kit				= 'kit';
	
	private $message = '';
	private $error = '';
	private $silent = true;
	private $protected_path = '';
	private $protected_url = '';
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
	
	public function __construct($silent=true) {
		global $kdlTools;
		$this->silent = $silent;
		$this->media_path = WB_PATH.MEDIA_DIRECTORY.'/';
		$this->media_url = WB_URL.MEDIA_DIRECTORY.'/';
		$this->protected_path = WB_PATH.MEDIA_DIRECTORY.'/kit_protected/';
		$this->protected_url  = WB_URL.MEDIA_DIRECTORY.'/kit_protected/';
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
		$this->page_link;  	
		$this->icon_url = WB_URL.'/modules/'.basename(dirname(__FILE__)).'/img/16x16/';
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
		// set default values
		foreach ($this->params as $key => $value) {
			switch($key):
			case self::param_media:
				$this->params[$key] = ''; break;
			case self::param_recursive:
				$this->params[$key] = false; break;
			case self::param_redirect_id:
				$this->params[$key] = -1; break;
			case self::param_copyright:
				$this->params[$key] = true; break;
			case self::param_include:
			case self::param_exclude:
			case self::param_kit_intern:
			case self::param_kit_news:
			case self::param_kit_dist:
			case self::param_wb_group:
				$this->params[$key] = '';
				break;
			default:
				$this->params[$key] = 'undefined'; break;
			endswitch;
		}
		// get the new values
		foreach ($params as $key => $value) {
			if (key_exists($key, $this->params)) {
				switch ($key):
				case self::param_media:
						$this->params[$key] = $this->trimSlashes(trim($value)).'/';
					break;
				case self::param_redirect_id:
					$this->params[$key] = (int) $value;
					break;
				case self::param_recursive:
				case self::param_copyright:	
					$this->params[$key] = (bool) $value;
					break;
				case self::param_kit_intern:
				case self::param_kit_news:
				case self::param_kit_dist:
					if (!$this->kit_installed) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kdl_error_kit_param_rejected, $key)));
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
						if ($key == self::param_exclude || $key == self::param_include) {
							if (strpos($val, '.') === false) $val = '.'.$val; 
						}
						$para[] = $val;
					}
					$this->params[$key] = implode(',', $para);
					break;	
				endswitch;
			}	
			else {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kdl_error_unknown_param_key, $key)));
				return false;
			}		
		}
		return true;
	} // setParams()
	
	/**
	 * Art des Schutzes pruefen und festlegen
	 */
	private function checkProtection() {
		if (strpos($this->base_path, $this->protected_path) == 0) {
			// base path reside within the protected path
			if (!empty($_SESSION[self::session_prefix.self::param_kit_news]) ||
					!empty($_SESSION[self::session_prefix.self::param_kit_dist]) ||
					!empty($_SESSION[self::session_prefix.self::param_kit_intern])) {
				// ok - pruefen ob der angeforderte Schutz mit KIT moeglich ist.
				if (!$this->kit_installed) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, kdl_error_kit_not_installed));
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
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactArray->getError()));
						return false;
					}
					if (count($cfgArray) < 1) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kdl_error_missing_kit_category, $category, $_SESSION[self::session_prefix.$category])));
						return false;
					}
				}
				$_SESSION[self::session_prefix.self::session_protect] = self::protect_kit;		
			}
			elseif (!empty($_SESSION[self::session_prefix.self::param_wb_group])) {
				if (!$this->wb_login) {
					// WB Anmeldung ist ausgeschaltet
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kdl_error_wb_login_not_enabled, self::param_wb_group)));
					return false;
				}
				global $database;
				$groups = explode(',', $_SESSION[self::session_prefix.self::param_wb_group]);
				$wb_groups_id = array();
				foreach ($groups as $group) {
					$SQL = sprintf(	"SELECT group_id FROM %sgroups WHERE name='%s'", TABLE_PREFIX, $group);
					if (false ===($result = $database->query($SQL))) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
						return false;
					}
					$data = $result->fetchRow(MYSQL_ASSOC);
					if (!isset($data['group_id'])) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kdl_error_missing_wb_group, self::param_wb_group, $group)));
						return false;
					}
					$wb_groups_id[] = $data['group_id'];
				}			
				$_SESSION[self::session_prefix.self::session_protect] = self::protect_group;
				$_SESSION[self::session_prefix.self::session_wb_grps] = implode(',', $wb_groups_id);
			}
			else {
				$_SESSION[self::session_prefix.self::session_protect] = self::protect_none;
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, kdl_error_protection_undefined));
				return false;
			}
		}
		else {
			$_SESSION[self::session_prefix.self::session_protect] = self::protect_none;
			if (!empty($_SESSION[self::session_prefix.self::param_kit_news]) ||
					!empty($_SESSION[self::session_prefix.self::param_kit_dist]) ||
					!empty($_SESSION[self::session_prefix.self::param_kit_intern]) ||
					!empty($_SESSION[self::session_prefix.self::param_wb_group])) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, kdl_error_public_dir_but_protect));
				return false;		
			}			
		}
		return true;
	} // checkSession()
	
	private function checkAuthentication() {
		global $kdlTools;
		global $parser;
		global $wb;
		
		if ($_SESSION[self::session_prefix.self::session_protect] == self::protect_none) {
			// no protection needed
			$_SESSION[self::session_prefix.self::session_auth] = 1;
			$this->is_authenticated = true;
			return true;
		}
		elseif ($_SESSION[self::session_prefix.self::session_protect] == self::protect_kit) {
			// Protection by KeepInTouch login
			if (!$this->kit_installed) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, kdl_error_kit_not_installed));
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
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
					return $this->getError();
				}				
				if (count($register) < 1) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kdl_error_kit_register_id_missing, $_SESSION['kit_aid'])));
					return $this->getError();
				}
				$contact = array();
				if (!$dbContact->getContactByID($register[0][dbKITregister::field_contact_id], $contact)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
					return $this->getError();
				}
				if (count($contact) < 1) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kdl_error_kit_id_missing, $register[0][dbKITregister::field_contact_id])));
					return $this->getError();
				}
				// Kategorien des Users
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
				// Benutzer freigeben
				$_SESSION[self::session_prefix.self::session_auth] = 1;
				$this->is_authenticated = true;
				return true;
			}
			else {
				// KIT User ist nicht angemeldet
				$url = '';				
				$kdlTools->getPageLinkByPageID(PAGE_ID, $url);
				$_SESSION['KIT_REDIRECT'] = $url;
				$data = array(
					'header'		=> kdl_header_login,
					'content'		=> sprintf(kdl_content_login_kit, sprintf('%s/modules/kit/kit.php?act=login', WB_URL))
				);
				return $parser->get($this->template_path.'frontend.login.kit.htt', $data);
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
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, kdl_error_wb_groups_undefined));
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
				$_SESSION[self::session_prefix.self::session_auth] = 1;
				$this->is_authenticated = true;
				return true;
			}
		}
		else {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kdl_error_unknown_param, $_SESSION[self::session_prefix.self::session_protect])));
			return $this->getError();
		}
	} // checkAuthentication()
	
	/**
	 * Trims a path by removing leading and trailing slashes
	 * @param STR $path
	 * @return STR $path
	 */
	private function trimSlashes($path) {
		while (strpos($path, '/') == 0) $path = substr($path, 1);
		while (strrpos($path, '/') == strlen($path)-1) $path = substr($path, 0, strlen($path)-1);
		return $path; 
	} // trimSlashes()
	
	/**
    * Set $this->error to $error
    * 
    * @param STR $error
    */
  public function setError($error) {
    $this->error = $error;
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
  	if ((!$this->is_authenticated) && (true !== ($login = $this->checkAuthentication()))) return $login;
  	
  	switch ($action):
		case self::action_logout:
			$result = $this->logout();
			break;
		case self::action_start:
		default: 
			$result = $this->DirectoryListing();	
		endswitch;		
		
		return $this->show($result);
	} // action()
	
	/**
	 * ECHO or RETURN the result dialog depending on switch SILENT
	 * @param STR $result
	 */
	public function show($result='- no content -') {
		// check if there was an error...
		if ($this->isError()) $result = sprintf('<div class="kdl_error"><h1>%s</h1>%s</div>', kdl_header_error, $this->getError());
		if ($_SESSION[self::session_prefix.self::param_copyright]) {
			// display copyright informations
			$result = sprintf('%s<div style="margin:0;padding:10px 0;font-size:7pt;text-align:center;color:#808080;background-color:transparent;">'.
												'<b>kitDirList</b> %01.2f - &copy; %d by <a href="mailto:ralf.hertsch@phpmanufaktur.de">Ralf Hertsch</a>, Berlin (Germany)<br />'.
												'Please visit <a href="http://phpmanufaktur.de" target="_blank">phpManufaktur</a> for more informations.</div>', 
												$result, $this->getVersion(), date('Y'));
		}
		if (($_SESSION[self::session_prefix.self::session_protect] == self::protect_group) ||
				($_SESSION[self::session_prefix.self::session_protect] == self::protect_kit)) {
			// display logout link if necessary...
			$result = sprintf('<div class="kdl_body"><div class="kdl_logout"><a href="%s">%s</a></div>%s</div>',
												sprintf('%s?%s=%s', $this->page_link, self::request_action, self::action_logout),
												kdl_btn_logout,
												$result); 		
		}
		else {
			$result = sprintf('<div class="kdl_body">%s</div>', $result);
		}
		if ($this->silent) return $result;
		echo $result;
	} // show()
	
	public function logout() {		
		// unset all session vars...
		unset($_SESSION[self::session_prefix.self::session_user]);
		unset($_SESSION[self::session_prefix.self::session_auth]);
		unset($_SESSION[self::session_prefix.self::session_wb_grps]);
		foreach ($this->params as $param) {
			unset($_SESSION[self::session_prefix.$param]);
		}
		if ($_SESSION[self::session_prefix.self::session_protect] == self::protect_group) {
			// WebsiteBaker Logout
			unset($_SESSION[self::session_prefix.self::session_protect]);
			header("Location: ".LOGOUT_URL);
		}
		elseif ($_SESSION[self::session_prefix.self::session_protect] == self::protect_kit) {
			// KeepInTouch Logout
			unset($_SESSION[self::session_prefix.self::session_protect]);
			require_once(WB_PATH.'/modules/kit/initialize.php');
			require_once(WB_PATH.'/modules/kit/class.dialogs.php');
			$kitCfg = new dbKITcfg();
			$dlg_name = $kitCfg->getValue(dbKITcfg::cfgRegisterDlgACC);
			$dbDlgRegister = new dbKITdialogsRegister();
			$where = array(dbKITdialogsRegister::field_name => $dlg_name);
			$dialogs = array();
			if (!$dbDlgRegister->sqlSelectRecord($where, $dialogs)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbDlgRegister->getError()));
				return $this->getError();
			}
			if (count($dialogs) < 1) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kdl_error_kit_dlg_invalid, $dlg_name)));
				return $this->getError();
			}
			$url = sprintf('%s/modules/kit/kit.php?lg=%s&act=dlg&dlg=%d&acc_act=out', WB_URL, strtolower(LANGUAGE), $dialogs[0][dbKITdialogsRegister::field_id]);
			header("Location: $url");			
		}
		// otherwise only unset the protect session...
		unset($_SESSION[self::session_prefix.self::session_protect]);
	} // logout()
	
	private function checkPaths() {
		// check protected path
		if (!file_exists($this->protected_path)) {
			// create directory
			if (!mkdir($this->protected_path, 0777)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kdl_error_create_dir, $this->protected_path)));
				return false;
			}
			// create directory protection
			if (!$this->createProtection()) return false;
		}
		// check directory protection
		if (!file_exists($this->protected_path.'.htaccess') || !file_exists($this->protected_path.'.htpasswd')) {
			if (!$this->createProtection()) return false;
		}		
		// set base_path
		if (empty($this->base_path)) $this->base_path = $this->media_path.$_SESSION[self::session_prefix.self::param_media];
		// check base path
		if (!file_exists($this->base_path)) {
			// base directory does not exists
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kdl_error_dir_not_exists, $this->base_path)));
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
	
	
	public function directoryListing() {
		global $parser;
		global $kdlTools;
		
		$mimeType = new mimeTypes();
		
		$dir = $this->base_path; 
		$dir_url = str_replace(WB_PATH, WB_URL, $dir);
		
		if (!file_exists($dir))	{
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kdl_error_dir_not_exists, $dir)));
			return false;
		}
		$directory = scandir($dir);
		
		$row = new Dwoo_Template_File($this->template_path.'frontend.dirlist.td.htt');
		$items = '';
		$flipflop = false;
		foreach ($directory as $item) {
			($flipflop) ? $flipflop = false : $flipflop = true;
			($flipflop) ? $class = 'flip' : $class = 'flop';
			
			if ($item == '.' || $item == '..') continue;
			
			// bei Fehler Datei ueberspringen
			if (!file_exists($this->base_path.$item)) continue;
			$icon = sprintf('<img src="%s" width="16" height="16" alt="%s" />',
											$this->icon_url.$mimeType->getIconByType($dir.$item),
											$mimeType->getMimeType($item));	
			$file = sprintf('<a href="%s" target="_blank">%s</a>', $dir_url.$item, $item);
			
			$data = array(
				'class'		=> $class,
				'icon'		=> $icon,
				'file'		=> $file,
				'size'		=> $kdlTools->bytes2Str(filesize($dir.$item)),
				'date'		=> date(kdl_cfg_date_time, filemtime($dir.$item))
			);
			$items .= $parser->get($row, $data);
			//if (is_dir($dir.$item)) $result .= '[x] ';
			//$result .= trim($item)."<br>";
		}	
		
		$data = array(
			'header'	=> '',
			'items'		=> $items
		);
		return $parser->get($this->template_path.'frontend.dirlist.htt', $data);
	} // showDirectory()
	
	
	private function createProtection() {
		$data = sprintf("# .htaccess generated by kitDirList\nAuthUserFile %s\nAuthGroupFile /dev/null".
										"\nAuthName \"KIT - Protected Media Directory\"\nAuthType Basic\n<Limit GET>\n".
										"require valid-user\n</Limit>",$this->protected_path.'.htpasswd'); 
		if (false === file_put_contents($this->protected_path.'.htaccess', $data)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, kdl_error_writing_htaccess));
			return false;
		}
		$data = sprintf("# .htpasswd generated by kitDirList\nkit_protector:%s", crypt($this->generatePassword()));
		if (false === file_put_contents($this->protected_path.'.htpasswd', $data)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, kdl_error_writing_htpasswd));
			return false;
		}		
		return true;
	} // createProtection()

} // class kitDirList
?>