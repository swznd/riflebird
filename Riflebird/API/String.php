<?php
namespace Riflebird\API;

class String
{
  
  public static function limitByWord($string, $limit) {
    if (empty($string)) {
      return null;
    }
    
    preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $string, $matches);
    
    return $matches[0];
  }
  
  public static function createSlug($string, $separator = '-') {
		$trans = array('&.+?;' => '',
			             '[^a-z0-9 _-]' => '',
			             '\s+' => $separator,
			             '('.$separator.')+' => $separator
		);

		$string = strip_tags($string);

		foreach ($trans as $key => $val) {
			$str = preg_replace("#".$key."#i", $val, $string);
		}
		
    $str = strtolower($string);

		return trim($string, $separator);
  }
}