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
  $params = $dirList->getParams();
  if (isset($media)) $params[kitDirList::param_media] = $media;
  if (isset($recursive)) $params[kitDirList::param_recursive] = (bool) $recursive;
  if (isset($include)) $params[kitDirList::param_include] = $include;
  if (isset($exclude)) $params[kitDirList::param_exclude] = $exclude; 
  if (isset($redirect_id)) $params[kitDirList::param_redirect_id] = (int) $redirect_id;
  if (isset($kit_intern)) $params[kitDirList::param_kit_intern] = $kit_intern;
	if (isset($kit_news)) $params[kitDirList::param_kit_news] = $kit_news;
	if (isset($kit_dist)) $params[kitDirList::param_kit_dist] = $kit_dist;
	if (isset($wb_group)) $params[kitDirList::param_wb_group] = $wb_group;
	$dirList->setParams($params);
	return $dirList->action();
}
else {
  return "kitDirList ist not installed!";
}