

1) Put all files in to mediawiki/extensions folder
2) Edit LocalSettings.php and add the Authentication Extension  at the end

include("extensions/BLTIAuth.php");

3) Configure BLTI:
Remote Tool URL: http://url/mediawiki/extensions/Redirect2Course.php
Password: secret
