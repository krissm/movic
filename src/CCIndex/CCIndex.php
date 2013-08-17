<?php
/**
* Standard controller layout.
* 
* @package movicCore
*/
class CCIndex implements IController {

   /**
    * Implementing interface IController. All controllers must have an index action.
    */
   public function Index() {   
      global $mo;
      $mo->data['title'] = "The Index Controller";
      $mo->data['main'] = "<h1>The Index Controller</h1>";
   }
}