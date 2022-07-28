<?php

namespace Nillkizz\render;

$files = glob(__DIR__ . '/components/*.php');

foreach ($files as $file) {
  require_once($file);
}
