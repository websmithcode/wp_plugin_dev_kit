<?php

namespace PluginName;

(function () use ($public_path) {
  global $plugin_name;
  $plugin_name->enqueue_style(['name' => 'plugin_name-example-shortcode-style', 'path' => $public_path . 'style.css']);
  $plugin_name->enqueue_script(['name' => 'plugin_name-example-shortcode-script', 'path' => $public_path . 'script.js']);

  include_once 'template.php';
})();
