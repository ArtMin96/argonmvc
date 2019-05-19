<?php

define('DEBUG', true); // set debug to false for production

// this should be set to false for security reasons. If you need to run migrations from the browser you can set this to true, then run migrations, then set it back to false.
define('RUN_MIGRATIONS_FROM_BROWSER', false);

define('BANNED_IP_FILE', 'app/restricted/bannedIP.txt'); // set file to block IP addresses

define('ERROR_LOG', ROOT.DS.'logs'.DS.'errors.log'); // set file to report errors

define('DB_ENGINE', 'mysql'); // // database engine
define('DB_CHARSET', 'utf8'); // // database charset
define('DB_HOST', '127.0.0.1'); // // database host *** use IP address to avoid DNS lookup
define('DB_NAME', 'argon'); // database name
define('DB_USER', 'root'); // database user
define('DB_PASSWORD', ''); // database password

define('DEFAULT_CONTROLLER', 'Home'); // default controller if there isn't one defined in the url

define('DEFAULT_LAYOUT', 'default'); // if no layout is set in the controller use this layout.

define('PROOT', '/'); // set this to '/' for a live server

define('VERSION','0.30'); // release version this can be used to display version or version assets like css and js files useful for fighting cached browser files

define('SITE_TITLE', 'Argon'); // This will be used if no site title is set

define('MENU_BRAND', 'Argon'); //This is the Brand text in the menu

define('CURRENT_USER_SESSION_NAME', 'kwXeusqldkiIKjehsLQZJFKJ'); //session name for logged in user

define('REMEMBER_ME_COOKIE_NAME', 'JAJEI6382LSJVlkdjfh3801jvD'); // cookie name for logged in user remember me

define('REMEMBER_ME_COOKIE_EXPIRY', 2592000); // time in seconds for remember me cookie to live (30 days)

define('ACCESS_RESTRICTED', 'Restricted'); //controller name for the restricted redirect
