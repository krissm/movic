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

   /**
    * Constructor
    */
   protected function __construct() {
    $mo = Cmovic::Instance();
    $this->config   = &$mo->config;
    $this->request  = &$mo->request;
    $this->data     = &$mo->data;
    $this->db       = &$mo->db;
    $this->views    = &$mo->views;
    $this->session  = &$mo->session;
  }

  /**
   * Redirect to another url and store the session
   */
  protected function RedirectTo($url) {
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
    header('Location: ' . $this->request->CreateUrl($url));
  }
}