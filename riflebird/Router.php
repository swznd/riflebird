<?php
namespace Riflebird;

class Router
{
  private $routes;
  private $method;
  private $path;
  
  public function __construct() {
    $this->routes = API\Config::get('routes');
    $this->path = static::getPath();
    $this->method = strtolower(API\Request::getMethod());
  }
  
  
  public function current($routes = array()) {
    $this->routes = array_merge($this->routes, $routes);
    $key = $this->getKeyRoute();

    if (is_null($key)) {
      return null;
    }
    
    return $this->routes[$key];
  }
  
  private function getKeyRoute() {
    
    foreach($this->routes as $route => $value) {
      if (preg_match('/^'.str_replace('/', '\/', $route).'$/', $this->path)) {
        return $route;
      }
    }
    
    return null;
  }
  
  public static function getPath() {
    $path = '/';
    
    if ( ! empty($_SERVER['PATH_INFO'])) {
      $path = $_SERVER['PATH_INFO'];
    }
    elseif ( ! empty($_SERVER['ORIG_PATH_INFO']) && $_SERVER['ORIG_PATH_INFO'] !== '/index.php') {
      $path = $_SERVER['ORIG_PATH_INFO'];
    }
    else {
      if ( ! empty($_SERVER['REQUEST_URI']) && !empty($_SERVER['SCRIPT_NAME'])) {
        $uri = $_SERVER['REQUEST_URI'];
       
        if (strpos($uri, '?') > 0) {
          $path = strstr($_SERVER['REQUEST_URI'], '?', true);
        }

        if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
          $path = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        }
        elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
          $path = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
        }
      }
    }
    
    return $path;
  }
  
}