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

define('kdl_btn_logout',										'Abmelden');

define('kdl_cfg_date_time',									'd.m.Y - H:i');

define('kdl_content_login_wb',							'<p>Damit Sie auf die Dateien in diesem Verzeichnis zugreifen können, müssen Sie sich zunächst <a href="%s">mit Ihrem Benutzernamen und Ihrem Passwort anmelden</a>.</p><p>Nach der Anmeldung werden Sie automatisch wieder hierher geleitet.</p>');
define('kdl_content_login_kit',							'<p>Damit Sie auf die Dateien in diesem Verzeichnis zugreifen können, müssen Sie sich zunächst <a href="%s">mit Ihrem Benutzernamen und Ihrem Passwort anmelden</a>.</p><p>Nach der Anmeldung werden Sie automatisch wieder hierher geleitet.</p>');

define('kdl_error_create_dir',							'<p>Das Verzeichnis <b>%s</b> konnte nicht angelegt werden!</p>');
define('kdl_error_dir_not_exists',					'<p>Das Verzeichnis <b>%s</b> existiert nicht!</p>');
define('kdl_error_file_not_found',					'<p>Die Datei <b>%s</b> wurde nicht gefunden!</p>');
define('kdl_error_kdl_id_not_available',		'<p>Die Datei mit der <b>ID %05d</b> steht nicht zur Verfügung!</p>');
define('kdl_error_kit_dlg_invalid',					'<p>Die Kennung für den Dialog <b>%s</b> konnte nicht ermittelt werden!</p>');
define('kdl_error_kit_id_missing',					'<p>Der KeepInTouch (KIT) Datensatz mit der <b>ID %05d</b> wurde nicht gefunden!</p>');
define('kdl_error_kit_not_installed',				'<p>KeepInTouch (KIT) ist nicht installiert!</p>');
define('kdl_error_kit_param_rejected',			'<p>Da KeepInTouch (KIT) nicht auf diesem System installiert ist, kann der Parameter <b>%s</b> nicht verwendet werden!</p>');
define('kdl_error_kit_register_id_missing',	'<p>Für die KIT Registrierung mit der <b>ID %05d<b> existiert kein gültiger Eintrag. Bitte wenden Sie sich an den Systemadministrator!</p>');
define('kdl_error_missing_kit_category',		'<p>Die mit dem Parameter <b>%s</b> genannte(n) Kategorien <b>%s</b> wurden nicht gefunden! Prüfen Sie Ihre Angaben!</p>');
define('kdl_error_missing_wb_group',				'<p>Die mit dem Parameter <b>%s</b> genannete Gruppe <b>%s</b> wurde nicht gefunden! Pürfen Sie Ihre Angaben!</p>');
define('kdl_error_protection_undefined',		'<p>Es wurde nicht definiert auf welche Weise der Zugriff auf das Verzeichnis kontrolliert werden soll. Legen Sie eine KeepInTouch (KIT) Kategorie oder eine WebsiteBaker Benutzergruppe fest!</p>');
define('kdl_error_public_dir_but_protect',	'<p>Sie haben ein frei zugängliches MEDIA Verzeichnis angegeben und möchten gleichzeitig eine Zugriffskontrolle über eine KeepInTouch (KIT) Kategorie bzw. über eine WebsiteBaker Benutzergruppe - dies ist nicht möglich. Um eine Zugriffskontrolle zu ermöglichen muss sich das Verzeichnis in <b>/media/kit_protect</b> befinden.</p>');
define('kdl_error_unknown_param',						'<p>Der Parameter <b>%s</b> ist nicht definiert. Programmausführung gestoppt.</p>');
define('kdl_error_unknown_param_key',				'<p>Der Parameter <b>%s</b> ist nicht definiert, bitte prüfen Sie die übergebenen Parameter!</p>');
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

define('kdl_msg_access_denied',							'<p>Sie sind nicht berechtigt auf diese Daten zuzugreifen.</p>');

?>