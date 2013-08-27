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
   
   /**
    * Constructor
    */
   protected function __construct() {
    $mo = Cmovic::Instance();
    $this->config   = &$mo->config;
    $this->request  = &$mo->request;
    $this->data     = &$mo->data;
    $this->db       = &$mo->db;
  }

}