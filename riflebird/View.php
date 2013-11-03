<?php
namespace Riflebird;

class View
{
  private static $theme_dir;
  private $data = array();
  private $parser;
  
  public function __construct() {
    $this->parser = new \Lex\Parser();
    
    $this->theme = API\Config::get('sites', 'theme');
    static::$theme_dir = getcwd() . '/' . API\Config::get('sites', 'directory.theme') . '/' . $this->theme;
  }
  
  public static function getLayout($template = '') {
    if ( ! empty($template) && file_exists(static::$theme_dir . '/layouts.yaml')) {
      $layouts = API\Yaml::parseFile(static::$theme_dir . '/layouts.yaml');
      
      if ( ! empty($layouts[$template])) {
        return $layouts[$template];
      }
    }
    
    return 'default';
  }
  
  public function setData($data) {
    $this->data = array_merge($this->data, $data);
  }
  
  public function render($template, $data = array()) {
    if ( ! is_array($data)) {
      $data = array($data);
    }
    
    $viewdata = array_merge($this->data, $data);
    
    if ( ! API\Filesystem::isHTML($template)) {
      $template .= '.html';
    }
    
    if (file_exists(static::$theme_dir . '/templates/' . $template)) {
      $html = $this->parser->parse(file_get_contents(static::$theme_dir . '/templates/' . $template), $viewdata, array($this, 'callback'), false);
    }
    else {
      echo "Template $template is not exists";
      return null;
    }
    

    return $this->renderLayout($template, $html);
  }
  
  public function render_error($suffix = '', $data = array(), $template = 'error') {
    if (file_exists(static::$theme_dir . '/templates/' . $template . '_' . $suffix . '.html')) {
      $template .= '_'.$suffix;
    }
    
    return $this->render($template, $data);
  }
  
  private function renderLayout($template, $content) {
    $layout = static::getLayout(API\Filesystem::getFileName($template));
    
    if ( ! API\Filesystem::isHTML($layout)) {
      $layout .= '.html';
    }
    
    if ( ! file_exists(static::$theme_dir . '/layouts/' .$layout)) {
      echo "Layout $layout is not exists";
      return;
    }
    
    $this->data['layout_content'] = $content;
 
    $html = $this->parser->parse(file_get_contents(static::$theme_dir . '/layouts/' . $layout), $this->data, array($this, 'callback'), false);
    $html = \Lex\Parser::injectNoParse($html);
    
    return $html;
  }
  
  public static function callback() {
    
  }
}