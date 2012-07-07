<?php
require_once('Hit.php');
require_once('CategoryGroup.php');
require_once('SpellCheckSuggestion.php');
require_once('RelatedTerm.php');

/**
 * SearchAnswer is a simplified view of a XMLV10 Answer
 * SearchAnswer instances are obtained via a SAX parsing of the XMLV10 Answer
 * The "results" are available via getHits() and the "navigation" via getRelatedTerms() and getCategoryGroups() 
 */
class SearchAnswer
{
    private $hits = array();
    private $categoryGroups = array();
    private $relatedTerms = array();
    private $spellCheckSuggestions = array();
    private $times = array();
    private $infos = array();
    private $args = array();

    /**
     * @ignore
     */
    public function setInfos($infos)
    {
        $this->infos = $infos;
    }

    /**
     * @ignore
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }

    public function getArg($name)
    {
        return $this->args[$name];
    }

    /**
     * @ignore
     */
    public function setTimes($times)
    {
        $this->times = $times;
    }

    /**
     * @ignore
     */
    public function setSpellCheckSuggestion(SpellCheckSuggestion $suggestion)
    {
        $this->spellCheckSuggestions[] = $suggestion;
    }

    /**
     * @ignore
     */
    public function setRelatedTerm(RelatedTerm $term)
    {
        $this->relatedTerms[] = $term;
    }

    /**
     * @ignore
     */
    public function addHit(Hit $hit)
    {
        $this->hits[] = $hit;
    }

    /**
     * @ignore
     */
    public function addCategoryGroup(CategoryGroup $catgroup)
    {
        $this->categoryGroups[] = $catgroup;
    }

    /**
     * returns a CategoryGroup defined by its name
     * @param $name - the CategoryGroup id
     * @return CategoryGroup
     */
    public function getCategoryGroup($name)
    {
        if (array_key_exists($name, $this->categoryGroups))
        {
        	return $this->categoryGroups[$name];
        }
    }

    /**
     * returns an array of categoryGroup which contains category tree
     * @return array 
     */
    public function getCategoryGroups()
    {
        return $this->categoryGroups;
    }
  
    /**
     * returns an array containing search timing informations
     * Available keys :<br>
     * "interrupted", values: "1" (if a timeout has been reached) or "0"<br>
     * "parse" (query parsing time), value is a long<br>
     * "synthesis", value is a long<br>
     * "cats", value is a long<br>
     * "kwds", value is a long<br>
     * "spell", value is a long<br>
     * "exec", value is a long<br>
     * "overall", value is a long<br>
     * "sliceTime.i", where "i" is slice number, value is a long<br>
     * @return array
     */
    public function getTimes()
    {
        return $this->times;
    }

    /**
     * returns an array containing informations relative to the Answer
     * Available keys :<br>
     *   "context", Search context when contains query settings and can be used for the next queries<br>
     *   "nmatches", Total number of documents matching the query. (may be estimated, see below)<br>
     *   "estimated", Is the total number of documents matching the query an estimation.<br>
     *   "nhits", Number of hits potentially available to the user (not estimated).<br>
     *   "nslices", Number of slices actually used.<br>
     *   "start", First hit index.<br>
     *   "last", Last document identifier examined.<br>
     * @return array
     */
    public function getInfos()
    {
        return $this->infos;
    }
  
    /**
     * returns the results that have matched the query
     * @return array
     */
    public function getHits()
    {
  	    return $this->hits;
    }

    /**
     * returns a array of spell suggestions relative to the query
     * @return array
     */
    public function getSpellCheckSuggestions()
    {
        return $this->spellCheckSuggestions;
    }

    /**
     * returns an array of terms linked with the query. These are part of the navigation as their id are used for refinements.
     * @return array
     */
    public function getRelatedTerms()
    {
        return $this->relatedTerms;
    }
}
