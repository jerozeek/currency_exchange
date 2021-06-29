<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);



//JWT
const KEY = 'light2021';

const iss = 'http://localhost/github/currency-exchange/';

const aud = 'http://localhost/github/currency-exchange/';

//define('iat',time());
const iat = 1356999524;

//define('nbf',iat + 30);
const nbf = 1357000000;

const exp = iat - 1 * 60;  // valid for 1 min after generate

const company_address = 'Paste 1234 S. Broadway St. City, State 12345';
const app_name = 'Skyebloom';
const DAILY_DEPOSIT_LIMIT = 10000;
const DAILY_WITHDRAWAL_LIMIT = 100000;
const DAILY_DEPOSIT_LIMIT_KYC = 100000;
const DAILY_WITHDRAWAL_LIMIT_KYC = 500000;
const app_link = 'http://localhost/github/currency-exchange/';
const stripeSecretKey = 'sk_test_CIyGmb1c0Bm4JGnrMjvDs5O300pYNU0ut4';
const CHARGES = 0.5;
const stripePublicKey = 'pk_test_KPQoeXoc6SUDHanW1BjtpgpS003CqpjqHx';
const fixed_amount_charges = 100;
const fixed_amount_percentage_charges = 0.5;
const min_transfer = 5000;
const max_transfer = 5000000;
const in_app_charges = 0.2;
const in_app_min = 2000;
const in_app_max = 2000000;
const exchangeCharges = 200;
const APP_ID = 'a88f3da0-8ac2-40e4-808c-4eb67e9e2cb5';
const ONESIGNAL_API = 'NTMyZGJmMTUtMTUzYS00ZDUyLTkyZTUtZWZkYTYzZWRlZjU1';
/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
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
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
