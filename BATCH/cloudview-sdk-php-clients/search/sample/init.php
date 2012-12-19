<?php

set_include_path('../lib:./templates:' . get_include_path());
define('LANG_FILE_DIRECTORY', dirname(__FILE__) . '/lang'); // Directory containing .ini files
define('DEBUG', false);
define('RESULTS_PER_PAGE', 10);
include_once('light-i18n.php');
include_once('helpers.php');
