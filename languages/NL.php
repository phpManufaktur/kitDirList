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

define('kdl_alt_folder',										'Map');
define('kdl_alt_unlink',										'Verwijderen');

define('kdl_btn_account',										'Account');
define('kdl_btn_logout',										'Logout');
define('kdl_btn_mkdir',											'Map maken');
define('kdl_btn_upload_file',								'Start upload');

define('kdl_cfg_date_time',									'd.m.Y - H:i');

define('kdl_content_login_wb',							'<p>Voor toegang tot de files in deze map moet je eerst inloggen <a href="%s">met username en wachtwoord</a>.</p><p>Na registratie zul je automatisch naar deze map worden geleid.</p>');
define('kdl_content_login_kit',							'<p>Voor toegang tot de files in deze map moet je eerst inloggen  <a href="%s">met username en wachtwoord</a>.</p><p>Na registratie zul je automatisch naar deze map worden geleid.</p>');

define('kdl_error_contacts_access',					'<p>Directe toegang tot de automatisch gegenereerde client directories is niet toegestaan! Gebriuk de parameter <b>kit_auto</b> of <b>wb_auto</b> om toegang te krijgen!</p>');
define('kdl_error_create_dir',							'<p>Map <b>%s</b>kan niet worden gemaakt!</p>');
define('kdl_error_dir_not_exists',					'<p>Map <b>%s</b> bestaat niet!</p>');
define('kdl_error_file_not_found',					'<p>File <b>%s</b> is niet gevonden!</p>');
define('kdl_error_file_type_forbidden',			'<p>De geuploade file <b>%s</b> is geweigerd, omdat de filenaam of het filetype op de blacklist vermeld staat!</p>');
define('kdl_error_file_uploads_forbidden',	'<p>File uploads zijn niet toegestaan op deze server, controleer in <b>php.ini</b> de waarde voor  <b>file_uploads</b>!</p>');
define('kdl_error_kdl_id_not_available',		'<p>File met <b>ID %05d</b> is niet beschikbaar!</p>');
define('kdl_error_kit_dlg_invalid',					'<p>De identifier voor de dialoog <b>%s</b> kon niet worden bepaald!</p>');
define('kdl_error_kit_id_missing',					'<p>Het KeepInTouch (KIT) record met het ID <b>%05d</b> is niet gevonden!</p>');
define('kdl_error_kit_not_installed',				'<p>KeepInTouch (KIT) is niet geinstalleerd!</p>');
define('kdl_error_kit_param_rejected',			'<p>Omdat KeepInTouch (KIT) niet geinstalleerd is op dit systeem, kan de parameter <b>%s</b> niet worden gebruikt!</p>');
define('kdl_error_kit_register_id_missing',	'<p>Voor de registratie van het KIT <b>ID %05d<b> is dit geen valide waarde. Neem aub contact op met de  systeem administrator!</p>');
define('kdl_error_missing_kit_category',		'<p>Parameter <b>%s</b> vraagt categorie(en) <b>%s</b> op die niet konden worden gevonden! Controleer uw ingevulde waarden!</p>');
define('kdl_error_missing_wb_group',				'<p>Parameter <b>%s</b> vraagt groep <b>%s</b> op die niet kon worden gevonden! Controleer uw ingevulde waarden!</p>');
define('kdl_error_please_update', 					'<p>Bitte aktualisieren Sie <b>%s</b>! Installiert ist die Version <b>%s</b>, benötigt wird Version <b>%s</b> oder höher!</p>');
define('kdl_error_protection_undefined',		'<p>Het is niet bepaald hoe de toegang tot deze map wordt bepaald. Definieer een KeepInTouch (KIT) categorie of een WebsiteBaker gebruikers groep!</p>');
define('kdl_error_public_dir_but_protect',	'<p>Je hebt een publiek toegankelijke MEDIA map gedefinieerd en met dezelfde gegevens wil je toegangscontrole hebben via een KeepInTouch (KIT) categorie respectievelijk via een WebsiteBaker gebruikers group - dat is niet mogelijk. Om toegangscontrole te hebben moet de map geplaatst zijn in <b>/media/kit_protect</b>!</p>');
define('kdl_error_send_mail',								'<p>Fout bij het verzenden van de status email naar %s!</p>');
define('kdl_error_unknown_param',						'<p>Parameter <b>%s</b> is niet gedefinieerd. Programma uitvoer stopt.</p>');
define('kdl_error_unknown_param_key',				'<p>Parameter <b>%s</b> is niet gedefinieerd, controleer aub de parameters!</p>');
define('kdl_error_unlink_dir',							'<p>De directory <b>%s</b>! kan niet verwijderd worden!</p>');
define('kdl_error_unlink_file',							'<p>De file <b>%s</b>! kan niet verwijderd worden!</p>');
define('kdl_error_upload_form_size',				'<p>De geuploade file is groter dan MAX_FILE_SIZE in het formulier toestaat.</p>');
define('kdl_error_upload_ini_size',					'<p>De geuploade file is groter dan de param <b>upload_max_filesize</b> van <b>%s</b> in de configuratiefile <b>php.ini</b></p>');
define('kdl_error_upload_move_file',				'<p>De file <b>%s</b> kan niet naar de doelmap worden verplaatst!</p>');
define('kdl_error_upload_partial',					'<p>De file <b>%s</b> was slechts gedeeltelijk geuploaded!</p>');
define('kdl_error_upload_undefined_error',	'<p>Een niet beschreven fout is opgetreden bij het uploaden van de file!</p>');
define('kdl_error_username',								'<p>De gebruikersnaam is onbekend!</p>');
define('kdl_error_wb_groups',								'<p>Fatal error: de  WebsiteBaker groepen kunnen niet gelezen worden!</p>');
define('kdl_error_wb_groups_undefined',			'<p>Fatal error: WebsiteBaker groepen zijn niet beschikbaar!</p>');
define('kdl_error_wb_login_not_enabled',		'<p>Parameter <b>%s</b> kan niet worden gebruikt omdat de login is uitgeschakeld.</p>');
define('kdl_error_writing_htaccess',				'<p>De .htaccess file kan niet worden geschreven!</p>');
define('kdl_error_writing_htpasswd',				'<p>De .htpasswd file kan niet worden geschreven!</p>');

