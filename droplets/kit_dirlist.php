<?php 
//:Access to kitDirList - public or protected directory listing with full access control
//:Parameters:
 media - path within MEDIA directory
 include - show only files with extensions, no dot, no wildcards, separate with comma
 exclude - like above but exclude files with extensions
 sort - default sorting: asc = ascending, desc = descending
 recursive - default false, enable for access to subdirectories
 kit_intern - allow access for named KIT INTERNAL CATEGORY's
 kit_news - allow access for named KIT NEWSLETTER's
 kit_dist - allow access for named KIT DISTRIBUTION LIST's
 wb_group - allow access for named WebsiteBaker groups
 
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
  if (isset($include)) $params[kitDirList::param_include] = $include;
  if (isset($exclude)) $params[kitDirList::param_exclude] = $exclude; 
  if (isset($kit_intern)) $params[kitDirList::param_kit_intern] = $kit_intern;
  if (isset($kit_news)) $params[kitDirList::param_kit_news] = $kit_news;
  if (isset($kit_dist)) $params[kitDirList::param_kit_dist] = $kit_dist;
  if (isset($wb_group)) $params[kitDirList::param_wb_group] = $wb_group;
  $params[kitDirList::param_copyright] = (isset($copyright) && strtolower($copyright) == 'false') ? false : true;
  $params[kitDirList::param_recursive] = (isset($recursive) && strtolower($recursive) == 'true') ? true : false;
  $params[kitDirList::param_sort] = (isset($sort) && strtolower($sort) == 'desc') ? 'desc' : 'asc';
  $dirList->setParams($params);
  return $dirList->action();
}
else {
  return "kitDirList ist not installed!";
}
?>