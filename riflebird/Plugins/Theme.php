<?php
namespace Riflebird\Plugins;

class Theme
{
  
  public static function url() {
    $args = func_get_args();
    $params = end($args);
    
    $src = ! empty($params['src']) ? $params['src'] : '';
    
    return \Riflebird\API\Request::getHost() . \Riflebird\API\Config::get('sites', 'theme') . '/' . $src;
  }
}