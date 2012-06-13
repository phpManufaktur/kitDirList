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
 * IMPORTANT NOTE:
 * 
 * If you are editing this file or creating a new language file
 * you must ensure that you SAVE THIS FILE UTF-8 ENCODED.
 * Otherwise all special chars will be destroyed and displayed improper!
 * It is NOT NECESSARY to mask special chars as HTML entities!
 * 
 * German language file (Original Source) by Ralf Hertsch
 *   
**/

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

if ('á' != "\xc3\xa1") {
	// important: language files must be saved as UTF-8 (without BOM)
	trigger_error('The language file <b>'.basename(__FILE__).'</b> is damaged, it must be saved <b>UTF-8</b> encoded!', E_USER_ERROR);
}

define('kdl_alt_folder',										'Directory');
define('kdl_alt_unlink',										'Delete');

define('kdl_btn_account',										'Account');
define('kdl_btn_logout',										'Logout');
define('kdl_btn_mkdir',											'Create');
define('kdl_btn_upload_file',								'Start upload');

define('kdl_cfg_date_time',									'd.m.Y - H:i');

define('kdl_content_login_wb',							'<p>To be able to access the files in that directory you have to login first <a href="%s">with username and password</a>.</p><p>After registration you will automatically be directed here.</p>');
define('kdl_content_login_kit',							'<p>To be able to access the files in that directory you have to login first <a href="%s">with username and password</a>.</p><p>After registration you will automatically be directed here.</p>');

define('kdl_error_contacts_access',					'<p>Direct access to the automatic generated client directories is not allowed! Please use the parameter <b>kit_auto</b> or <b>wb_auto</b> to access!</p>');
define('kdl_error_create_dir',							'<p>Directory <b>%s</b>could not be created!</p>');
define('kdl_error_dir_not_exists',					'<p>Directory <b>%s</b> does not exist!</p>');
define('kdl_error_file_not_found',					'<p>File <b>%s</b> was not found!</p>');
define('kdl_error_file_type_forbidden',			'<p>The uploaded file <b>%s</b> is rejected, because the filename or filetype is member of the blacklist!</p>');
define('kdl_error_file_uploads_forbidden',	'<p>File uploads are forbidden at this server, please check the <b>php.ini</b> entry <b>file_uploads</b>!</p>');
define('kdl_error_kdl_id_not_available',		'<p>File with <b>ID %05d</b> is not available!</p>');
define('kdl_error_kit_dlg_invalid',					'<p>The identifier for the dialog <b>%s</b> could not be determined!</p>');
define('kdl_error_kit_id_missing',					'<p>The KeepInTouch (KIT) record with the ID <b>%05d</b> not found!</p>');
define('kdl_error_kit_not_installed',				'<p>KeepInTouch (KIT) is not installed!</p>');
define('kdl_error_kit_param_rejected',			'<p>Since KeepInTouch (KIT) is not installed on this system, parameter <b>%s</b> cannot be used!</p>');
define('kdl_error_kit_register_id_missing',	'<p>For the registration of the KIT <b>ID %05d<b> there is no valid entry. Please contact the system administrator!</p>');
define('kdl_error_missing_kit_category',		'<p>Parameter <b>%s</b> calls categorie(s) <b>%s</b> which could not be found! Check your entries!</p>');
define('kdl_error_missing_wb_group',				'<p>Parameter <b>%s</b> calls group <b>%s</b> which could not be found! Check your entries!</p>');
define('kdl_error_please_update', 					'<p>Bitte aktualisieren Sie <b>%s</b>! Installiert ist die Version <b>%s</b>, benötigt wird Version <b>%s</b> oder höher!</p>');
define('kdl_error_protection_undefined',		'<p>It was not defined how the access to this directory is to be controlled. Define a KeepInTouch (KIT) category or a WebsieBaker user group!</p>');
define('kdl_error_public_dir_but_protect',	'<p>You have specified a publicly accessible MEDIA directory and by the same token like to have access control via a KeepInTouch (KIT) category respectively via a WebsiteBaker user group - that is not feasible. In order to enable access control the directory has to be located within <b>/media/kit_protect</b>!</p>');
define('kdl_error_send_mail',								'<p>Error sending the status email to %s!</p>');
define('kdl_error_unknown_param',						'<p>Parameter <b>%s</b> is not defined. Program execution stops.</p>');
define('kdl_error_unknown_param_key',				'<p>Parameter <b>%s</b> is not defined, please check the parameters passed!</p>');
define('kdl_error_unlink_dir',							'<p>Can not delete the directory <b>%s</b>!</p>');
define('kdl_error_unlink_file',							'<p>Can not delete the file <b>%s</b>!</p>');
define('kdl_error_upload_form_size',				'<p>The uploaded file is greater than MAX_FILE_SIZE within the form directive.</p>');
define('kdl_error_upload_ini_size',					'<p>The uplaoded file is greater than the param <b>upload_max_filesize</b> of <b>%s</b> within the <b>php.ini</b></p>');
define('kdl_error_upload_move_file',				'<p>Can not move the file <b>%s</b> to the target directory!</p>');
define('kdl_error_upload_partial',					'<p>The file <b>%s</b> was only partial uploaded!</p>');
define('kdl_error_upload_undefined_error',	'<p>A not described error occured while file uploading!</p>');
define('kdl_error_username',								'<p>The username is unknown!</p>');
define('kdl_error_wb_groups',								'<p>Fatal error: can not read the WebsiteBaker groups!</p>');
define('kdl_error_wb_groups_undefined',			'<p>Fatal error: WebsiteBaker groups not available!</p>');
define('kdl_error_wb_login_not_enabled',		'<p>Parameter <b>%s</b> cannot be used because login is turned off.</p>');
define('kdl_error_writing_htaccess',				'<p>Die .htaccess file could not be written!</p>');
define('kdl_error_writing_htpasswd',				'<p>Die .htpasswd file could not be written!</p>');

define('kdl_header_access_denied',					'access denied');
define('kdl_header_error',									'kitDirList - error message');
define('kdl_header_list_date',							'date');
define('kdl_header_list_files',							'files');
define('kdl_header_list_size',							'size');
define('kdl_header_list_sort',							'invert order');
define('kdl_header_login',									'login necessary');

define('kdl_label_upload',									'File (max. %s)');
define('kdl_label_mkdir',										'Directory');

define('kdl_msg_access_denied',							'<p>You do not have permission to access this data </ p>.');
define('kdl_msg_admin_mode',								'<p style="font-style:italic;color:maroon;text-align:center;">Attention! <b>You are logged in as admin</b> and have full access to all user data!</p>');
define('kdl_msg_confirm_unlink_dir',				'<p>Should the directory <b>%s</b> and his whole content really deleted? <a href="%s">YES, delete directory!</a></p>');
define('kdl_msg_confirm_unlink_file',				'<p>Should the file <b>%s</b> really deleted? <a href="%s">YES, delete file!</a></p>');
define('kdl_msg_mkdir_success',							'<p>The directory <b>%s</b> was created.</p>');
define('kdl_msg_no_files',									'<div style="width:99%;text-align:center;padding:10px 0;">- no files -</div>');
define('kdl_msg_unlink_dir_success',				'<p>The directory <b>%s</b> was deleted.</p>');
define('kdl_msg_unlink_file_success',				'<p>The file <b>%s</b> was deleted.</p>');
define('kdl_msg_upload_success',						'<p>The file <b>%s</b> was successfully uploaded.</p>');

define('kdl_text_anonymous',								'- anonym -');
define('kdl_text_upload_subject',						'kitDirList - file uploaded');

?>