<?php
namespace Riflebird;

require_once __DIR__ . '/vendor/autoload.php';

class Riflebird
{
  protected $vars = array();
  protected static $instance;
  
  
  public static function autoload($className) {
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
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
  
  public function run() {
    $modules = API\Config::get('modules');
    
    Modules::load($modules);
  }
}