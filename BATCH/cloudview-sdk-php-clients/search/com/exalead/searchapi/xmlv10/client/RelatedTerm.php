<?php
/**
* The RelatedTerm is a component used for the navigation. It represents a term linked with the current query.
*/
class RelatedTerm {
    private $infos = array();

    /**
     * returns the state of the RelatedTerm (refined, excluded or "displayed" when refined or excluded)
     * @return int
     */
    public function getState() {
        return (int) $this->infos["state"];
    }

    /**
     * returns the id of the RelatedTerm
     * this id can be used for a refinement (must be prefixed by a "+" or "-" for an exclusion when using SearchParameter::$REFINE)
     * @return string
     */
    public function getId() {
        return (string) $this->infos["id"];
    }

    /**
     * returns the title of the related term
     * @return string
     */
    public function getTitle() {
        return (string) $this->infos["title"];
    }

    /**
     * returns an array containing informations relative to the RelatedTerm
     * Available keys (not exhaustive) :
     * "count", RelatedTerm count (number of occurrences of this related term)<br/>
     * "score", RelatedTerm score (sum of the scores of all matching hits)<br/>
     * @return array
     */
    public function getInfos() {
        return $this->infos;
    }

    /**
     * @ignore
     */
    public function setInfos($infos) {
        return $this->infos = $infos;
    }
}
