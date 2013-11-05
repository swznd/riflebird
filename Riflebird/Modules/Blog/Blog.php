<?php
namespace Riflebird\Modules\Blog;

use \Riflebird\API as API;

class Blog
{
  protected static $content_dir;
  protected static $config;
  
  public function __construct() {
    static::$content_dir = API\Filesystem::parentPath(API\Config::get('sites', 'directory.content') . '/blog');
    static::$config = API\Config::get(__DIR__ . '/config/config');
    
    if ( ! is_dir(static::$content_dir)) {
      mkdir(static::$content_dir, 0755);
    }
  }
  
  public function lists() {
    $args = func_get_args();
    $params = end($args);
    
    Cache::read();
  }
}