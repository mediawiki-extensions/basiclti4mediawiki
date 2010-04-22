<?php
/**
 * MediaWiki put category before editing page
 *
 * @file
 * @ingroup Extensions
 * @version 1.0
 * @author Jose Diago
 */

// Extension credits that will show up on Special:Version
$wgExtensionCredits['other'][] = array(
	'name' => 'Put category before editing page',
	'version' => '1.0',
	'author' => 'Charles Severance, Jose Diago',
	'url' => 'http://www.mediawiki.org',
	'description' => 'This extension puts a category on the page content before showing the page for editing',
);

$wgHooks['EditFormPreloadText'][] = 'putCatLinkAtBottom';

function putCatLinkAtBottom(&$textbox, &$title)
{
    if (isset($_SESSION['BLTIclassroom'])) {
    
        $myClassRoom = $_SESSION['BLTIclassroom'];

        if(isset($_REQUEST['title']))
        {
            if($_REQUEST['title'] == "Category:" . $myClassRoom){
                //we won't add the category link to the category itself
                return true;
            }
        }

        $textbox = $textbox . "\n\n[[Course:$myClassRoom]]";
    }
    
    return true;
}

$wgHooks['SkinBuildSidebar'][] = 'changeMainCategory';

function changeMainCategory($skin, &$sidebar)
{
    if (isset($_SESSION['BLTIclassroom'])) {
    
        $myClassRoom = $_SESSION['BLTIclassroom'];

        $mp = $sidebar['navigation'][0]['href'];
        $sidebar['navigation'][0]['href'] = str_replace('Main_Page', 'Course:'.$myClassRoom, $mp);
        unset($sidebar['navigation'][1]);  // Community Portal
        unset($sidebar['navigation'][2]);  // Current Events
        unset($sidebar['navigation'][4]);  // Random

        // $textbox = $textbox . "\n\n[[Course:$myClassRoom]]";
    }
    
    return true;
}


?>
