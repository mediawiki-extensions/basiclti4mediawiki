<?php
/*
 * Custom Permissions Scheme using Categories
 * based on Extension:NamespacePermissions by Petr Andreev
 *
 * Provides separate permissions for each action (read,edit,create,move) based
 * on category tags on pages.
 *
 * Author: Matthew Vernon
 * Additional Contributions by Carsten Zimmermann and Richard Hartmann
 *
 * Licensed under GPL v2 - use,change,enjoy
 *
 * Usage:
 *
 * $wgCategoryExclusive=array("Category:cat_name","Category:cat2_name");//deny acces to these categories for anyone not in the group
 * $wgGroupAlwaysAllow=''; //set a group name to ALWAYS allow access to this group
 * $wgGroupDefaultAllow=false; //set to true to allow everyone access to pages without a category
 *
 * //get the name of BLTIclassroom
  $course_name = $_SESSION['BLTIclassroom'];
 * //add groups to category permissions by:
  $wgGroupPermissions['user']['*_read'] 	= false; //Set permission for others categories/courses
  $wgGroupPermissions['user']['*_edit'] 	= false;
  $wgGroupPermissions['user']['*_move'] 	= false;
  $wgGroupPermissions['user']['*_createpage']  = false;
  $tag_category = 'Category';//'Course' //Be careful with the translate of Categoryç
 * Set permission for current category/course
  $wgGroupPermissions['user'][$tag_category.':'.$course_name.'_read'] 	= true; 
  $wgGroupPermissions['user'][$tag_category.':'.$course_name.'_edit'] 	= true;
  $wgGroupPermissions['user'][$tag_category.':'.$course_name.'_move'] 	= true;
  $wgGroupPermissions['user'][$tag_category.':'.$course_name.'_createpage']  = true;
  $wgGroupPermissions['user'][$tag_category.':'.$course_name.'_create']  = true;
 *
 * //if yo want you can define special permission access for a group to all categories like this
 * $wgGroupPermissions['group_name']['*_read']=true;
 * require_once('extensions/CategoryPermissionsBLTI.php');

 */
 
 
//set up hook
$wgExtensionFunctions[] = "wfCategoryPermissionsBLTI";
 
function wfCategoryPermissionsBLTI()
{
  global $wgHooks;
 
  // use the userCan hook to check permissions
  $wgHooks[ 'userCan' ][] = 'checkCategoryPermissions';
}
 
 
//register extension
$wgExtensionCredits['parserhook'][] = array( 
'name'=>'CategoryPermissionsBLTI', 
'author'=>'Antoni Bertran based on Matthew Vernon', 
'url'=>'http://www.mediawiki.org/wiki/Extension:CategoryPermissions');
 
 
//turn on debug messages by changing to 1 or true
// Remember to set $wgDebugLogFile in LocalSettings.php as well.
define("__debug_permissions", 0);
 
 
function checkCategoryPermissions( $title, $user, $action, $result )
{
  global $wgGroupDefaultAllow, $wgCategoryExclusive, $wgGroupPermissions, $course_name, $tag_category;
 
  $user_allowed=false;
  
  //get categories for this page
  $parentCategories=$title->getParentCategories();
  
 
  //scan list of categories, if any
  if(is_array($parentCategories))
  {
    foreach( $parentCategories as $category=>$dd)
    {
      if(__debug_permissions)wfDebug("checkCategoryPermissions on line ".__LINE__.": Trying action {$action} on category {$category} for user {$user->mName}\n");
 
      if( $user->isAllowed("{$category}_{$action}") )
      {
          if(__debug_permissions)wfDebug("checkCategoryPermissions on line ".__LINE__.": Category-action {$category}_{$action} ALLOWED on {$title->mPrefixedText} to user {$user->mName}\n");
          $user_allowed=true;
      }
      else
      {
        if(in_array($category,$wgCategoryExclusive))
        { //if category is in $wgCategoryExclusive then deny anyone who doesn't have explicit access
          if(__debug_permissions)wfDebug("checkCategoryPermissions on line ".__LINE__.": Category-action {$category}_{$action} DENIED on {$title->mPrefixedText} to user {$user->mName}: Exclusive Category\n");
          $result=false;
          return false;
        }
      }
    }//foreach( $parentCategories as $category=>$dd)
  }//if($parentCategories)
 
  //if we are here, then no exclusive categories have rejected us
  if($user_allowed==true)
  {    $result=null; return true;  }
 
  
  //Gets category
  $mCategoryTitle = $_REQUEST['title'];
  //Check permission in current category
  if(__debug_permissions)wfDebug("checkCategoryPermissions on line ".__LINE__.": Trying action {$action} on $mUrlform category for user {$user->mName}\n");
  if( $user->isAllowed("{$mCategoryTitle}_{$action}"))
  {
    if(__debug_permissions)wfDebug("checkCategoryPermissions on line ".__LINE__.": Action {$action} ALLOWED on {$title->mPrefixedText} to user {$user->mName}: AlwaysAllow\n");
    $result=null;
    return true;
  }
 
  //always allow groups with *_read etc. regardless of categories
  if(__debug_permissions)wfDebug("checkCategoryPermissions on line ".__LINE__.": Trying action {$action} on * (all categories for user {$user->mName}\n");
  if( $user->isAllowed("*_{$action}") )
  {
    if(__debug_permissions)wfDebug("checkCategoryPermissions on line ".__LINE__.": Action {$action} ALLOWED on {$title->mPrefixedText} to user {$user->mName}: AlwaysAllow\n");
    $result=null;
    return true;
  }
 
  if(__debug_permissions)wfDebug("checkCategoryPermissions on line ".__LINE__.": Trying action {$action} on {$title->mPrefixedText} for user {$user->mName}: No categories\n");
  if(!($parentCategories))
  {
    //handle special case of no categories
    //default action is based on wgGroupDefaultAllow
    if($wgGroupDefaultAllow)
    {
      if(__debug_permissions)wfDebug("checkCategoryPermissions on line ".__LINE__.": Action {$action} ALLOWED on {$title->mPrefixedText} to user {$user->mName}: No categories\n");
      $result=null;
      return true;
    }
    else
    {
      if(__debug_permissions)wfDebug("checkCategoryPermissions on line ".__LINE__.": Action {$action} DENIED on {$title->mPrefixedText} to user {$user->mName}: No categories\n");
      $result=false;
      return false;
    }
  }
 
  //default action=deny
  if(__debug_permissions)wfDebug("checkCategoryPermissions on line ".__LINE__.": Action {$action} DENIED on {$title->mPrefixedText} to user {$user->mName}: Default action\n");
  $result=false;
  return false;
}
 
?>