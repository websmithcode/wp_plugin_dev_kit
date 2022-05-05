<?php

namespace Nillkizz;

class Shortcodes
{
  function __construct($dir = "", $shortcodes = [])
  {
    $this->dir = $dir; // Dir with your plugin shortcode files
    $this->shortcodes = $shortcodes; //  your shortcode names (without ".php" extension)
  }

  function init()
  {
    $this->add_shortcodes();
  }

  function add_shortcodes()
  {
    foreach ($this->shortcodes as $shortcode) {
      add_shortcode($shortcode, $this->init_shortcode($shortcode));
    }
  }

  function init_shortcode($shortcode)
  {
    $dir = $this->dir . $shortcode;
    if (is_dir($dir)) $path =  $dir . '/' . $shortcode . '.php';
    else $path = $dir . '.php';

    return (function () use ($path) {
      $public_path = '/shortcodes/' . basename(dirname($path)) . '/public/';
      include $path;
    });
  }
}
