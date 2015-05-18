<?php
function args(&$argv) {
  $args = array();
  foreach($argv as $arg){
    if (preg_match('#^-{1,2}([a-zA-Z0-9]*)=?(.*)$#', $arg, $matches)) {
      $key = $matches[1];
      switch($matches[2]){
        case '':
        case 'true':
          $arg = true;
          break;
        case 'false':
          $arg = false;
          break;
        default:
          $arg = $matches[2];
      }
      $args[$key] = $arg;
    }
    else{
      $args['input'][] = $arg;
    }
  }
  return $args;
}

$args = args($argv);
