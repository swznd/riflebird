<?php
namespace Riflebird;

class View
{
  private $theme_dir;
  private $data = array();
  private $parser;
  
  public function __construct() {
    $this->parser = new \Lex\Parser();
    
    $this->theme = API\Config::get('sites', 'theme');
    $this->theme_dir = getcwd() . '/' . API\Config::get('sites', 'directory.theme') . '/' . $this->theme;
  }
  
  public static function getLayout($template = '') {
    if ( ! empty($template) && file_exists(__DIR__ . '/layouts.yaml')) {
      $layouts = API\Yaml::parseFile(__DIR__ . '/layouts.yaml');
      
      if ( ! empty($layouts[$template])) {
        return $layouts[$template];
      }
    }
    
    return 'default';
  }
  
  public function setData($data) {
    $this->data = $data;
  }
  
  public function render($template, $data = array()) {
    if ( ! is_array($data)) {
      $data = array($data);
    }
    
    $viewdata = array_merge($this->data, $data);
    
    if ( ! API\Filesystem::isHTML($template)) {
      $template .= '.html';
    }
    
    if (file_exists($this->theme_dir . '/templates/' . $template)) {
      $html = $this->parser->parse(file_get_contents($this->theme_dir . '/templates/' . $template), $viewdata, array($this, 'callback'), false);
    }
    else {
      echo "Template $template is not exists";
      return;
    }
    
    return $this->renderLayout($template, $html);
  }
  
  private function renderLayout($template, $content) {
    $layout = static::getLayout(API\Filesystem::getFileName($template));
    
    if ( ! API\Filesystem::isHTML($layout)) {
      $layout .= '.html';
    }
    
    if ( ! file_exists($this->theme_dir . '/layouts/' .$layout)) {
      echo "Layout $layout is not exists";
      return;
    }
    
    $this->data['layout_content'] = $content;
 
    $html = $this->parser->parse(file_get_contents($this->theme_dir . '/layouts/' . $layout), $this->data, array($this, 'callback'), false);
    $html = \Lex\Parser::injectNoParse($html);
    
    return $html;
  }
  
  public static function callback() {
    
  }
}