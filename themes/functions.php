<?php
/**
* Helpers for theming, available for all themes in their template files and functions.php.
* This file is included right before the themes own functions.php
*/

/**
* Login menu. Creates a menu which reflects if user is logged in or not.
*/
function login_menu() {
  $mo = Cmovic::Instance();
  if($mo->user->IsAuthenticated()) {
    $items = "<a href='" . create_url('user/profile') . "'>" . $mo->user->GetAcronym() . "</a> ";
    if($mo->user->IsAdministrator()) {
      $items .= "<a href='" . create_url('acp') . "'>acp</a> ";
    }
    $items .= "<a href='" . create_url('user/logout') . "'>logout</a> ";
  } else {
    $items = "<a href='" . create_url('user/login') . "'>login</a> ";
  }
  return "<nav>$items</nav>";
}

/**
* Get messages stored in flash-session.
*/
function get_messages_from_session() {
  $messages = Cmovic::Instance()->session->GetMessages();
  $html = null;
  if(!empty($messages)) {
    foreach($messages as $val) {
      $valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
      $class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
      $html .= "<div class='$class'>{$val['message']}</div>\n";
    }
  }
  return $html;
}

/**
 * Create a url to an internal resource.
 *
 * @param string the whole url or the controller. Leave empty for current controller.
 * @param string the method when specifying controller as first argument, else leave empty.
 * @param string the extra arguments to the method, leave empty if not using method.
 */
function create_url($urlOrController=null, $method=null, $arguments=null) {
  return Cmovic::Instance()->request->CreateUrl($urlOrController, $method, $arguments);
}

/**
* Create a url by prepending the base_url.
*/
function base_url($url) {
  return Cmovic::Instance()->request->base_url . trim($url, '/');
}

/**
* Return the current url.
*/
function current_url() {
  return Cmovic::Instance()->request->current_url;
}

/**
* Render all views.
*/
function render_views() {
  return Cmovic::Instance()->views->Render();
}

/**
 * Print debuginformation from the framework.
 */
function get_debug() {
  // Only if debug is wanted.
  $mo = Cmovic::Instance();  
  if(empty($mo->config['debug'])) {
    return;
  }

  // Get the debug output
  $html = null;

  if(isset($mo->config['debug']['db-num-queries']) && $mo->config['debug']['db-num-queries'] && isset($mo->db)) {
    $html .= "<p>Database made " . $mo->db->GetNumQueries() . " queries.</p>";
  }    
  if(isset($mo->config['debug']['db-queries']) && $mo->config['debug']['db-queries'] && isset($mo->db)) {
    $html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $mo->db->GetQueries()) . "</pre>";
  }     
  // if(isset($mo->config['debug']['timer']) && $mo->config['debug']['timer']) {
  //   $html .= "<p>Page was loaded in " . round(microtime(true) - $mo->timer['first'], 5)*1000 . " msecs.</p>";
  // }    
  if(isset($mo->config['debug']['movic']) && $mo->config['debug']['movic']) {
    $html .= "<hr><h3>Debuginformation</h3><p>The content of Cmovic:</p><pre>" . htmlent(print_r($mo, true)) . "</pre>";
  }    
 if(isset($mo->config['debug']['session']) && $mo->config['debug']['session']) {
   $html .= "<hr><h3>SESSION</h3><p>The content of Cmovic->session:</p><pre>" . htmlent(print_r($mo->session, true)) . "</pre>";
   $html .= "<p>The content of \$_SESSION:</p><pre>" . htmlent(print_r($_SESSION, true)) . "</pre>";
 }    
  return $html;
}