<?php
namespace Riflebird\API;

class Yaml
{
  
  public static function parse($yaml) {
    return \Spyc::YAMLLoad($yaml);
  }
  
  public static function parseFile($file) {
    if (file_exists($file)) {
      return self::parse($file);
    }
    
    return false;
  }
  
  public static function dump($array) {
    return \Sypc::YAMLDump($array);
  }
  
  public static function dumpFile($array, $file) {
    if (is_dir(dirname($file))) {
      return file_put_contents($file, self::parse($array), LOCK_EX);
    }
  }
}