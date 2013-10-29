<?php
namespace Riflebird\API;

class Security
{
  
  protected static function stripSlashes($string) {
    if (is_array($string)) {
      return array_map(array('self', 'stripSlashes'), $string);
    }
    
    return stripslashes($string);
  }
  
  public static function cleanMagicQuotes($string) {
    if (get_magic_quotes_gpc()) {
      return self::stripSlahes($string);
    }
    
    return $string;
  }
  
  public static function xssClean($str) {
    $str = rawurldecode($str);
    $str = str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $str);
		$str = html_entity_decode($str, ENT_COMPAT, $charset);
		$str = preg_replace('~&#x(0*[0-9a-f]{2,5})~ei', 'chr(hexdec("\\1"))', $str);
		$str = preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
    
    return $str;
  }
}