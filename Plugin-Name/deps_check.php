<?php

namespace NAirTickets;

class DepsChecker
{
  public $err_msg = '<span style="color:red; text-transform: uppercase; font-weight:bold;">*** Nillkizz Core plugin required ***</span>';

  function __construct(string $__FILE__)
  {
    $this->__FILE__ = $__FILE__;
  }

  function init()
  {
    if (!get_option('nillkizz_core_activated')) {
      register_activation_hook($this->__FILE__, [$this, 'stopActivation']);
      $this->showMsgInLinks();
    }
  }

  function showMsgInLinks()
  {
    $plugin_name = plugin_basename($this->__FILE__);
    add_filter("plugin_action_links_$plugin_name", function ($links) {
      array_unshift($links, $this->err_msg);
      return $links;
    });
  }

  function stopActivation()
  {
    die($this->err_msg);
  }
}
