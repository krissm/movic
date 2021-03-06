<?php
/**
* Main class for movic, holds everything.
*
* Som det är nu finns det två sätt olika sätt att få tag i informationen i Cmovic, 
* antingen som global variabel $mo eller via dess instans-metod, 
* Cmovic::Instance(), som returnerar instansen av Cmovic enligt singleton design mönster.
*
* @package movicCore
*/
class Cmovic implements ISingleton {

  private static $instance = null;
  public $config = array();
  public $request;
  public $data;
  public $db;
  public $views;
  public $session;
  public $timer = array();
  public $user;

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
    * Initiates Session, Database, CViewContainer, and MUser objects, 
    * with the help of the config settings (the config array member variable of this object)
    */
  protected function __construct() {
    // time page generation
    $this->timer['first'] = microtime(true); 

    // include the site specific config.php, and create a variable $mo which holds a 
    // reference to the Movic instance. 
    $mo = &$this; //en fix för att variabeln $mo skall gå att använda direkt i filen 
    // site/config.php.
    require(MOVIC_SITE_PATH.'/config.php');

    // Start a named session
    session_name($this->config['session_name']);
    session_start();
    $this->session = new CSession($this->config['session_key']);
    $this->session->PopulateFromSession();

    // Set default date/time-zone
    date_default_timezone_set($this->config['timezone']);

    // Create a database object.
    if(isset($this->config['database'][0]['dsn'])) {
      $this->db = new CDatabase($this->config['database'][0]['dsn']);
    }

    // Create a container for all views and theme data
    $this->views = new CViewContainer();

    // Create a object for the user
    $this->user = new CMUser($this);
  }

  /**
  * Frontcontroller check url and route to controllers.
  * The controllers' methods sets the Session, Database, View, and User member variable 
  * with the help of model objects 
  * Fill $views : CViewContainer
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
        $formattedMethod = str_replace(array('_', '-'), '', $method);
        if($rc->hasMethod($formattedMethod)) {
          $controllerObj = $rc->newInstance();
          $methodObj = $rc->getMethod($formattedMethod);
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
  }

  /**
  * Theme Engine Render, renders the views using the selected theme.
  * extract all data variables and view data to gain access to them in the theme view
  */
  public function ThemeEngineRender() {
    // Save to session before output anything
    $this->session->StoreInSession();

    // Get the paths and settings for the theme
    $themeName    = $this->config['theme']['name'];
    $themePath    = MOVIC_INSTALL_PATH . "/themes/{$themeName}";
    $themeUrl     = $this->request->base_url . "themes/{$themeName}";
    
    // Add stylesheet path to the $mo->data array
    $this->data['stylesheet'] = "{$themeUrl}/style.css";

    // Include the global functions.php and the functions.php that are part of the theme
    $mo = &$this;
    include(MOVIC_INSTALL_PATH . '/themes/functions.php');
    $functionsPath = "{$themePath}/functions.php";
    if(is_file($functionsPath)) {
      include $functionsPath;
    }

    // Extract $mo->data and $mo->view->data to own variables and handover to the template file
    extract($this->data);    
    extract($this->views->GetData());  
    include("{$themePath}/default.tpl.php");
  }
}  