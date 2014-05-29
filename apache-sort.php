#!/usr/bin/php
<?php
require_once('./config.inc');
if(empty($args['type']) || empty($field[$args['type']]) ){
  echo "  Usage:\n    --type=[ip|referer|vhost|path] --vhost=[yourdomain] --tail=20\n    --type=[ip|referer|vhost|path] --tail=20\n";
  exit;
}

if(isset($field[$args['type']])){
  $awk = $field[$args['type']];
  if($args['type'] == 'referer'){
    $awk = '-F\" '."'{print $".$awk."}'";
  }
  else{
    $awk = "'{print $".$awk."}'";
  }
  if(!empty($args['vhost'])){
    $cmd = "cat {$apachelog} | grep '^{$args['vhost']}' | awk {$awk} | sort | uniq -c | sort -n | tail -{$tail}";
  }
  else{
    $cmd = "cat {$apachelog} | awk {$awk} | sort | uniq -c | sort -n | tail -{$tail}";
  }
  // echo $cmd;
  echo `{$cmd}`;
}

