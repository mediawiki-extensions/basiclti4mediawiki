

1) Put all files in to mediawiki/extensions folder
2) Edit LocalSettings.php and add the Authentication Extension  at the end

include("extensions/BLTIAuth.php");
include("extensions/CatLinkAtBottom.php");
include("extensions/KillLogout.php");

If you want to include special permissions for the others  categories/courses, like only categories/courses logged can be read, edited, created pages...
Usage CategoryPermissionsBLTI:

$wgCategoryExclusive=array("Category:cat_name","Category:cat2_name");//deny acces to these categories for anyone not in the group
$wgGroupAlwaysAllow=''; //set a group name to ALWAYS allow access to this group
$wgGroupDefaultAllow=false; //set to true to allow everyone access to pages without a category

//get the name of BLTIclassroom
  $course_name = $_SESSION['BLTIclassroom'];
//add groups to category permissions by:
  $wgGroupPermissions['user']['*_read'] 	= false; //Set permission for others categories/courses
  $wgGroupPermissions['user']['*_edit'] 	= false;
  $wgGroupPermissions['user']['*_move'] 	= false;
  $wgGroupPermissions['user']['*_created']  = false;
  $tag_category = 'Category';//'Course' //Be careful with the translate of Categoryç
//Set permission for current category/course
  $wgGroupPermissions['user'][$tag_category.':'.$course_name.'_read'] 	= true; 
  $wgGroupPermissions['user'][$tag_category.':'.$course_name.'_edit'] 	= true;
  $wgGroupPermissions['user'][$tag_category.':'.$course_name.'_move'] 	= true;
  $wgGroupPermissions['user'][$tag_category.':'.$course_name.'_create']  = true;
  $wgGroupPermissions['user'][$tag_category.':'.$course_name.'_createpage']  = true;

//if yo want you can define special permission access for a group to all categories like this
$wgGroupPermissions['group_name']['*_read']=true;
include_once('extensions/CategoryPermissionsBLTI.php');


3) Configure BLTI:
Remote Tool URL: http://url/mediawiki/extensions/Redirect2Course.php
Password: secret
