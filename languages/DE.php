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

define('kdl_alt_folder',										'Verzeichnis');
define('kdl_alt_unlink',										'Löschen');

define('kdl_btn_logout',										'Abmelden');
define('kdl_btn_mkdir',											'Anlegen');
define('kdl_btn_upload_file',								'Übertragung starten');

define('kdl_cfg_date_time',									'd.m.Y - H:i');

define('kdl_content_login_wb',							'<p>Damit Sie auf die Dateien in diesem Verzeichnis zugreifen können, müssen Sie sich zunächst <a href="%s">mit Ihrem Benutzernamen und Ihrem Passwort anmelden</a>.</p><p>Nach der Anmeldung werden Sie automatisch wieder hierher geleitet.</p>');
define('kdl_content_login_kit',							'<p>Damit Sie auf die Dateien in diesem Verzeichnis zugreifen können, müssen Sie sich zunächst <a href="%s">mit Ihrem Benutzernamen und Ihrem Passwort anmelden</a>.</p><p>Nach der Anmeldung werden Sie automatisch wieder hierher geleitet.</p>');

define('kdl_error_contacts_access',					'<p>Der direkte Zugriff auf die automatisch angelegten Kundenverzeichnisse ist nicht gestattet. Nutzen Sie den Parameter <b>kit_auto</b> oder <b>wb_auto</b>!</p>');
define('kdl_error_create_dir',							'<p>Das Verzeichnis <b>%s</b> konnte nicht angelegt werden!</p>');
define('kdl_error_dir_not_exists',					'<p>Das Verzeichnis <b>%s</b> existiert nicht!</p>');
define('kdl_error_file_not_found',					'<p>Die Datei <b>%s</b> wurde nicht gefunden!</p>');
define('kdl_error_file_uploads_forbidden',	'<p>Datenübertragungen sind in der <b>php.ini</b> mit dem Eintrag <b>file_uploads</b> verboten!</p>');
define('kdl_error_kdl_id_not_available',		'<p>Die Datei mit der <b>ID %05d</b> steht nicht zur Verfügung!</p>');
define('kdl_error_kit_dlg_invalid',					'<p>Die Kennung für den Dialog <b>%s</b> konnte nicht ermittelt werden!</p>');
define('kdl_error_kit_id_missing',					'<p>Der KeepInTouch (KIT) Datensatz mit der <b>ID %05d</b> wurde nicht gefunden!</p>');
define('kdl_error_kit_not_installed',				'<p>KeepInTouch (KIT) ist nicht installiert!</p>');
define('kdl_error_kit_param_rejected',			'<p>Da KeepInTouch (KIT) nicht auf diesem System installiert ist, kann der Parameter <b>%s</b> nicht verwendet werden!</p>');
define('kdl_error_kit_register_id_missing',	'<p>Für die KIT Registrierung mit der <b>ID %05d<b> existiert kein gültiger Eintrag. Bitte wenden Sie sich an den Systemadministrator!</p>');
define('kdl_error_missing_kit_category',		'<p>Die mit dem Parameter <b>%s</b> genannte(n) Kategorien <b>%s</b> wurden nicht gefunden! Prüfen Sie Ihre Angaben!</p>');
define('kdl_error_missing_wb_group',				'<p>Die mit dem Parameter <b>%s</b> genannete Gruppe <b>%s</b> wurde nicht gefunden! Prüfen Sie Ihre Angaben!</p>');
define('kdl_error_protection_undefined',		'<p>Es wurde nicht definiert auf welche Weise der Zugriff auf das Verzeichnis kontrolliert werden soll. Legen Sie eine KeepInTouch (KIT) Kategorie oder eine WebsiteBaker Benutzergruppe fest!</p>');
define('kdl_error_public_dir_but_protect',	'<p>Sie haben ein frei zugängliches MEDIA Verzeichnis angegeben und möchten gleichzeitig eine Zugriffskontrolle über eine KeepInTouch (KIT) Kategorie bzw. über eine WebsiteBaker Benutzergruppe - dies ist nicht möglich. Um eine Zugriffskontrolle zu ermöglichen muss sich das Verzeichnis in <b>/media/kit_protect</b> befinden.</p>');
define('kdl_error_send_mail',								'<p>Die Status E-Mail an %s konnte nicht versendet werden!</p>');
define('kdl_error_unknown_param',						'<p>Der Parameter <b>%s</b> ist nicht definiert. Programmausführung gestoppt.</p>');
define('kdl_error_unknown_param_key',				'<p>Der Parameter <b>%s</b> ist nicht definiert, bitte prüfen Sie die übergebenen Parameter!</p>');
define('kdl_error_unlink_dir',							'<p>Das Verzeichnis <b>%s</b> konnte nicht gelöscht werden!</p>');
define('kdl_error_unlink_file',							'<p>Die Datei <b>%s</b> konnte nicht gelöscht werden!</p>');
define('kdl_error_upload_form_size',				'<p>Die hochgeladene Datei überschreitet die in dem HTML Formular mittels der Anweisung MAX_FILE_SIZE angegebene maximale Dateigröße.</p>');
define('kdl_error_upload_ini_size',					'<p>Die hochgeladene Datei überschreitet die in der Anweisung upload_max_filesize in php.ini festgelegte Größe von %s</p>');
define('kdl_error_upload_move_file',				'<p>Die Datei <b>%s</b> konnte nicht in das Zielverzeichnis verschoben werden!</p>');
define('kdl_error_upload_partial',					'<p>Die Datei <b>%s</b> wurde nur teilweise hochgeladen.</p>');
define('kdl_error_upload_undefined_error',	'<p>Während der Datenübertragung ist ein nicht näher beschriebener Fehler aufgetreteten.</p>');
define('kdl_error_username',								'<p>Der Benutzername konnte nicht ermittelt werden.</p>');
define('kdl_error_wb_groups',								'<p>Fataler Fehler: Die WebsiteBaker Gruppen konnten nicht ausgelesen werden!</p>');
define('kdl_error_wb_groups_undefined',			'<p>Fataler Fehler: WebsiteBaker Gruppen sind nicht gesetzt!</p>');
define('kdl_error_wb_login_not_enabled',		'<p>Der Parameter <b>%s</b> kann nicht verwendet werden, da die Anmeldung ausgeschaltet ist.</p>');
define('kdl_error_writing_htaccess',				'<p>Die .htaccess Datei konnte nicht geschrieben werden!</p>');
define('kdl_error_writing_htpasswd',				'<p>Die .htpasswd Datei konnte nicht geschrieben werden!</p>');

