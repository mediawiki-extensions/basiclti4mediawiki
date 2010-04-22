<?php
/**
 * MediaWiki KillLogout link
 *
 * @file
 * @ingroup Extensions
 * @version 1.0
 * @author Jose Diago
 */

if( !defined( 'MEDIAWIKI' ) )
	die();
 
// Extension credits that will show up on Special:Version
$wgExtensionCredits['other'][] = array(
	'name' => 'KillLogout link',
	'version' => '1.0',
	'author' => 'Charles Severance, Jose Diago',
	'url' => 'http://www.mediawiki.org',
	'description' => 'Kills the Logout link',
);

$wgHooks['PersonalUrls'][] = 'KillLogout'; /* Disallow logout link */

/* Kill logout link */
function KillLogout(&$personal_urls, $title)
{
    //Logout link is only killed if we come from Moodle
    if (isset($_SESSION['BLTIclassroom'])) {
        $personal_urls['logout'] = null;
	$personal_urls['preferences'] = null;
    }

    return true;

}

?>
