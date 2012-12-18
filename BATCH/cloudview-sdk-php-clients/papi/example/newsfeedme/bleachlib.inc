<?php

/******************************************************************************
 *                               bleachlib
 *
 * Copyright (C) Xavier Roche
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the Lesser GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or any later version.
 *****************************************************************************/

function takethisclean(&$hdrs, $fp) {
  if (takethis($hdrs, $fp)) {
    art_cleanup($hdrs);
    return true;
  }
  return false;
}

function takethis(&$hdrs, $fp) {
  return takethis2($hdrs, $fp, ".");
}

function takethis2(&$hdrs, $fp, $endmark) {
  $hdrs = array();
  $gotHdrs = 0;
  $gotSig = 0;
  $lastKey = "";
  $inpos = 0;
  do {
    if (feof($fp)) {
      return false;
    }
    $line = get_line($fp);
    if ($line === false) {
      return false;
    }
    $inpos++;
    if ($line != $endmark) {
      if (!$gotHdrs && strlen($line) == 0) {
        $gotHdrs = 1;
      } else if (!$gotHdrs) {
        if ($line[0] == ' ' || $line[0] == "\t") {
          $hdrs[$lastKey] .= trim(substr($line, 1));
        }
        else if (($pos = strpos($line, ':')) > 0) {
          $key = strtolower(substr($line, 0, $pos));
          $value = trim(substr($line, $pos + 1));
          if (strlen($key) > 0 && strlen($value) > 0) {
            $hdrs[$key] = $value;
          }
          $lastKey = $key;
        }
        if (!isset($hdrs["raw_hdrs"])) {
          $hdrs["raw_hdrs"] = '';
        }
        $hdrs["raw_hdrs"] .= $line;
        $hdrs["raw_hdrs"] .= "\r\n";
      } else if (!$gotSig) {
        if ($line == "-- ") {
          $gotSig = 1;
        } else {
          if (!isset($hdrs["body"])) {
            $hdrs["body"] = '';
          }
          $hdrs["body"] .= $line;
          $hdrs["body"] .= "\r\n";
        }
      } else {
        if (!isset($hdrs["sig"])) {
          $hdrs["sig"] = '';
        }
        $hdrs["sig"] .= $line;
        $hdrs["sig"] .= "\r\n";
      }
    }
  } while($line != $endmark);
  return true;
}

function get_line($fp) {
  $line = fgets($fp, 32768);
  if ($line === false) {
    return false;
  }
  if (substr($line, -2) == "\r\n") {
    $line = substr($line, 0, strlen($line) - 2);
  }
  else if (substr($line, -1) == "\n" || substr($line, -1) == "\r") {
    $line = substr($line, 0, strlen($line) - 1);
  }
  return $line;
}

function art_cleanup(&$hdrs) {
  if (isset($hdrs['content-transfer-encoding'])
      && eregi('^base64', $hdrs['content-transfer-encoding'])) {
    $hdrs['body'] = base64_decode($hdrs['body']);
    unset($hdrs['content-transfer-encoding']);
  }
  if (isset($hdrs['newsgroups'])) {
    $hdrs['newsgroups'] = str_replace(" ", '',
                                      str_replace("\t", '',
                                                  $hdrs['newsgroups']));
  }
}

?>