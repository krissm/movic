<?php
/**
* Holding a instance of Cmovic to enable use of $this in subclasses.
*
* @package MovicCore
*/
class CObject {

   public $config;
   public $request;
   public $data;
   public $db;
   public $views;
   public $session;
   public $user;
   /**
    * Constructor
    */
   protected function __construct($mo=null) {
    if(!$mo){
      $mo = Cmovic::Instance();
    }
    $this->config   = &$mo->config;
    $this->request  = &$mo->request;
    $this->data     = &$mo->data;
    $this->db       = &$mo->db;
    $this->views    = &$mo->views;
    $this->session  = &$mo->session;
    $this->user     = &$mo->user;
  }

  /**
   * Redirect to another url and store the session
   */
  protected function RedirectTo($urlOrController=null, $method=null) {
    $mo = Cmovic::Instance();
    if(isset($mo->config['debug']['db-num-queries']) && $mo->config['debug']['db-num-queries'] && isset($mo->db)) {
      $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
    }    
    if(isset($mo->config['debug']['db-queries']) && $mo->config['debug']['db-queries'] && isset($mo->db)) {
      $this->session->SetFlash('database_queries', $this->db->GetQueries());
    }    
    if(isset($mo->config['debug']['timer']) && $mo->config['debug']['timer']) {
      $this->session->SetFlash('timer', $mo->timer);
    }    
    $this->session->StoreInSession();
    header('Location: ' . $this->request->CreateUrl($urlOrController, $method));
  }

  /**
   * Redirect to a method within the current controller. Defaults to index-method. Uses RedirectTo().
   *
   * @param string method name the method, default is index method.
  */
  protected function RedirectToController($method=null) {
    $this->RedirectTo($this->request->controller, $method);
  }


  /**
   * Redirect to a controller and method. Uses RedirectTo().
   *
   * @param string controller name the controller or null for current controller.
   * @param string method name the method, default is current method.
  */
  protected function RedirectToControllerMethod($controller=null, $method=null) {
    $controller = is_null($controller) ? $this->request->controller : null;
    $method = is_null($method) ? $this->request->method : null;   
    $this->RedirectTo($this->request->CreateUrl($controller, $method));
  }
}