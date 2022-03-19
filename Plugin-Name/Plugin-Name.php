<?php

/**
 * Plugin Name: Plugin Name
 * Description: Template of plagin from dev kit
 * Version: 0.0.0
 * Author: Alexander Smith
 * Author URI: https://t.me/alxndr_smith
 */

namespace PluginName;

use \Nillkizz\PluginBase;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}



if (!class_exists('PluginName')) :
  require('deps_check.php');
  (new DepsChecker(__FILE__))->init();

  add_action('n_core_defined', '\PluginName\plugin_name');
  function plugin_name()
  {
    class PluginName extends PluginBase
    {
      public $includes = [
        'acf-init.php',
        'rest_api.php',
      ];
      public $js_scripts = [
        ['name' => 'plugin_name', 'path' => 'public/js/script.js'],
      ];
      public $css_styles = [
        ['name' => 'plugin_name', 'path' => 'public/css/style.css']
      ];
    }


    global $plugin_name;
    // Instantiate only once.
    if (!isset($plugin_name)) {
      $plugin_name = new PluginName(__FILE__);
      add_action('init', [$plugin_name, 'initialize']);
    }
    return $plugin_name;
  }
endif;
