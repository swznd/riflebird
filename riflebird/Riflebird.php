<?php
namespace Riflebird;

require_once __DIR__ . '/vendor/autoload.php';

class Riflebird
{
  protected $vars = array();
  protected static $instance;
  protected $method;


  public static function autoload($className) {
    $currentClass = str_replace(__NAMESPACE__.'\\', '', __CLASS__);

    $basePath = __DIR__;

    if (substr($basePath, -strlen($currentClass)) === $currentClass) {
      $basePath = dirname(__DIR__);
    }

    $className = ltrim($className, '\\');
    $fileName  = $basePath . DIRECTORY_SEPARATOR;
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    
    if (file_exists($fileName)) {
      require $fileName;
    }
  }
  
  
  public static function registerAutoload() {
    spl_autoload_register(__NAMESPACE__ . '\Riflebird::autoload');
  }
  
  
  public function __construct($vars = array()) {
    
    if (isset($vars)) {
      foreach ($vars as $name => $value) {
        $this->vars[$name] = $value;
      }
    }
    
    if ( ! static::$instance instanceof $this) {
      static::$instance = $this;
    }
    
    $this->method = strtolower(API\Request::getMethod());
  }
  
  public static function getInstance() {
    return static::$instance;
  }
  
  public function setVars($name, $value) {
    $this->vars[$name] = $value;
  }
  
  public function getVars($name) {
    return $this->vars[$name];
  }
  
  private function process($route) {
    foreach ($route as $key => $value) {
      if ($key === 'function') {
        $params = array();
        
        if(array_key_exists('params', $value)) {
          $params[] = $value['params'];
          unset($value['params']);
        }
        
        if ( ! empty($this->method)) {
          $last = count($value) - 1;
          if (is_callable($value[$last].'_'.$this->method)) {
            $value[$last] = $value[$last];
          }
        }
        
        if (is_callable($value)) {  
          $cname = ucfirst($value[0]);
          $class = new $cname();
          $method = $value[1];
          
          return call_user_func_array(array($class, $method), $params);
        }
      }
      
      if ($key === 'redirect') {
        $status = 302;
        
        if (is_array($value) && ! empty($value['url'])) {
          $url = $value['url'];
          
          if ( ! empty($value['type'])) {
            if ($value['type'] === 'permanent') {
              $status = 301;
            }
          }
        }
        else {
          $url = $value;
        }
        
        return array('url' => $url, 'status' => $status);
      }
    }
  }
  
  public function run() {
    $sites = API\Config::get('sites');
    
    $router = new Router();
    $route = $router->current();
    
    $output = new Output;
    $view = new View();
    
    if (is_array($route)) {
      $data = $this->process($route);
      
      if ( ! empty($route['template'])) {
        $view->setData($sites);
        $body = $view->render($route['template'], $data);
        
        $output->setBody($body);
      }
      
      else if ( ! empty($route['redirect'])) {
        $output->redirect($data['url'], $data['status']);
      }
      
      else {
        $output->setStatus(500);
        $body = $view->render_error(404);
      
        $output->setBody($body);
      }
    }
    else {
      $output->setStatus(404);
      $body = $view->render_error(404, array('title' => 'Not Found', 'description' => 'URL that you access does not exists'));
      
      $output->setBody($body);
    }
    
    $output->send();
  }
}