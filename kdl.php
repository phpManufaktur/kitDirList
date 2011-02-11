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

// Include config file
$config_path = '../../config.php';
if (!file_exists($config_path)) {
	die('Missing Configuration File...'); 
}
require_once($config_path);

// include dbConnect
if (!class_exists('dbConnectLE')) require_once(WB_PATH.'/modules/dbconnect_le/include.php');

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.link.php');
// include language file
if(!file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/languages/'.LANGUAGE.'.php')) {
	require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/languages/DE.php'); 
}
else {
	require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/languages/'.LANGUAGE.'.php'); 
}

// first check if user is authenticated...

if (false === (isset($_SESSION['kdl_pct']) && isset($_SESSION['kdl_aut']) && isset($_SESSION['kdl_usr']) && isset($_GET['id']))) {
	// access not allowed
	header($_SERVER['SERVER_PROTOCOL']." 403 Forbidden");
	exit();
}

$id = $_GET['id'];
$user = $_SESSION['kdl_usr'];

$dbDirList = new dbKITdirList();

$where = array(
	dbKITdirList::field_id		=> $id,
	dbKITdirList::field_user	=> $user
);
$dirlist = array();
if (!$dbDirList->sqlSelectRecord($where, $dirlist)) {
	echo $dbDirList->getError();
}
if (count($dirlist) < 1) {
	echo sprintf(kdl_error_kdl_id_not_available, $id);
	exit();
}

$dirlist = $dirlist[0];
if (!file_exists($dirlist[dbKITdirList::field_path])) {
	echo sprintf(kdl_error_file_not_found, $dirlist[dbKITdirList::field_file]);
	exit();
}


// start download
header('Content-type: application/force-download');
header('Content-Transfer-Encoding: Binary');
header('Content-length: '.filesize($dirlist[dbKITdirList::field_path]));
header('Content-disposition: attachment;filename='.$dirlist[dbKITdirList::field_file]);
readfile($dirlist[dbKITdirList::field_path]);
exit(0);				

?>