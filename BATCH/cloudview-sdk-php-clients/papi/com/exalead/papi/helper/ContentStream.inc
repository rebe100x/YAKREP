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
 * Content Stream interface for Part.
 */
interface ContentStream {
  /**
   * Get data.
   * @returns data, as bytes.
   * Note: this function is called once by the framework to serialize data.
   **/
  public function data();
  
  /**
   * Get the data size.
   * @returns data size, in bytes, as an integer.
   **/
  public function size();
}

?>