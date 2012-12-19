<?php

if (!defined('LANG_FILE_DIRECTORY'))
  die('Missing LANG_FILE_DIRECTORY configuration.');

if (!defined('DETECT_LANGUAGE'))
  define('DETECT_LANGUAGE', true);

if (!defined('DEFAULT_LANG'))
  define('DEFAULT_LANG', 'en');

if (DETECT_LANGUAGE === true) {
  if (isset($_GET['lang']))
	$_SESSION['lang'] = $_GET['lang'];

  if (isset($_SESSION['lang']))
	$lang = $_SESSION['lang'];
  else
	$lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
}

if (DETECT_LANGUAGE === true && file_exists(LANG_FILE_DIRECTORY .'/'. $lang . '.ini')) {
  $langfile = LANG_FILE_DIRECTORY.'/'.$lang.'.ini';
} else {
  $langfile = LANG_FILE_DIRECTORY.'/'.DEFAULT_LANG.'.ini';
  $lang = DEFAULT_LANG;
}

$cacheFilePath = sys_get_temp_dir() . '/' . $_SERVER['SERVER_NAME'] . $lang . '.cache';
if (file_exists($cacheFilePath) == false ||
	filemtime($cacheFilePath) < filemtime($langfile)) {

  if (!file_exists($langfile)) {
	die('Missing internationalisation file: '.$langfile);
  }

  $rawini = file_get_contents($langfile);

  $rawini = '<?php class L { '.preg_replace('/([\w_-]+) ?= ?(.*)/', 'const \1 = \2;', $rawini).' }';
  file_put_contents($cacheFilePath, $rawini);
}

require_once $cacheFilePath;
