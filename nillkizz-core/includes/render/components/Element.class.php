<?php

namespace Nillkizz\render\components;

use function PHPSTORM_META\override;

abstract class Element
{

  function __construct($tag = "div", $attrs = [], $content = '', $self_closed = false)
  {
    $this->tag = $tag;
    $this->attrs = $attrs;
    $this->content = $content;
    $this->self_closed = $self_closed;
  }

  function render($return = false)
  {
    if ($return) return $this->_ob_wrap([$this, '_render']);
    else  $this->_render();
  }

  function render_content($return = false)
  {
    // override this method
    $content = $this->content;
    if ($return) return $content;
    else echo $content;
  }

  protected function extra_attrs()
  {
    // override this method
    return [];
  }

  function _add_to_array_if_defined(&$array, $key, $value)
  {
    if (isset($value)) $array[$key] = $value;
  }

  function _render()
  {
    $attrs = wp_parse_args($this->attrs, $this->extra_attrs());
    $attrs = array_map(function ($key, $value) {
      return $key . '="' . $value . '"';
    }, array_keys($attrs), $attrs);

    $attrs = implode(' ', $attrs);

    if ($this->self_closed) echo '<' . $this->tag . ' ' . $attrs . '/>';
    else {
      echo '<' . $this->tag . ' ' . $attrs . '>';
      $this->render_content();
      echo '</' . $this->tag . '>';
    }
  }

  function _ob_wrap($cb)
  {
    ob_start();
    $cb();
    $content = ob_get_clean();
    return $content;
  }

  function __toString()
  {
    return $this->render(true);
  }

  static function fromHtml($html, $self_closed = false)
  {
    $attrs = static::getTagAttributes($html);
    $content = static::getTagContent($html);
    return new static($attrs, $content, $self_closed);
  }

  static function getTagAttributes($htmlortag, $name = false)
  {    // name=false returns all attributes as array
    $p = 0;
    $tag = false;
    $inquote = false;
    $started = false;
    $stack = '';
    $attrState = -1;  // -1:NOTHING   1:NAME 2:VALUE
    $currentAttr = false;
    $attrValuePos = -1;
    $attr = array();
    while ($p < strlen($htmlortag)) {
      $c = substr($htmlortag, $p, 1);

      if ($c == ' ' && $started && !$tag) {
        $tag = $stack;
        $stack = '';
      } else if ($started && $c == '>' && ($attrState != 2 || $inquote == ' ')) {    // END OF TAG (if not in a value, doesn't work without braces)
        $started = false;
        if ($attrState == 1 && trim($stack) != '/')
          $attr[trim($stack)] = true;
        if ($attrState == 2)
          $attr[$currentAttr] = $stack;
        break;  // DONE

      } else if ($started && $tag && $c == '=' && $attrState != 2) {          // END OF ATTR NAME, BEGIN OF VALUE
        $currentAttr = trim($stack);
        $stack = '';
        $attrState = 2;
      } else if ($started && $tag && $c == ' ' && $attrState == 1) {          // END OF ATTR NAME, BEGIN OF VALUE
        $currentAttr = trim($stack);
        $stack = '';
        $attrState = 5;
      } else if ($started && $tag && $attrState == 5) {                // CHAR AFTER SPACE AFTER ATTR NAME, BEGIN OF ANOTHER ATTR
        $attr[$currentAttr] = true;
        $currentAttr = false;
        $stack .= $c;
        $attrState = 1;
      } else if (!$started && $c == '<') {                      // BEGIN OF TAG
        $started = true;
      } else if ($started && $tag && $attrState == 2 && $c === $inquote) {      // END OF VALUE
        $attr[$currentAttr] = $stack;
        $stack = '';
        $attrState = -1;
        $inquote = false;
        $attrValuePos = -1;
      } else if ($started && $tag && $attrState == 2 && $attrValuePos == -1) {    // MIDDLE OF VALUE
        $attrValuePos = 0;
        if ($c == '\'') $inquote = '\'';
        else if ($c == '"') $inquote = '"';
        else {
          $inquote = ' ';
          $stack .= $c;
          $attrValuePos = 1;
        }
      } else if ($started && $tag && $attrState == -1) {              // BEGIN OF ATTR NAME
        $attrState = 1;
        $stack .= $c;
      } else {
        $stack .= $c;
        if ($attrState == 2) $attrValuePos++;
      }
      $p++;
    }
    return $name ? $attr[$name] : $attr;
  }

  static function getTagContent($html)
  {
    return strip_tags($html);
  }
}
