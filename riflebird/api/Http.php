<?php
namespace Riflebird\API;

class Http
{
  private $headers = array();
  private static $specialHeader = array(
    'CONTENT_TYPE',
    'CONTENT_LENGTH',
    'PHP_AUTH_USER',
    'PHP_AUTH_PW',
    'PHP_AUTH_DIGEST',
    'AUTH_TYPE'
  );
  
  public static function get($rkey = '') {
    $gets = array();
    
    if ( ! empty($_SERVER['QUERY_STRING'])) {
      if (function_exists('mb_parse_str')) {
        mb_parse_str($_SERVER['QUERY_STRING'], $output);
      }
      else {
        parse_str($_SERVER['QUERY_STRING'], $output);
      }
      
      $gets = Security::cleanMagicQuotes($output);
      
      if ( ! empty($hkey)) {
        if ( ! empty($gets[$hkey])) {
          return $gets[$hkey];
        }
        
        return null;
      }
    }
    
    return $gets;
  }
  
  
  public static function post($rkey = '') {
    $posts = array();
    
    $input = @file_get_contents('php://input');
    
    if ( ! empty($input)) {
      if (function_exists('mb_parse_str')) {
        mb_parse_str($input, $output);
      }
      else {
        parse_str($input, $output);
      }
      
      $posts = Security::cleanMagicQuotes($output);   
      
      if ( ! empty($hkey)) {
        if ( ! empty($gets[$hkey])) {
          return $gets[$hkey];
        }
        
        return null;
      }   
    }
    
    return $posts;
  }
  
  public static function params($key = '') {
    $params = array_merge(static::get(), static::post());
    
    if ( ! empty($key)) {
      if ( ! empty($params[$key])) {
        return $params[$key];
      }
      
      return null;
    }
    
    return $params;
  }
  
  public static function getHeader($hkey = '') {
    if (empty($this->headers)) {
      foreach($_SERVER as $key => $value) {
        if (strpos($key, 'X_') === 0 || strpos($key, 'HTTP_') === 0 || in_array($key, static::$specialHeader)) {
          $key = strtoupper($key);
          $this->headers[$key] = $value;
        }
      }
    }
    
    if ( ! empty($hkey)) {
      if ( ! empty($this->headers[$hkey])) {
        return $this->headers[$hkey];
      }
      
      return null;
    }
    
    return $this->headers;
  }
  
  public static function getMethod() {
    return strtoupper($_SERVER['REQUEST_METHOD']);
  }
  
  public static function getReferrer() {
    return static::getHeader('HTTP_REFERER');
  }
  
  public static function getUserAgent() {
    return static::getHeader('HTTP_USER_AGENT');
  }
  
  public static function getIp() {
    if ($_SERVER['X_FORWARDED_FOR']) {
      return $_SERVER['X_FORWARDED_FOR'];
    }
    elseif ($_SERVER['CLIENT_IP']) {
      return $_SERVER['CLIENT_IP'];
    }
    
    return $_SERVER['REMOTE_ADDR'];
  }
  
  public static function isXhr() {
    return static::getHeader('X_REQUESTED_WITH') === 'XMLHttpRequest';
  }
  
  public static function redirect($url, $code = 302) {
    header("Location: ".$url, true, $code);
  }
}