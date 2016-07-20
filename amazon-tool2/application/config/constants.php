<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/*
|--------------------------------------------------------------------------
| Custome constents
|--------------------------------------------------------------------------
|
| Following are custome constents
|
*/
//define('SITE_URL','http://topratedbestbabymonitors.com');//for best baby monitors
//define('SITE_URL','http://bestplaypenforbabies.com');
define('SITE_URL','http://topratedbestinfantcarseats.com');
define('FRAMEWORK_URL','http://localhost/frameworks/');
/*define('AMAZON_BUTTON','<center><img 
		class="aligncenter 
		size-full wp-image-87" 
		alt="check-price-on-amazon" 
		src="'.SITE_URL.'" 
		width="268" 
		height="37"></center>');
*/		
////src="'.SITE_URL.'/wp-content/uploads/2015/12/check-price-at-amazon.png"
define('AMAZON_BUTTON','<img class="aligncenter size-full wp-image-87"	alt="check-price-on-amazon" src="https://drive.google.com/uc?export=view&id=1uL131_l5AHYg-NxjRbbp1iddLUQizqj9Dg" width="268" height="37">');
define('ASSOCIATIVE_TAG','babycarseats0f8-20');//'babyplaypen07-20'

				
/* End of file constants.php */
/* Location: ./application/config/constants.php */