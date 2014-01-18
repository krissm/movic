<?php
/**
* Site configuration, this file is changed by user per site.
*/

/*
* Set level of error reporting
*/
error_reporting(-1);
ini_set('display_errors', 1);

/**
 * Set what to show as debug or developer information in the get_debug() theme helper.
 */
$mo->config['debug']['movic'] 					= true;
$mo->config['debug']['session'] 				= true;
$mo->config['debug']['timer'] 					= true;
$mo->config['debug']['db-num-queries'] 	= true;
$mo->config['debug']['db-queries'] 			= true;

/**
* Set database(s).
*/
$mo->config['database'][0]['dsn'] = 'sqlite:' . MOVIC_SITE_PATH . '/data/.ht.sqlite';

/**
 * How to hash password of new users, choose from: plain, md5salt, md5, sha1salt, sha1.
 */
$mo->config['hashing_algorithm'] = 'sha1salt';


/*
* Define session name
*/
$mo->config['session_name'] = preg_replace('/[:\.\/-_]/', '', __DIR__);
$mo->config['session_key'] 	= 'movic';

/*
* Define server timezone
*/
$mo->config['timezone'] = 'Europe/Stockholm';

/*
* Define internal character encoding
*/
$mo->config['character_encoding'] = 'UTF-8';

/*
* Define language
*/
$mo->config['language'] = 'en';

/**
* Define the controllers, their classname and enable/disable them.
*
* The array-key is matched against the url, for example: 
* the url 'developer/dump' would instantiate the controller with the key "developer", that is 
* CCDeveloper and call the method "dump" in that class. This process is managed in:
* $mo->FrontControllerRoute();
* which is called in the frontcontroller phase from index.php.
*/
$mo->config['controllers'] = array(
	'index' 		=> array('enabled' => true, 'class' => 'CCIndex'),
	'developer' => array('enabled' => true, 'class' => 'CCDeveloper'),
	'guestbook' => array('enabled' => true, 'class' => 'CCGuestbook'),
	'user' 			=> array('enabled' => true, 'class' => 'CCUser'),
	'acp' 	    => array('enabled' => true, 'class' => 'CCAdminControlPanel'),	
	);

/**
* Settings for the theme.
*/
$mo->config['theme'] = array(
  // The name of the theme in the theme directory
  'name'    => 'core', 
);

/**
* Set a base_url to use another than the default calculated
*/
$mo->config['base_url'] = null;

/**
* What type of urls should be used?
* 
* default      = 0      => index.php/controller/method/arg1/arg2/arg3
* clean        = 1      => controller/method/arg1/arg2/arg3
* querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
*/
$mo->config['url_type'] = 1;

