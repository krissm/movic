<?php
/**
* A guestbook controller as an example to show off some basic controller and model-stuff.
* 
* @package LydiaCore
*/
class CCGuestbook extends CObject implements IController, IHasSQL {

  private $pageTitle = 'Movic Guestbook Example';
  private $pageHeader = '<h1>Guestbook Example</h1><p>Showing off how to implement a guestbook in Lydia.</p>';
  private $pageForm = "";
  private $pageMessages = '<h2>Current messages</h2>';
  

  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
  }
  

    /**
   * Implementing interface IController. All controllers must have an index action.
   */
  public function Index() {   
    $formAction = $this->request->CreateUrl('guestbook/Handler');
    $this->pageForm = "
      <form action='{$formAction}' method='post'>
        <p>
          <label>Message: <br/>
          <textarea name='newEntry'></textarea></label>
        </p>
        <p>
          <input type='submit' name='doAdd' value='Add message' />
          <input type='submit' name='doClear' value='Clear all messages' />
          <input type='submit' name='doCreate' value='Create new database' />
        </p>
      </form>
    ";
    $this->data['title'] = $this->pageTitle;
    $this->data['main']  = $this->pageHeader . $this->pageForm . $this->pageMessages;
    $posts = $this->ReadAllFromDatabase();

    foreach($posts as $post){
      $this->data['main'] .= "<div style='background-color:#f6f6f6;border:1px solid #ccc;margin-bottom:1em;padding:1em;'><p>At: {$post['created']}</p><p>{$post['entry']}</p></div>\n";
    }
    // if(isset($_SESSION['guestbook'])) {
    //   foreach($_SESSION['guestbook'] as $val) {
    //     $this->data['main'] .= "<div style='background-color:#f6f6f6;border:1px solid #ccc;margin-bottom:1em;padding:1em;'><p>At: {$val['time']}</p><p>{$val['entry']}</p></div>\n";
    //   }
    // }
  }

    /**
   * Add a entry to the guestbook.
   */
  // public function Add() {
  //   if(isset($_POST['doAdd'])) {
  //     $entry = strip_tags($_POST['newEntry']);
  //     $time = date('r');
  //     $_SESSION['guestbook'][] = array('time'=>$time, 'entry'=>$entry); 
  //   }
  //   elseif(isset($_POST['doClear'])) {
  //     unset($_SESSION['guestbook']);
  //   }            
  //   header('Location: ' . $this->request->CreateUrl('guestbook'));
  // }

    /**
   * Handle posts from the form and take appropriate action.
   */
  public function Handler() {
    if(isset($_POST['doAdd'])) {
      $this->SaveNewToDatabase(strip_tags($_POST['newEntry']));
    }
    elseif(isset($_POST['doClear'])) {
      $this->DeleteAllFromDatabase();
    }            
    elseif(isset($_POST['doCreate'])) {
      $this->CreateTableInDatabase();
    }            
    header('Location: ' . $this->request->CreateUrl('guestbook'));
  }

  /**
  * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
  *
  * @param string $key the string that is the key of the wanted SQL-entry in the array.
  */
  public static function SQL($key=null) {
     $queries = array(
        'create table guestbook'  => "CREATE TABLE IF NOT EXISTS Guestbook (id INTEGER PRIMARY KEY, entry TEXT, created DATETIME default (datetime('now')));",
        'insert into guestbook'   => 'INSERT INTO Guestbook (entry) VALUES (?);',
        'select * from guestbook' => 'SELECT * FROM Guestbook ORDER BY id DESC;',
        'delete from guestbook'   => 'DELETE FROM Guestbook;',
     );
     if(!isset($queries[$key])) {
        throw new Exception("No such SQL query, key '$key' was not found.");
      }
      return $queries[$key];
   }

  /**
   * Save a new entry to database.
   */
  private function SaveNewToDatabase($entry) {
    $this->db->ExecuteQuery(self::SQL('insert into guestbook'), array($entry)); 
    if($this->db->RowCount() != 1) {
      die('Failed to insert new guestbook item into database.');
    }
  }

    /**
   * Delete all entries from the database.
   */
  private function DeleteAllFromDatabase() {
    $this->db->ExecuteQuery(self::SQL('delete from guestbook'));
  }

    /**
   * Read all entries from the database.
   */
  private function ReadAllFromDatabase() {
    try {
      $this->db->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
      return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * from guestbook'));
    } catch(Exception $e) {
      return array();
    }
  }

   /**
   * Save a new entry to database.
   */
  private function CreateTableInDatabase() {
    try {
      $this->db->ExecuteQuery(self::SQL('create table guestbook'));
    } catch(Exception$e) {
      die("Failed to open database: " . $this->config['database'][0]['dsn'] . "</br>" . $e);
    }
  }
  
}