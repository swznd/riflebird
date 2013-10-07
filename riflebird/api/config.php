<?php
namespace Riflebird\API;

class Config
{
  
  public static function get($files, $key = '') {
    
    $sites = Yaml::parse($files);
    
    if (isset($key)) {
      if (isset($sites[$key])) {
        return $sites[$key];
      }
      
      return false;
    }
    
    return $sites;
  }
}