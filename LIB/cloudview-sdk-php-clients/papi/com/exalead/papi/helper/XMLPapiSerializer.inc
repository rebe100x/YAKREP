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

class XMLPapiSerializer {
  function __construct($data) {
    $this->data = $data;
  }
  
  private function mapXMLstartElement($parser, $name, $attrs) {
    $this->name = $name;
    $this->xmlobj[$name] = '';
  }
  
  private function mapXMLendElement($parser, $name) {
  }
  
  private function mapXMLcontentElement($parser, $data) {
    $this->xmlobj[$this->name] .= $data;
  }
  
  public function getObject() {
    $this->xmlobj = array();
    $xml_parser = xml_parser_create();
    xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);
    xml_set_object($xml_parser, $this);
    xml_set_element_handler($xml_parser,
                            array(&$this, 'mapXMLstartElement'),
                            array(&$this, 'mapXMLendElement')
                            );
    xml_set_character_data_handler($xml_parser, 'mapXMLcontentElement'); 
    if (!xml_parse($xml_parser, $this->data, true)) {
      $api_error = sprintf("XML error: %s at line %d",
                           xml_error_string(xml_get_error_code($xml_parser)),
                           xml_get_current_line_number($xml_parser));
    }
    xml_parser_free($xml_parser);
    if (isset($api_error)) {
      throw new PushAPIException('Invalid PAPI reply: ' . $api_error);
    }
    $xmlobj = $this->xmlobj;
    unset($this->xmlobj);
    return $xmlobj;
  }

  protected $name;
  protected $data;
  protected $xmlobj;
}
?>