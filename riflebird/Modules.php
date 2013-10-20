<?php
namespace Riflebird;

class Modules
{
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
  
  public static function registerAutoload($name) {
    spl_autoload_register($name);
  }
  
  public static function load($modules) {
    
    if ( ! is_array($modules)) {
      $modules[$modules] = $modules;
    }
    
    foreach ($modules as $module) {
      self::registerAutoload($module . '::autoload');
    }
  }
}