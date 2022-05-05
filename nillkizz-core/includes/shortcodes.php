<?php

namespace Nillkizz;

class Shortcodes
{
  function __construct($dir = "", $shortcodes = [])
  {
    $this->dir = $dir; // Dir with your plugin shortcode files
    $this->shortcodes = $shortcodes; //  your shortcode files... (Shortcode - without ".php" extension, slashes replaces by "__")
  }

  function init()
  {
    $this->add_shortcodes();
  }

  function add_shortcodes()
  {
    foreach ($this->shortcodes as $shortcode) {
      $shortcode_name = self::get_shortcode_name($shortcode);

      add_shortcode($shortcode_name, $this->init_shortcode($shortcode));
    }
  }

  function init_shortcode($shortcode_file)
  {
    return (function () use ($shortcode_file) {
      include $this->dir . $shortcode_file;
    });
  }

  static function get_shortcode_name($shortcode_file)
  {
    return str_replace('/', '__', rtrim($shortcode_file, '.php'));
  }
}