define('kdl_header_access_denied',					'toegang geweigerd');
define('kdl_header_error',									'kitDirList - error message');
define('kdl_header_list_date',							'datum');
define('kdl_header_list_files',							'files');
define('kdl_header_list_size',							'size');
define('kdl_header_list_sort',							'keer volgorde om');
define('kdl_header_login',									'inloggen is noodzakelijk');

define('kdl_label_upload',									'File (max. %s)');
define('kdl_label_mkdir',										'Map');

define('kdl_msg_access_denied',							'<p>U hebt geen toestemming voor toegang tot deze data </ p>.');
define('kdl_msg_admin_mode',								'<p style="font-style:italic;color:maroon;text-align:center;">Opgelet! <b>U bent ingelogd als admin </b> en hebt volledige toegang tot alle gebruikersdata!</p>');
define('kdl_msg_confirm_unlink_dir',				'<p>Moet de map <b>%s</b> en alle inhoud werkelijk verwijderd worden? <a href="%s">JA, verwijder map!</a></p>');
define('kdl_msg_confirm_unlink_file',				'<p>Moet de file <b>%s</b> werkelijk verwijderd worden? <a href="%s">JA, verwijder de file!</a></p>');
define('kdl_msg_mkdir_success',							'<p>De map <b>%s</b> is gemaakt.</p>');
define('kdl_msg_no_files',									'<div style="width:99%;text-align:center;padding:10px 0;">- geen files -</div>');
define('kdl_msg_unlink_dir_success',				'<p>De map <b>%s</b> is verwijderd.</p>');
define('kdl_msg_unlink_file_success',				'<p>De file <b>%s</b> is verwijderd.</p>');
define('kdl_msg_upload_success',						'<p>De file <b>%s</b> is met succes geupload.</p>');

define('kdl_text_anonymous',								'- anoniem -');
define('kdl_text_upload_subject',						'kitDirList - file geupload');
?>