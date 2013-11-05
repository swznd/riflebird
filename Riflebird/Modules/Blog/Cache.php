<?php
namespace Riflebird\Modules\Blog;

use \Riflebird\API as API;

class Cache extends Blog
{
  private static $cache_file = 'cache.json';
  private static $update_file = '.last_update';
  
  
  public static function read() {
    if ( ! file_exists(static::$content_dir . '/' . static::$cache_file)) {
      static::create();
    }
    
    static::update();
  }
  
  public static function create() {
    file_put_contents(static::$content_dir . '/' . static::$cache_file, '{}');
    touch(static::$content_dir . '/' . static::$update_file);
  }
  
  public static function update() {
    $cf = file_get_contents(static::$content_dir . '/' . static::$cache_file);
    $uf = file_get_contents(static::$content_dir . '/' . static::$update_file);
    $cache = json_decode($cf, true);
    $last_update = ! empty($uf) && is_numeric($uf) ? $uf : '';
    $cache_files = array();
    
    foreach($cache as $kitem => $citem) {
      if ( ! file_exists($citem['file'])) {
        unset($cache[$kitem]);
      }
      
      $cache_files[] = $citem['file'];
    }
    
    $current_files = API\Filesystem::readDir(static::$content_dir, array('name' => '.md'));
    $changed_files = API\Filesystem::readDir(static::$content_dir, array('name' => '.md', 'after' => $last_update));
    
    $new_files = array_diff($current_files, $cache_files);
    $files = array_unique(array_merge($new_files, $changed_files));

    $contents = array();
    $markdownParser = new \dflydev\markdown\MarkdownParser;

    foreach($files as $file) {
      $cf = file_get_contents($file);
      preg_match_all('/^---(.*)---(.*)$/s', $cf, $rawcontent);
      if (count($rawcontent) < 2) {
        continue;
      }
      
      $attribute = API\Yaml::parse($rawcontent[1][0]);
      $slug = str_replace(static::$content_dir, '', $file);
      $slug = str_replace('.'.pathinfo($file,PATHINFO_EXTENSION), '', $slug);
      $slug = API\String::createSlug(trim($slug, '/'));
      
      $isNew = false;
      
      if ( ! array_key_exists($slug, $cache)) {
        $isNew = true;
      }
      
      $attribute['created_at'] = $isNew ? time() : $cache[$slug]['created_at'];
      $attribute['modified_at'] = filemtime($file);
      $attribute['file'] = $file;
      $attribute['slug'] = $slug;
      
      if (empty($attribute['author'])) {
        $attribute['author'] = static::$config['author'];
      }
      
      if (empty($attribute['date'])) {
        $attribute['date'] = $isNew ? date('Y-m-d') : $cache[$slug]['date'];
      }
      
      $attribute['content'] = $markdownParser->transformMarkdown($rawcontent[2][0]);
      
      $contents[$slug] = $attribute;
    }
    
    if ( ! empty($contents)) {
      $wc = json_encode(array_merge($cache, $contents));
      $wu = time();
    
      file_put_contents(static::$content_dir . '/' . static::$cache_file, $wc);
      file_put_contents(static::$content_dir . '/' . static::$update_file, $wu);
    }
  }
}