<?php

namespace Nillkizz;

abstract class PluginBase
{
  const AUTHOR = 'NILLKIZZ';

  public $includes = [
    // Your includes relative path...
  ];
  public $shortcodes = [
    // Yout shortcode files path relative for "shortcodes/"... (without ".php" extension)
    // Like wp plugins - must be a directory with filename with same name, or just php file
  ];
  public $templates = [
    // Your templates relative path...
    // Example:
    // '/var/www/html/wp-content/plugins/nillkizz-core/includes/templates/landings-page.php' => 'Landings Page'
  ];
  protected $_css_styles = [
    'quasar@2.6.0' => ['name' => 'quasar', 'url' => '//cdn.jsdelivr.net/npm/quasar@2.6.0/dist/quasar.prod.css'],
  ];
  protected $_js_scripts = [
    'nillkizz-colors' =>     ['name' => 'nillkizz-colors',     'url' => '/wp-content/plugins/nillkizz-core/public/js/nillkizz-colors.js'],
    'nillkizz-cookies' =>     ['name' => 'nillkizz-cookies',    'url' => '/wp-content/plugins/nillkizz-core/public/js/nillkizz-cookies.js'],
    'nillkizz-scroll' =>      ['name' => 'nillkizz-scroll',     'url' => '/wp-content/plugins/nillkizz-core/public/js/nillkizz-scroll.js'],
    'nillkizz-styles' =>      ['name' => 'nillkizz-styles',     'url' => '/wp-content/plugins/nillkizz-core/public/js/nillkizz-styles.js'],
    'nillkizz-utils' =>       ['name' => 'nillkizz-utils',      'url' => '/wp-content/plugins/nillkizz-core/public/js/nillkizz-utils.js'],

    'alpinejs' =>             ['name' => 'alpinejs',            'url' => '//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js', 'in_footer' => true],
    'alpinejs/collapse' =>    ['name' => 'alpinejs/collapse',   'url' => '//unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js'],
    'alpinejs/focus' =>       ['name' => 'alpinejs/focus',      'url' => '//unpkg.com/@alpinejs/focus@3.9.1/dist/cdn.min.js'],
    'alpinejs/intersect' =>   ['name' => 'alpinejs/intersect',  'url' => '//unpkg.com/@alpinejs/intersect@3.x.x/dist/cdn.min.js'],
    'alpinejs/persist' =>     ['name' => 'alpinejs/persist',    'url' => '//unpkg.com/@alpinejs/persist@3.x.x/dist/cdn.min.js'],
    'alpinejs/morph' =>       ['name' => 'alpinejs/morph',      'url' => '//unpkg.com/@alpinejs/morph@3.x.x/dist/cdn.min.js'],
    'vue@3' =>                ['name' => 'vuejs',               'url' => '//cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js', 'in_footer' => true],
    'quasar@2.6.0' =>         ['name' => 'quasar-umd',          'url' => '//cdn.jsdelivr.net/npm/quasar@2.6.0/dist/quasar.umd.prod.js', 'in_footer' => true],
  ];

  public $js_scripts = [];
  public $js_footer_scripts = [];
  public $css_styles = [];
  public $image_sizes = [];

  function __construct($__FILE__ = NULL)
  {
    if (empty($__FILE__)) wp_die('$__FILE__ variable of constructor in class extended with NillkizzPluginBase class - must be defined!');
    if (!function_exists('get_plugin_data')) require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    $this->__FILE__ = $__FILE__;
    $this->__DIR__ = dirname($__FILE__);
    $this->plugin_name = dirname(plugin_basename($__FILE__));
    $this->plugin = plugin_basename($__FILE__);
    $this->plugin_path = trailingslashit(plugin_dir_path($__FILE__));
    $this->plugin_url = plugins_url($this->plugin_name . '/');
    $this->plugin_data = get_plugin_data($__FILE__);
    $this->plugin_version = $this->plugin_data['Version'];

    $_constPluginName = apply_filters($this->plugin_name . '-ConstPluginName', strtoupper($this->plugin_name));

    define($this::AUTHOR . "_" . $_constPluginName . "_PLUGIN_DATA", $this->plugin_data);
    define($this::AUTHOR . "_" . $_constPluginName . "_PATH", $this->plugin_path);
    define($this::AUTHOR . "_" . $_constPluginName . "_NAME", $this->plugin_name);
    define($this::AUTHOR . "_" . $_constPluginName . "_URL", $this->plugin_url);
  }

