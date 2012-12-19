<?php

require_once('MetaValue.php');

/**
 * Holds a metadata item which can multivalued
 */
class Meta {
	private $name = null;
	private $metaValues = array();

    /**
     * @ignore
     */
    public function addValue($xml) {
         $this->metaValues[] = new MetaValue($xml);
    }
    
    /**
     * returns the name of the metadata item
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * @ignore
     */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
     * returns the first value of the meta, or null if there no value
     * @return string
     */
    public function getValue() {
        if (count($this->metaValues)) {
            return $this->metaValues[0];
        } else {
            return null;
        }
    }
    
    /**
     * returns an array containing the list of the values
     * @return array
     */
    public function getValues() {
        return $this->metaValues;
    }
}
