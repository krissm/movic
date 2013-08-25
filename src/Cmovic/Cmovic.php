<?php
/**
* Main class for movic, holds everything.
*
* @package movicCore
*/
class Cmovic implements ISingleton {

   private static $instance = null;

   /**
    * Singleton pattern. Get the instance of the latest created object or create a new one. 
    * @return Cmovic The instance of this class.
    */
   public static function Instance() {
      if(self::$instance == null) {
         self::$instance = new Cmovic();
      }
      return self::$instance;
   }

   /**
    * Constructor
    */
  protected function __construct() {
    // include the site specific config.php and create a ref to $ly to be used by config.php
    $mo = &$this; //en fix för att variabeln $mo skall gå att använda direkt i filen site/config.php.
    require(MOVIC_SITE_PATH.'/config.php');
  }

  /**
  * Frontcontroller, check url and route to controllers.
  */
  public function FrontControllerRoute() {
    // Step 1
    // Take current url and divide it in controller, method and parameters
    $this->request = new CRequest($this->config['url_type']);
    $this->request->Init($this->config['base_url']);
    $controller = $this->request->controller;
    $method     = $this->request->method;
    $arguments  = $this->request->arguments;

    // Is the controller enabled in config.php?
    $controllerExists    = isset($this->config['controllers'][$controller]);
    $controllerEnabled   = false;
    $className           = false;
    $classExists         = false;

    if($controllerExists) {
      $controllerEnabled    = ($this->config['controllers'][$controller]['enabled'] == true);
      $className            = $this->config['controllers'][$controller]['class'];
      $classExists          = class_exists($className);
    }

    // Step 2
    // Check if there is a callable method in the controller class, if then call it
    if($controllerExists && $controllerEnabled && $classExists) {
      $rc = new ReflectionClass($className);
      if($rc->implementsInterface('IController')) {
        if($rc->hasMethod($method)) {
          $controllerObj = $rc->newInstance();
          $methodObj = $rc->getMethod($method);
          $methodObj->invokeArgs($controllerObj, $arguments);
        } else {
          die("404. " . get_class() . ' error: Controller does not contain method.');
        }
      } else {
        die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
      }
    } 
    else { 
      die('404. Page is not found.');
    }

    //debug
    $this->data['debug']  = "\n\tREQUEST_URI - {$_SERVER['REQUEST_URI']}\n\t";
    $this->data['debug'] .= "SCRIPT_NAME - {$_SERVER['SCRIPT_NAME']}\n";
  }

  /**
  * Theme Engine Render, renders the views using the selected theme.
  */
  public function ThemeEngineRender() {
    // Get the paths and settings for the theme
    $themeName    = $this->config['theme']['name'];
    $themePath    = MOVIC_INSTALL_PATH . "/themes/{$themeName}";
    $themeUrl  = $this->request->base_url . "themes/{$themeName}";
    
    // Add stylesheet path to the $ly->data array
    $this->data['stylesheet'] = "{$themeUrl}/style.css";

    // Include the global functions.php and the functions.php that are part of the theme
    $mo = &$this;
    $functionsPath = "{$themePath}/functions.php";
    if(is_file($functionsPath)) {
      include $functionsPath;
    }

    // Extract $mo->data to own variables and handover to the template file
    extract($this->data);      
    include("{$themePath}/default.tpl.php");

    //echo "<h1>I'm Cmovic::ThemeEngineRender</h1><p>You are most welcome. Nothing to render at the moment</p>";
    //echo "<h2>The content of the config array:</h2><pre>", print_r($this->config, true) . "</pre>";
    //echo "<h2>The content of the data array:</h2><pre>", print_r($this->data, true) . "</pre>";
    //echo "<h2>The content of the request array:</h2><pre>", print_r($this->request, true) . "</pre>";
  }
}  