<?php
namespace Riflebird\API;

class Config
{
  
  public static function get($file, $key = '') {
    
    if ( ! Filesystem::isYaml($file)) {
      $file .= '.yaml';
    }
    
    if ( ! Filesystem::isHasDir($file)) {
      $file = Filesystem::systemPath('Config/'.$file);
    }

    $sites = Yaml::parse($file);
    
    if ( ! empty($key)) {
      
      if (strpos($key, '.') !== false) {
        $arrkeys = explode('.', $key);
        $lenkeys = count($arrkeys);
        $poskeys = 0;
        
        $sitesIt = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($sites), \RecursiveIteratorIterator::SELF_FIRST);
 
        foreach($sitesIt as $key => $sub) {
          if ($key === $arrkeys[$poskeys]) {
            if ($poskeys == $lenkeys - 1) {
              return $sub;
            }
            $poskeys++;
          }
        }
        
        return null;
      }
      
      if ( ! empty($sites[$key])) {
        return $sites[$key];
      }
      
      return null;
    }
    
    return $sites;
  }
  
}