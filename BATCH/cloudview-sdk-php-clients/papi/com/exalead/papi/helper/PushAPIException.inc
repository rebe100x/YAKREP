<?php
/******************************************************************************
 *
 *                                 Cloudview
 *
 *                            PHP Push API Client Library.
 *
 *                       Copyright (c) 2009 Exalead S.A.
 *
 *****************************************************************************/

/**
 * Base class for exceptions that can be thrown by push calls.
 **/
class PushAPIException extends Exception {
  function __construct($message, $file=false, $line=0) {
    if ($file !== false) {
      parent::__construct($message . ' at ' . $file . ':' . $line);
    } else {
      parent::__construct($message);
    }
  }
}
?>