define('kdl_header_access_denied',					'Zugriff verweigert');
define('kdl_header_error',									'kitDirList - Fehlermeldung');
define('kdl_header_list_date',							'Datum');
define('kdl_header_list_files',							'Dateien');
define('kdl_header_list_size',							'Größe');
define('kdl_header_list_sort',							'Reihenfolge umkehren');
define('kdl_header_login',									'Anmeldung erforderlich');

define('kdl_label_upload',									'Datei (max. %s)');
define('kdl_label_mkdir',										'Verzeichnis');

define('kdl_msg_access_denied',							'<p>Sie sind nicht berechtigt auf diese Daten zuzugreifen.</p>');
define('kdl_msg_admin_mode',								'<p style="font-style:italic;color:maroon;text-align:center;">Achtung, <b>Sie sind als Administrator angemeldet</b> und haben Zugriff auf alle Benutzerdaten!</p>');
define('kdl_msg_confirm_unlink_dir',				'<p>Soll das Verzeichnis <b>%s</b> und sein gesamter Inhalt wirklich gelöscht werden? <a href="%s">JA, Verzeichnis löschen!</a></p>');
define('kdl_msg_confirm_unlink_file',				'<p>Soll die Datei <b>%s</b> wirklich gelöscht werden? <a href="%s">JA, Datei löschen!</a></p>');
define('kdl_msg_mkdir_success',							'<p>Das Verzeichnis <b>%s</b> wurde angelegt.</p>');
define('kdl_msg_no_files',									'<div style="width:99%;text-align:center;padding:10px 0;">- keine Dateien vorhanden -</div>');
define('kdl_msg_unlink_dir_success',				'<p>Das Verzeichnis <b>%s</b> wurde gelöscht.</p>');
define('kdl_msg_unlink_file_success',				'<p>Die Datei <b>%s</b> wurde gelöscht.</p>');
define('kdl_msg_upload_success',						'<p>Die Datei <b>%s</b> wurde erfolgreich übertragen.</p>');

define('kdl_text_anonymous',								'- anonym -');
define('kdl_text_upload_subject',						'kitDirList - Datenübertragung');
?>