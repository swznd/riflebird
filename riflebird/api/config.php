<?php
namespace Riflebird\API;

class Config
{
  
  public static function get($file, $key = '') {
    
    $riflebird = \Riflebird\Riflebird::getInstance();
    
    if ( ! \Riflebird\API\Filesystem::isYaml($file)) {
      $file .= '.yaml';
    }
    
    if ( ! \Riflebird\API\Filesystem::isHasDir($file)) {
      $file = $riflebird->getVars('config.path') . '/' . $file;
    }
    
    $sites = Yaml::parse($file);
    
    if ( ! empty($key)) {
      if ( ! empty($sites[$key])) {
        return $sites[$key];
      }
      
      return false;
    }
    
    return $sites;
  }
  
}