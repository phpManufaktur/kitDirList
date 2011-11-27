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

$module_directory     = 'kit_dirlist';
$module_name          = 'kitDirList';
$module_function      = 'snippet';
$module_version       = '0.26';
$module_status        = 'Stable';
$module_platform      = '2.8'; 
$module_author        = 'Ralf Hertsch, Berlin (Germany)';
$module_license       = 'GNU General Public License';
$module_description   = 'Show public or protected directory list';
$module_home          = 'http://phpmanufaktur.de/kit_dirlist';
$module_guid          = 'A9628771-6557-48CB-81B7-660F3E3D7361';

?>
