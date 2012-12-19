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
 * Factory for creating push clients. Given the protocol supported by the push
 * server, this factory will create the appropriate push client.
 **/
class PushAPIFactory {
  /**
   * Create a new push client instance.
   * @param host the host name of the push server
   * @param port the port number of the push server 
   * @param connectorName the name of the connector to be used. For more information on connectors, refer to the Exalead CloudView documentation set.
   * @param connectorType the type of the connector to be used (mainly used for license checking purpose;  the available connector types are described in your product's license)
   * @param login identification login to be used if the target connector is secured
   * @param password password associated with the identification login
   * @return an new push client instance.
   * @throws PushAPIException upon error
   **/
  static function createHttp($host, $port,
                             $connectorName='mysource', $connectorType='papi',
                             $login=false, $password=false) {
    return new PushAPIv4($host, $port, $connectorName, $connectorType,
                         $login=false, $password=false);
  }
}

?>