//:Access to kitDirList - public or protected directory listing with full access control
//:Please visit http://phpManufaktur.de for informations about kitDirList!
/**
 * kitDirList
 * @author Ralf Hertsch (ralf.hertsch@phpmanufaktur.de)
 * @link http://phpmanufaktur.de
 * @copyright 2011
 * @license GNU GPL (http://www.gnu.org/licenses/gpl.html)
 * @version $Id$
 */
if (file_exists(WB_PATH.'/modules/kit_dirlist/class.dirlist.php')) {
  require_once(WB_PATH.'/modules/kit_dirlist/class.dirlist.php');
  $dirList = new kitDirList();
  $params = $dirList->getParams();
  if (isset($media)) $params[kitDirList::param_media] = $media;
  if (isset($include)) $params[kitDirList::param_include] = $include;
  if (isset($exclude)) $params[kitDirList::param_exclude] = $exclude; 
  if (isset($kit_intern)) $params[kitDirList::param_kit_intern] = $kit_intern;
  if (isset($kit_news)) $params[kitDirList::param_kit_news] = $kit_news;
  if (isset($kit_dist)) $params[kitDirList::param_kit_dist] = $kit_dist;
  if (isset($wb_group)) $params[kitDirList::param_wb_group] = $wb_group;
  if (isset($page_link)) $params[kitDirList::param_page_link] = $page_link;
  $params[kitDirList::param_wb_auto] = (isset($wb_auto) && strtolower($wb_auto) == 'true') ? true : false;
  $params[kitDirList::param_kit_auto] = (isset($kit_auto) && strtolower($kit_auto) == 'true') ? true : false;
  $params[kitDirList::param_upload] = (isset($upload) && strtolower($upload) == 'true') ? true : false;
  $params[kitDirList::param_unlink] = (isset($unlink) && strtolower($unlink) == 'true') ? true : false;
  $params[kitDirList::param_mkdir] = (isset($mkdir) && strtolower($mkdir) == 'true') ? true : false;
  $params[kitDirList::param_copyright] = (isset($copyright) && strtolower($copyright) == 'false') ? false : true;
  $params[kitDirList::param_recursive] = (isset($recursive) && strtolower($recursive) == 'true') ? true : false;
  $params[kitDirList::param_sort] = (isset($sort) && strtolower($sort) == 'desc') ? 'desc' : 'asc';
  $params[kitDirList::param_login_dlg] = (isset($login_dlg)) ? strtolower($login_dlg) : 'kit_login';
  $params[kitDirList::param_account_dlg] = (isset($account_dlg)) ? strtolower($account_dlg) : 'kit_account';
  $params[kitDirList::param_hide_account] = (isset($hide_account) && strtolower($hide_account) == 'true') ? true : false;
  $params[kitDirList::param_css] = (isset($css) && strtolower($css) == 'false') ? false : true;
  $dirList->setParams($params);
  return $dirList->action();
}
else {
  return "kitDirList ist not installed!";
}