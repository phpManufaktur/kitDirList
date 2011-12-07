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

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.droplets.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.link.php');

$error = '';

// remove Droplets
$dbDroplets = new dbDroplets();
$where = array(dbDroplets::field_name => 'kit_dirlist');
if (!$dbDroplets->sqlDeleteRecord($where)) {
	$message = sprintf('[UPGRADE] Error uninstalling Droplet: %s', $dbDroplets->getError());
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

if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/frontend.css')) {
	// unlink this old CSS file
	@unlink(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/frontend.css');
}

$dbKITdirList = new dbKITdirList();
if (!$dbKITdirList->sqlFieldExists(dbKITdirList::field_reference)) {
    // add the additional field for references
    if (!$dbKITdirList->sqlAlterTableAddField(dbKITdirList::field_reference, "VARCHAR(255) NOT NULL DEFAULT ''", dbKITdirList::field_id)) {
        $error .= sprintf('[UPGRADE] Error: %s', $dbKITdirList->getError());
    }
    if (!$dbKITdirList->sqlAlterTableAddField(dbKITdirList::field_file_orgin, "VARCHAR(255) NOT NULL DEFAULT ''", dbKITdirList::field_id)) {
        $error .= sprintf('[UPGRADE] Error: %s', $dbKITdirList->getError());
    }
}

// Prompt Errors
if (!empty($error)) {
	global $admin;
	$admin->print_error($error);
}

?>