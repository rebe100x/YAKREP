<?php
function millitime() {
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float)$usec)/1000.0;
}
$gtick = array();
$gtick['time'] = millitime();
$gtick['ticks'] = array();
function tickSet($name) {
  global $gtick;
  $t = millitime();
  $gtick['ticks'][$name] = $t - $gtick['time'];
  $gtick['time'] = $t;
}
function tickShow() {
  global $gtick;
  foreach($gtick['ticks'] as $k => $v) {
    echo $k . ": " . round($v, 6) . " s.\n";
  }
}
?>