<?php
/**
 * Holds of the value of metadata item which can highlighted or not
 */
class MetaValue {
	private $highlighted = false;
	private $value = "";
	private $highlights = array();
  	private $data = '';

    /**
     * @ignore
     */
    public function __construct($xml, $version = null) {
        $this->parse($xml);
    }

    /**
     * @ignore
     */
    private function parse($xml) {
        $xml_parser = xml_parser_create();
        xml_set_object($xml_parser, $this);
        xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);
        xml_set_element_handler($xml_parser, array(&$this, "startElement"), array(&$this, "endElement"));
        xml_set_character_data_handler($xml_parser, array(&$this, "characterData"));
//        xml_set_default_handler($xml_parser, array(&$this, "defaultHandler"));

        if (!xml_parse($xml_parser, $xml, true)) {
            echo 'MetaValue error: ' . xml_error_string($xml_parser).'<br>' .
                 'Error line: ' . xml_get_current_line_number($xml_parser).'<br>' .
                 'Byte number error: ' . xml_get_current_byte_index($xml_parser) .'<br><br>';
        }

        xml_parser_free($xml_parser);
    }

    /**
     * @ignore
     */
    private function startElement($parser, $name, $attrs)
    {
        switch($name)
        {
            case "MetaString":
            case "MetaText":
                $this->data = '';
                break;

            case "TextSeg":
                if ($attrs["highlighted"] == "true")
                {
                    $this->highlighted = true;
                    $this->tmpHighlighted = true;
                }
                $this->data = "";
                break;
        }
    }


    /**
     * @ignore
     */
    private function endElement($parser, $name)
    {
      	switch($name)
        {
            case "MetaString":
                $this->value = $this->data;
                break;
            case "TextSeg":
                if ($this->tmpHighlighted)
                {
                    $this->highlights[] = $this->data;
                    $this->tmpHighlighted = false;
                }
                $this->value .= $this->data;
                break;
            case "TextCut":
                $this->value .= "...";
                break;
        }
    }

    /**
     * @ignore
     */
    private function characterData($xmlparser, $cdata)
    {
        $this->data .= $cdata;
    }

//    private function defaultHandler($xmlParser, $cdata)
//    {
//        echo 'MetaValue defaultHandler: ' . $cdata . "\n";
//    }

    /**
     * returns an array of strings representing the highlighted value of the metadata item value. If the value does not contain highlighting informations, this method will return an array with a single string containing the flat value
     * @return array
     */
    public function getHightlightedText()
    {
        if (count($this->highlights)) {
        	return $this->highlights;
        } else {
        	return $this->value;
        }
	}

    /**
     * @return string the string value of the metadata item value (returns a flattened value for highlighted value)
     */
	public function getStringValue()
    {
		return $this->value;
	}

	/**
	 * @return boolean whether the metadata item value is highlighted or not
	 */
	public function isHighlighted()
    {
		return $this->highlighted;
	}
}
