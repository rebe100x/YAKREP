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

/* TODO: reconnect */

/** Strict error reporting. **/
error_reporting(E_ALL);

/** No time limit **/
set_time_limit(0);

/** Includes **/
require_once('com/exalead/papi/helper/Document.inc');
require_once('com/exalead/papi/helper/Part.inc');
require_once('com/exalead/papi/helper/PushAPIException.inc');
require_once('com/exalead/papi/helper/PushAPIFactory.inc');
require_once('com/exalead/papi/helper/PushAPI.inc');
require_once('com/exalead/papi/helper/PushAPISerializer.inc');
require_once('com/exalead/papi/helper/PushAPIv4.inc');
require_once('com/exalead/papi/helper/XMLPapiSerializer.inc');
require_once('com/exalead/papi/helper/DocumentEnumeration.inc');
require_once('com/exalead/papi/helper/ContentStream.inc');
?>