  function initialize()
  {
    $this->_add_image_sizes();

    $this->_enqueue_styles();
    add_action('wp_enqueue_scripts', [$this, '_enqueue_scripts']);

    $this->_includes();

    $shortcodes = new Shortcodes($this->__DIR__ . '/shortcodes/', $this->shortcodes);
    $shortcodes->init();

    add_filter('templater_page_templates', [$this, '_add_templates']);
  }

  function _add_templates($templates)
  {
    $templates_path = explode('wp-content', $this->plugin_path)[1]; // Relative path for wp-content. Ex.: /plugins/nillkizz-core/templates/example.php

    foreach ($this->templates as $template => $name) {
      $templates[$templates_path . $template] =  $name;
    }

    return $templates;
  }

  function enqueue_style($style)
  {
    if (is_string($style) && isset($this->_css_styles[$style])) $style = $this->_css_styles[$style];

    if (is_array($style)) {
      $gv = [$this, '_get_val'];  // Get value from array by key or default value if key not exists function.
      $handle = $gv($style, 'name');
      $src = $gv($style, 'url') ?: $this->plugin_url . $gv($style, 'path');
      $deps = $gv($style, 'deps', []);
      $ver = $gv($style, 'ver', $this->plugin_version);
      $media = $gv($style, 'media', 'all');
      wp_enqueue_style($handle, $src, $deps, $ver, $media);
    } else if (is_string($style)) wp_enqueue_style($style);
    else wp_die('Invalid style argument in enqueue_style() method of class extended with Nillkizz\PluginBase class!');
  }


  function enqueue_script($script)
  {
    if (is_string($script) && isset($this->_js_scripts[$script])) $script = $this->_js_scripts[$script];

    if (is_array($script)) {
      $gv = [$this, '_get_val'];  // Get value from array by key or default value if key not exists function.
      $handle = $gv($script, 'name');
      $src = $gv($script, 'url') ?: $this->plugin_url . $gv($script, 'path');
      $deps = $gv($script, 'deps', []);
      $ver = $gv($script, 'ver', $this->plugin_version);
      $in_footer = $gv($script, 'in_footer', false);
      wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
    } else if (is_string($script)) wp_enqueue_script($script);
    else wp_die('Invalid script argument in enqueue_script() method of class extended with Nillkizz\PluginBase class!');
  }

  function _get_val($array, $key, $default = '') // Get value from array by key or default value if key not exists function.
  {
    return isset($array[$key]) ? $array[$key] : $default;
  }

  protected function _includes()
  {
    foreach ($this->includes as $include) {
      require_once $this->plugin_path . $include;
    }
    do_action($this->plugin_name . '_includes');
  }

  protected function _add_image_sizes()
  {
    foreach ($this->image_sizes as $size) {
      add_image_size($size[0], $size[1], $size[2], $size[3]);
    }
  }
  function _enqueue_styles()
  {
    foreach ($this->css_styles as $style) {
      $this->enqueue_style($style);
    }
    do_action($this->plugin_name . '_enq_styles');

    add_action('wp_footer', function () {
      foreach (apply_filters($this->plugin_name . '_footer_enq_styles', $this->css_styles) as $style) {
        $this->enqueue_style($style);
      }
    });
  }

  function _enqueue_scripts()
  {
    foreach ($this->js_scripts as $script) {
      $this->enqueue_script($script);
    }
    do_action($this->plugin_name . '_enq_scripts');

    add_action('wp_footer', function () {
      foreach (apply_filters($this->plugin_name . '_footer_enq_scripts', $this->js_footer_scripts) as $script) {
        $this->enqueue_script($script);
      }
    });
  }
}
