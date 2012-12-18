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
 * Push API Version 4 implementation.
 * 
 * Document enumeration interface.
 */
interface DocumentEnumeration {
  /**
   * Next element
   * @returns false if the enumeration is finished
   **/
  public function next();
  
  /**
   * Called on start
   **/
  public function start();

  /**
   * Called on ending
   **/
  public function end();
}

?>