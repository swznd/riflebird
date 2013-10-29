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
}
