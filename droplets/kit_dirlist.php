//:Show a specific directory in public or protected mode
//:Usage: [[kit_dirlist]]
/**
 * kitDirList
 * 
 * @author Ralf Hertsch (ralf.hertsch@phpmanufaktur.de)
 * @link http://phpmanufaktur.de
 * @copyright 2011
 * @license GNU GPL (http://www.gnu.org/licenses/gpl.html)
 * @version $Id$
 */
if (file_exists(WB_PATH.'/modules/kitDirList/class.dirlist.php')) {
  require_once(WB_PATH.'/modules/kitDirList/class.dirlist.php');
  $dirList = new kitDirList();
  return $dirList->action();
}
else {
  return "kitDirList wurde nicht gefunden!";
}