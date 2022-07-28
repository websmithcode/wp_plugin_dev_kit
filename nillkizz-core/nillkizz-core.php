<?php

/**
 * Plugin Name:  Nillkizz Core
 * Description: Nillkizz core plugin.
 * Version: 1.6.0
 * Author: Alexander Smith
 * Author URI: https://t.me/alx_n_smith
 */

namespace Nillkizz;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}



if (!class_exists('Core')) :
  require_once('plugin_base.php');

  class Core extends PluginBase
  {
    public $includes = [
      'includes/render/index.php',
      'includes/shortcodes.php',
      'includes/utils.php',
      'includes/templater.php',
    ];
    public $css_styles = [
      ['name' => 'nillkizz-core-util', 'path' => 'public/css/util.css'],
    ];
    function __construct($__FILE__ = NULL)
    {
      add_filter('nillkizz_core-ConstPluginName', function () {
        return 'CORE';
      });
      parent::__construct($__FILE__);
      do_action('n_core_defined');
    }

    function initialize()
    {
      parent::initialize();
      do_action('ncore_init');
    }
  }


  add_action('plugins_loaded', '\Nillkizz\nillkizz_core');
  function nillkizz_core()
  {
    global $ncore;
    // Instantiate only once.
    if (!isset($ncore)) {
      $ncore = new Core(__FILE__);
      $ncore->initialize();
    }
    return $ncore;
  }
// Instantiate.

endif;
