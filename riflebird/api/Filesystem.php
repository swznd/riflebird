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
      
      if( ! empty($filter) && ! preg_match('/'.$filter.'/', $file->getBasename())) {
        continue;
      }
      
      $files[] = str_replace($dir . '/', '', $file->getPathname());
    }
    
    return $files;
  }
}
