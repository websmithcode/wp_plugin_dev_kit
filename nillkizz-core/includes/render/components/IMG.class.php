<?php

namespace Nillkizz\render\components;

require_once('Element.class.php');

class IMG extends Element
{
  /**
   * @param string|array $src
   * @param string $alt
   * @param array $attrs
   * @return void
   */
  function __construct($src, $alt = '', $attrs = [])
  {
    if (is_array($src)) $attrs = $src;
    else {
      $attrs['src'] = $src;
      $attrs['alt'] = $alt;
    }

    parent::__construct('img', $attrs, '', true);
  }

  static function from_attachment($attachment_attrs, $fallback_img_attrs = [])
  {
    $attrs = wp_parse_args($attachment_attrs, ['id' => null, 'size' => '']);
    if (isset($attrs['id']) && !empty($attrs['id'])) return self::fromHtml(wp_get_attachment_image($attrs['id'], $attrs['size']));
    if (empty($fallback_img_attrs)) return '';

    $attrs = wp_parse_args($fallback_img_attrs, ['src' => '', 'alt' => '', 'attrs' => []]);
    return new IMG($attrs['src'], $attrs['alt'], $attrs['attrs']);
  }
}
