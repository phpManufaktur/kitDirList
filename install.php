<?php

/**
 * kitDirList
 * 
 * @author Ralf Hertsch (ralf.hertsch@phpmanufaktur.de)
 * @link http://phpmanufaktur.de
 * @copyright 2011
 * @license GNU GPL (http://www.gnu.org/licenses/gpl.html)
 * @version $Id$
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	if (defined('LEPTON_VERSION')) include(WB_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.link.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.droplets.php');

$error = '';

$dbKITdirList = new dbKITdirList();
if (!$dbKITdirList->sqlTableExists()) {
	if (!$dbKITdirList->sqlCreateTable()) {
		$error .= sprintf('<p>[INSTALL %s] %s</p>', $dbKITdirList->getTableName(), $dbKITdirList->getError());
	}
}

// Install Droplets
$droplets = new checkDroplets();
if ($droplets->insertDropletsIntoTable()) {
  $message = 'The Droplets for kitDirList where successfully installed! Please look at the Help for further informations.';
}
else {
  $message = 'The installation of the Droplets for kitDirList failed. Error: '. $droplets->getError();
}
if ($message != "") {
  echo '<script language="javascript">alert ("'.$message.'");</script>';
}

// Prompt Errors
if (!empty($error)) {
	global $admin;
	$admin->print_error($error);
}

?>