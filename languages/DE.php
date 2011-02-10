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

define('kdl_error_create_dir',							'<p>Das Verzeichnis <b>%s</b> konnte nicht angelegt werden!</p>');
define('kdl_error_dir_not_exists',					'<p>Das Verzeichnis <b>%s</b> existiert nicht!</p>');
define('kdl_error_kit_not_installed',				'<p>KeepInTouch (KIT) ist nicht installiert!</p>');
define('kdl_error_kit_param_rejected',			'<p>Da KeepInTouch (KIT) nicht auf diesem System installiert ist, kann der Parameter <b>%s</b> nicht verwendet werden!</p>');
define('kdl_error_missing_kit_category',		'<p>Die mit dem Parameter <b>%s</b> genannte(n) Kategorien <b>%s</b> wurden nicht gefunden! Prüfen Sie Ihre Angaben!');
define('kdl_error_protection_undefined',		'<p>Es wurde nicht definiert auf welche Weise der Zugriff auf das Verzeichnis kontrolliert werden soll. Legen Sie eine KeepInTouch (KIT) Kategorie oder eine WebsiteBaker Benutzergruppe fest!</p>');
define('kdl_error_public_dir_but_protect',	'<p>Sie haben ein frei zugängliches MEDIA Verzeichnis angegeben und möchten gleichzeitig eine Zugriffskontrolle über eine KeepInTouch (KIT) Kategorie bzw. über eine WebsiteBaker Benutzergruppe - dies ist nicht möglich. Um eine Zugriffskontrolle zu ermöglichen muss sich das Verzeichnis in <b>/media/kit_protect</b> befinden.</p>');
define('kdl_error_unknown_param_key',				'<p>Der Parameter <b>%s</b> ist nicht definiert, bitte prüfen Sie die übergebenen Parameter!</p>');
define('kdl_error_writing_htaccess',				'<p>Die .htaccess Datei konnte nicht geschrieben werden!</p>');
define('kdl_error_writing_htpasswd',				'<p>Die .htpasswd Datei konnte nicht geschrieben werden!</p>');

define('kdl_header_error',									'kitDirList - Fehlermeldung');

?>