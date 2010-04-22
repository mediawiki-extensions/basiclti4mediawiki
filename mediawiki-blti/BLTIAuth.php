<?php
/**
 * MediaWiki Authentication via Basic LTI extension
 *
 * @file
 * @ingroup Extensions
 * @version 1.2
 * @author Charles Severancee, Based on Jose Diago's OKI Extension
 * @author Jose Diago, based on Ioannis Yessios's CAS Authentication Extension
 */

$_pwdSecret = "spasiva danke gracias thanks"; // Secret phrase for password generation

if( !defined( 'MEDIAWIKI' ) )
	die();
 
// Extension credits that will show up on Special:Version
$wgExtensionCredits['other'][] = array(
	'name' => 'Authentication via BLTI',
	'version' => '1.1',
	'author' => 'Charles Severance, Jose Diago',
	'url' => 'http://www.mediawiki.org',
	'description' => 'Auto-authenticates users via BLTI',
);
 
require_once dirname(__FILE__) . '/../includes/GlobalFunctions.php';
if (isset($_REQUEST['BLTI'])) {
    session_cache_limiter( 'priva te, must-revalidate' );
    wfSuppressWarnings();
    session_start();
    wfRestoreWarnings();
}

// The Hook (R)
$wgHooks['UserLoadFromSession'][] = 'AutoAuthenticateBLTI';

function AutoAuthenticateBLTI( $user, &$result ) {
    global $_pwdSecret;

    if (isset($_REQUEST['BLTI'])) {
    
        //$lg = Language::factory($wgLanguageCode);

        if($_REQUEST['BLTI'] == 'yes') {
        
            global $wgContLang;
            // Let's get the username from BLTI and give it MW's usual first capital
            $name = $wgContLang->ucfirst( $_REQUEST['BLTIusername'] );
            // Clean up name according to title rules
            $t = Title::newFromText( $name );
            if( is_null( $t ) ) {
                return true;
            }
            $canonicalName = $t->getText();
            if( !User::isValidUserName( $canonicalName ) ) {
                return true;
            }
            //--------------------------------------------------------------------------
            // Create a new MediaWiki user if not exists
            //--------------------------------------------------------------------------
            $u = User::newFromName( $canonicalName );
            $uid = $u->getID();

            if ( 0 == $uid ) {
                // create a new user
                $u->addToDatabase();
                $u->setPassword( genPass($_REQUEST['BLTIfullname'],$_pwdSecret) );
                $u->setEmail( $_REQUEST['BLTIemail'] );
                $u->setRealName( $_REQUEST['BLTIfullname'] );
                $u->setToken();
                $u->setOption( 'rememberpassword', 0 );
                $u->setOption( 'nocache', 1 );
                $u->saveSettings();
            }
 
            $u->setCookies();
            $user = $u;
        }
    }
    else if ($_REQUEST['title'] == 'Special:Userlogout') {
        $user->logout();
    }

    return true;
}

// generate a unique password based on the username
function genPass($username, $_pwdSecret){
  return md5($username.$_pwdSecret);
}

?>
