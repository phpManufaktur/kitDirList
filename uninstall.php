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

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.link.php');

$error = '';

$dbKITdirList = new dbKITdirList();
if ($dbKITdirList->sqlTableExists()) {
	if (!$dbKITdirList->sqlDeleteTable()) {
		$error .= sprintf('[UNINSTALL %s] %s', $dbKITdirList->getTableName(), $dbKITdirList->getError());		
	}
}

// Prompt Errors
if (!empty($error)) {
	global $admin;
	$admin->print_error($error);
}

?>