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

?>
