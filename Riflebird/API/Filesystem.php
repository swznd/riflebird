<?php
namespace Riflebird\API;

class Filesystem
{
  
  public static function isYaml($file) {
    return pathinfo($file, PATHINFO_EXTENSION) == 'yaml';
  }
  
  public static function isHTML($file) {
    return pathinfo($file, PATHINFO_EXTENSION) == 'html';
  }
  
  public static function isHasDir($file) {
    return dirname($file) != '.';
  }
  
  public static function getFileName($file) {
    return pathinfo($file, PATHINFO_FILENAME);
  }
  
  public static function parentPath($path = '') {
    return getcwd() . '/' . $path;
  }
  
  public static function systemPath($path = '') {
    return static::parentPath('Riflebird/'. $path);
  }
  
  public static function readDir($dir, $filter = array()) {
    if ( ! is_dir($dir)) {
      return null;
    }
    
    $files = array();  
    $scanIt = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
    foreach($scanIt as $file) {
      if ($file->getBasename() == '.' OR $file->getBasename() == '..') {
        continue;
      }
      
      if( ! empty($filter['name']) && ! preg_match('/'.$filter['name'].'/', $file->getBasename())) {
        continue;
      }
      
      if ( ! empty($filter['before']) && $file->getMTime() > $filter['before']) {
        continue;
      }
      
      if ( ! empty($filter['after']) && $file->getMTime() < $filter['after']) {
        continue;
      }
      
      $files[] = $file->getPathname();
    }
    
    return $files;
  }
}
