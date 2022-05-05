<?php

namespace PluginName;

class DepsChecker
{
  private $err_plugin = "";
  public $err_msg = '<span style="color:red; text-transform: uppercase; font-weight:bold;">*** %s plugin required ***</span>';

  function __construct(string $__FILE__)
  {
    $this->__FILE__ = $__FILE__;
  }

  function init(array $deps = [])
  {
    $this->deps = array_merge(['Nillkizz Core'], $deps);
    $active_plugins = self::get_active_plugins();

    foreach ($this->deps as $dep) {
      if (!in_array($dep, $active_plugins)) {
        $this->err_plugin = $dep;

        register_activation_hook($this->__FILE__, [$this, 'stopActivation']);
        $this->showMsgInLinks();
      }
    }
  }

  static function get_active_plugins()
  {
    return array_map('self::get_plugin_name', (array) wp_get_active_and_valid_plugins());
  }

  function showMsgInLinks()
  {
    $plugin_name = plugin_basename($this->__FILE__);
    add_filter("plugin_action_links_$plugin_name", function ($links) {
      array_unshift($links, sprintf($this->err_msg, $this->err_plugin));
      return $links;
    });
  }

  function stopActivation()
  {
    die(sprintf($this->err_msg, 'Nillkizz Core'));
  }


  static function get_dir_name($path)
  {
    return basename(dirname($path));
  }
  static function unslug($slug)
  {
    return ucwords(str_replace('-', ' ', $slug));
  }
  static function get_plugin_name($path)
  {
    return self::unslug(self::get_dir_name($path));
  }
}
