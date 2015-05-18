#!/usr/bin/php
<?php
require_once('./config.inc');
if(empty($args['type']) || empty($field[$args['type']]) ){
  echo "  Usage:
    --type=[ip|referer|vhost|path] --vhost=[yourdomain] --tail=20
    --type=[ip|referer|vhost|path] --log=/var/log/nginx/access.log.1
    --type=[ip|referer|vhost|path] --tail=20
    --type=[ip|referer|vhost|path] --head=20
";
  exit;
}

if(isset($field[$args['type']])){
  $apachelog = $args['log'] ? $args['log'] : $apachelog;
  $fqdnip = `hostname -i`;
  $fqdnip = trim($fqdnip);
  $localip = '127.0.0.1';

  $awk = $field[$args['type']];
  if($args['type'] == 'referer'){
    $awk = '-F\" '."'{print $".$awk."}'";
  }
  else{
    $awk = "'{print $".$awk."}'";
  }
  if(!empty($args['vhost'])){
    $cmd = " | grep '^{$args['vhost']}' | awk {$awk} | sort | uniq -c | sort -n | tail -20";
  }
  else{
    $cmd = " | awk {$awk} | sort | uniq -c | sort -n | tail -20";
  }
  $cmd = " | grep -v '{$fqdnip}\|127.0.0.1'".$cmd;

  if($args['tail']){
    $cmd = "tail -{$args['tail']} ${apachelog}".$cmd;
  }
  elseif($args['head']){
    $cmd = "head -n {$args['head']} ${apachelog}".$cmd;
  }
  else{
    $cmd = "cat ${apachelog}".$cmd;
  }

  echo "Executing...:\n".$cmd."\n";

  // echo $cmd;
  echo `{$cmd}`;
}

