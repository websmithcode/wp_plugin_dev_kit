<?php

if (!class_exists('NillkizzUtils')) {
  class NillkizzUtils
  {
    static function make_attr($name, $value)
    {
      $attr = "";
      if (!empty($value)) {
        switch ($name) {
          case 'href':
          case 'src':
            $attr = $name . '="' . esc_url($value) . '"';
            break;
          default:
            $attr = $name . '="' . esc_attr($value) . '"';
        }
      }
      return $attr;
    }

    static function array_put_before($columns, $nKey = '', $nValue = '', $before = '')
    {
      $n_columns = array();
      foreach ($columns as $key => $value) {
        if ($key == $before) {
          $n_columns[$nKey] = $nValue;
        }
        $n_columns[$key] = $value;
      }
      return $n_columns;
    }
  }
}
