<?php
/**
 * BLTI
 *
 * @file
 * @ingroup Extensions
 * @version 0.1
 * @author Charles Severance based on for from Antoni Bertran and Jose Diago
 */

$going2MW = false;

// BLTI integration
require_once 'IMSBasicLTI/ims-blti/blti.php';

if ( ! is_basic_lti_request() ) return;
//Let's get the user's data
$context = new BLTI("secret", false, false);


if($context->valid) {
	$agentCourse = $context->getCourseName();
	$agentUserName = $context->getUserShortName();
	$agentEmail = $context->getUserEmail();
	$agentFullName = $context->getUserName();
	$going2MW = true;
        session_start();
	$_SESSION['BLTIclassroom'] = $agentCourse;
}
else {
	echo 'Error validating: '.$context->message;
}

$context = null;

if($going2MW) {
	$myURI = $_SERVER['REQUEST_URI'];
	$newURI = str_replace("extensions/Redirect2CourseBLTI.php", "index.php", $myURI);
	$newParameters = "title=Category:$agentCourse&BLTIusername=$agentUserName&BLTIemail=$agentEmail&BLTIfullname=$agentFullName&BLTI=yes";
	$newURI = $newURI . "?" . $newParameters;
	header("Location: $newURI");
}
?>
