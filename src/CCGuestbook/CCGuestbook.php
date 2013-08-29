<?php
/**
* A guestbook controller as an example to show off some basic controller and model-stuff.
* 
* @package LydiaCore
*/
class CCGuestbook extends CObject implements IController {

  private $pageTitle = 'Movic Guestbook Example';
  private $guestbookModel;
  
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
     $this->guestbookModel = new CMGuestbook();
  }
  
  /**
  * Implementing interface IController. All controllers must have an index action.
  */
  public function Index() { 
    $this->views->SetTitle($this->pageTitle);
    $this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
      'posts'=>$this->guestbookModel->ReadAll(), 
      'formAction'=>$this->request->CreateUrl('', 'handler')
      ));
  }

  /**
  * Handle posts from the form and take appropriate action.
  */
  public function Handler() {
    if(isset($_POST['doAdd'])) {
      $this->guestbookModel->Add(strip_tags($_POST['newEntry']));
    }
    elseif(isset($_POST['doClear'])) {
      $this->guestbookModel->DeleteAll();
    }            
    elseif(isset($_POST['doCreate'])) {
      $this->guestbookModel->Init();
    } 
    $this->RedirectTo($this->request->CreateUrl($this->request->controller));
//    $this->RedirectTo($this->request->controller);
    //header('Location: ' . $this->request->CreateUrl('guestbook'));
  }
  
}