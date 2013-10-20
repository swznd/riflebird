<?php
namespace Riflebird\API;

class Filesystem
{
  
  public static function isYaml($file) {
    return pathinfo($file, PATHINFO_EXTENSION) == 'yaml';
  }
  
  public static function isHasDir($file) {
    return dirname($file) != '.';
  }
}
