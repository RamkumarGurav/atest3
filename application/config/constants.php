<?php
defined('BASEPATH') or exit('No direct script access allowed');

$ark_root = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
$ark_root .= "://" . $_SERVER['HTTP_HOST'];
$ark_root .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);


////project details
define('_project_name_', "A TEST 3");//this is used in 
define('_project_name_for_web_title_', "A TEST 3"); # to display
define('_project_complete_name_', "A TEST 3"); # to display
define('_brand_name_', "AT3");//need in left_nav
define('_summernote_', "");//need in left_nav
// define('_project_address_', "MPP Complex, Shop No. 14 15 Siddipet Road Ramayampet Medak, Telangana, 502101 India.");
// define('_project_contact_', "+91 93921 99595");
// define('_project_contact2_', "+91 96663 64912");
// define('_project_contact_without_space_', "+919392199595");
// define('_project_contact_whatsapp_', "+91 93921 99595");
// define('_project_contact_whatsapp_without_space_', "+919392199595");
// define('_project_contact_whatsapp_message_', "Welcome to A TEST 3");
// define('_project_email_', "info@atest3.in");

///email api details
define('__fromemail__', "info@atest3.in");
define('__fromemailpassword__', "dummypass");
define('__email_user_id__', "email_user_id");
define('__email_user_password__', "email_user_password");


////sms api details
define('__sms_user_id__', "sms_user_id");
define('__sms_user_password__', "sms_user_password");

// define('__adminemail__', "info@atest3.in");
// define('__adminsms__', "919392199595");  // for Register

define('_project_web_', "www.atest3.in");
define('_mainsite_hostname_', "atest3.in");

// define('_SMS_BRAND_', "atest3.in");
// define('__GSTIN__', "36AKRPG7758C2ZS");


////social media details
// define('_FACEBOOK_', "https://www.facebook.com/profile.php?id=100069220293490&mibextid=LQQJ4d");
// define('_INSTAGRAM_', "https://instagram.com/atest3.rythusevakendram?utm_source=qr");
// define('_TWITTER_', "");
// define('_YOUTUBE_', "https://youtube.com/@atest3_farmer?si=G2mHlloY4qTbuUsd");
// define('_PINTEREST_', "");
// define('_LINKEDIN_', "");


////google captcha details
define('_local_google_recaptcha_site_key_', "6LezxwUqAAAAAC7iAfqFV-2G8Q6upDtpVxHnTKQx");
define('_local_google_recaptcha_secret_key_', "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe");
define('_google_recaptcha_site_key_', "6LcnlAUqAAAAAOPvpkVUYHO57WZkKgL_DNMCZWtW");
define('_google_recaptcha_secret_key_', "6LcnlAUqAAAAAPQS7pndA5PI8LAuvc0vpwY7xqbQ");


//admin side related routes
define('__logout__', "logout");
define('__signup__', "sign-up");
define('__login__', "Login");
define('__forgotPassword__', "forgot-password");  // for forgot Password
define('__changePassword__', "change-password");  // for Change Password
define('__dashboard__', "dashboard");  // for User Dashboard



//// file paths
define('MAINSITE', $ark_root);
define('MAINSITE_Admin', $ark_root . "secureRegions/");
define('_access_denied_', $ark_root . "secureRegions/wam/access_denied");
define('IMAGE', $ark_root . "assets/front/images/");
define('IMAGE_TEMP', $ark_root);
define('CSS', $ark_root . "assets/front/css/");
define('JS', $ark_root . "assets/front/js/");
define('__scriptFilePath__', "assets/front/");  // for login
define('_uploaded_files_', $ark_root . "assets/uploads/");
define('_uploaded_temp_files_', "assets/uploads/");
define('_lte_files_', $ark_root . "assets/admin/lte/");
define('_admin_files_', $ark_root . "assets/admin/");
define('IMAGE_ADMIN', $ark_root . "assets/admin/images/");
define('_image_files_', $ark_root . "assets/front/images/");

////pagination
define('_all_pagination_', "999999999");

//country
define('__const_country_id__', "1");









/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE') or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ') or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS') or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
