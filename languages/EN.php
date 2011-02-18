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

define('kdl_alt_folder',										'Directory');

define('kdl_btn_logout',										'Logout');

define('kdl_cfg_date_time',									'd.m.Y - H:i');

define('kdl_content_login_wb',							'<p>To be able to access the files in that directory you have to login first <a href="%s">with username and password</a>.</p><p>After registration you will automatically be directed here.</p>');
define('kdl_content_login_kit',							'<p>To be able to access the files in that directory you have to login first <a href="%s">with username and password</a>.</p><p>After registration you will automatically be directed here.</p>');

define('kdl_error_create_dir',							'<p>Directory <b>%s</b>could not be created!</p>');
define('kdl_error_dir_not_exists',					'<p>Directory <b>%s</b> does not exist!</p>');
define('kdl_error_file_not_found',					'<p>File <b>%s</b> was not found!</p>');
define('kdl_error_kdl_id_not_available',		'<p>File with <b>ID %05d</b> is not available!</p>');
define('kdl_error_kit_dlg_invalid',					'<p>The identifier for the dialog <b>%s</b> could not be determined!</p>');
define('kdl_error_kit_id_missing',					'<p>The KeepInTouch (KIT) record with the ID <b>%05d</b> not found!</p>');
define('kdl_error_kit_not_installed',				'<p>KeepInTouch (KIT) is not installed!</p>');
define('kdl_error_kit_param_rejected',			'<p>Since KeepInTouch (KIT) is not installed on this system, parameter <b>%s</b> cannot be used!</p>');
define('kdl_error_kit_register_id_missing',	'<p>For the registration of the KIT <b>ID %05d<b> there is no valid entry. Please contact the system administrator!</p>');
define('kdl_error_missing_kit_category',		'<p>Parameter <b>%s</b> calls categorie(s) <b>%s</b> which could not be found! Check your entries!</p>');
define('kdl_error_missing_wb_group',				'<p>Parameter <b>%s</b> calls group <b>%s</b> which could not be found! Check your entries!</p>');
define('kdl_error_protection_undefined',		'<p>It was not defined how the access to this directory is to be controlled. Define a KeepInTouch (KIT) category or a WebsieBaker user group!</p>');
define('kdl_error_public_dir_but_protect',	'<p>You have specified a publicly accessible MEDIA directory and by the same token like to have access control via a KeepInTouch (KIT) category respectively via a WebsiteBaker user group - that is not feasible. In order to enable access control the directory has to be located within <b>/media/kit_protect</b>!</p>');
define('kdl_error_unknown_param',						'<p>Parameter <b>%s</b> is not defined. Program execution stops.</p>');
define('kdl_error_unknown_param_key',				'<p>Parameter <b>%s</b> is not defined, please check the parameters passed!</p>');
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

define('kdl_msg_access_denied',							'<p>You do not have permission to access this data </ p>.');

?>