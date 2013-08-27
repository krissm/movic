<?php
/**
* Bootstrapping, setting up and loading the core.
*
* @package LydiaCore
*/

/**
* Enable auto-load of class declarations.
*
*en funktion för att autoloada klassfiler. När du gör new på en klass så anropas denna funktion för att ladda in klassfilen. Den är skriven så att den först letar efter klass-filen i LYDIA_INSTALL_PATH/src och därefter i LYDIA_SITE_PATH/src. På det sättet kan användaren ha sina egna klassfiler under site-katalogen.
*/

function autoload($aClassName) {
  $classFile = "/src/{$aClassName}/{$aClassName}.php";
   $file1 = MOVIC_INSTALL_PATH . $classFile;
   $file2 = MOVIC_SITE_PATH . $classFile;
   if(is_file($file1)) {
      require_once($file1);
   } elseif(is_file($file2)) {
      require_once($file2);
   }
}
spl_autoload_register('autoload');

/**
 * Helper, wrap html_entites with correct character encoding
 */
function htmlent($str, $flags = ENT_COMPAT) {
  return htmlentities($str, $flags, Cmovic::Instance()->config['character_encoding']);
}

/**
* Set a default exception handler and enable logging in it. "en standard funktion som samlar upp alla exceptions som inte hanteras. Det ger mig en möjlighet att i framtiden logga dessa till en textfil."
*/
function exception_handler($exception) {
  echo "Movic: Uncaught exception: <p>" . $exception->getMessage() . "</p><pre>" . $exception->getTraceAsString(), "</pre>";
}
set_exception_handler('exception_handler');