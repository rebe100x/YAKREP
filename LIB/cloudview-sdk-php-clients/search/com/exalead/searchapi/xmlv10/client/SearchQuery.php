<?php
require_once('SearchParameter.php');

/**
 * SearchQuery holds the different search settings that are used to configure your search
 * The SearchQuery can be configured using the different setters or by adding SearchParameters using the method addParameter. (The way can be more be more convenient if you build your SearchQuery from an HTTP request or any key/value structure.
 * Note that the different settings are stored as a list of SearchParameter in the SearchQuery even when using the different setters of the SearchQuery. For example the method setQuery() adds a SearchParameter::$QUERY parameter with the given value.
 * To execute a search, use a SearchClient that can be obtained through the SearchClientFactory 
 *
 */
class SearchQuery {
    /**
     * @param string $query query string
     */
    public function __construct($query = null)
    {
        if ($query)
        {
            $this->setQuery($query);
        }
    }

  	/**
	 * Sets the query string (equivalent to {@link SearchParameter::$QUERY})
	 * Example: "exalead OR author:bourdoncle"
	 * (see Query Language Guide for more details)
	 * @param string $query query string
	 * @see SearchParameter::$QUERY
	 */
	public function setQuery($query){
		$this->setStandardParameter(SearchParameter::$QUERY, query);
	}

	/**
	 * Returns the query string
	 * 
	 * @see SearchParameter::$QUERY
     * @return string
	 */
	public function getQuery()
    {
		return $this->getParameterValue(SearchParameter::$QUERY);
	}

	/**
	 * Sets the query language
	 * 
	 * @param string $lang a langId (ex: "en", "fr")
	 * @see SearchParameter::$LANG
	 */
	public function setLanguage($lang){
		$this->setStandardParameter(SearchParameter::$LANG, $lang);
	}

	/**
	 * Returns the query language
	 * @see SearchParameter::$LANG
	 */
	public function getLanguage(){
		
		return $this->getParameterValue(SearchParameter::$LANG, true);
	}

	/**
	 * Set the index of the first result to retrieve
	 * 
	 * @param int $startIndex index of the first result (starts from 0)
	 * @see SearchParameter::$START
	 */
	public function setResultsSetStart($startIndex){
		
		$this->setStandardParameter(SearchParameter::$START, (string) $startIndex);
	}

	/**
	 * Returns the index of the first result to retrieve
	 * 
	 * @see SearchParameter::$START
	 */
	public function getResultsSetStart(){

		$start = $this->getParameterValue(SearchParameter::$START, true);
		if(start == null){
			return 0;
		}
		
		return (int) $start;
	}

	/**
	 * Sets the number of results that will be retrieved
	 * 
	 * @see SearchParameter::$NRESULTS
	 */
	public function setResultsSetLength($length){
		
		$this->setStandardParameter(SearchParameter::$NRESULTS, (int) $length);
	}

	/**
	 * Returns the number of results that will be retrieved
	 * 
	 * @see SearchParameter::$NRESULTS
	 */
	public function getResultsSetLength(){
		
		$length = $this->getParameterValue(SearchParameter::$NRESULTS, true);
		if($length == null){
			return 0;
		}

		return (int) $length;
	}

	/**
	 * Sets the field on which the results will be sorted on
	 * Note that you can't sort on alphanumerical and category fields
	 * 
     * Warning the second parameter is not supported on the 4.6 product
     *
	 * @param string $fieldName the name of a field (that can be used for sorting)
	 * @param boolean $isSecondary wether the sort is secondary or not. When the sort is secondary it is only applied to the results with the same score.
	 * @see SearchParameter::$SORT
	 */
	public function setSortField($fieldName, $isSecondary = null){
		
        $string = $fieldName;
        if ($isSecondary !== null)
        {
            $string .= '::' . ($isSecondary ? "sec" : "");
        }
		$this->setStandardParameter(SearchParameter::$SORT, $string);
	}
	
	/**
	 * Returns the field on which the results will be sorted
	 * Note that when using {@link SearchQuery::setSortField()} with the second parameter $isSecondary, the syntax of the returned 
	 * string is the following one: 
	 * "sortedFiled::" or "sortedFiled::sec" if the sort is secondary 
	 * 
	 * @see SearchParameter::$SORT
	 */
	public function getSortField(){
		
		return $this->getParameterValue(SearchParameter::$SORT, true);
	}
	
	/**
	 * Sets whether the sort is ascending or not
	 * @param boolean $isAscending
	 * @see SearchParameter::$SORT_ASCENDING
	 */
	public function setAscendingSort($isAscending){
		
		$this->setStandardParameter(SearchParameter::$SORT_ASCENDING, $isAscending ? "1" : "0");
	}

	/**
	 * Returns whether the sort is ascending or not, assuming the {@link SearchQuery::setAscendingSort()} method or {@link SearchParameter::$SORT_ASCENDING} parameter have been used
	 * 
	 * @see SearchParameter::$SORT_ASCENDING
	 */
	public function isSortAscending(){
		
		$value = $this->getParameterValue(SearchParameter::$SORT_ASCENDING, true); 
		
		if($value == null || $value == "0"){
			return false;
		}
		else{
			return true;
		}
		 
	}
	
	/**
	 * Sets the logic (default query) that will be used
	 * 
	 * @param string $name a logic name (5.0) or query name (4.6)
	 * @see SearchParameter::$LOGIC
	 */
	public function setSearchLogic($name){
	
		$this->setStandardParameter(SearchParameter::$LOGIC, $name);
	}
	
	/**
	 * Returns the logic (default query) that will be used
	 * 
	 * @see SearchParameter::$LOGIC
	 */
	public function getSearchLogic(){
		
		return $this->getParameterValue(SearchParameter::$LOGIC, true);
	}
	
	/**
	 * Sets the search context.
	 * This context is available in the SearchAnswer via {@link SearchAnswer::getInfos()}, (the key is "context")
     *
	 * The search context allows to chain queries easily without having to keep the parameters used in the previous queries.
     *
	 * @param string $context 
	 * @see SearchAnswer::getInfos()
	 * @see SearchQuery::setSearchContext(String)
     * @see SearchParameter::$CONTEXT
	 */
	public function setSearchContext($context)
    {
		$this->setStandardParameter(SearchParameter::$CONTEXT, $context);
	}

	/**
	 * Returns the search context (if it has been set)
	 * (equivalent to {@link SearchParameter::$CONTEXT})
	 * 
	 * @see SearchParameter::$CONTEXT
	 */
	public function getSearchContext()
    {
		return $this->getParameterValue(SearchParameter::$CONTEXT, true);
	}
	
	/**
	 * Returns the current refinements
	 *Excluded refinements are prefixed with "-" others are prefixed with "+"
	 * 
	 * @see SearchParameter::$REFINE
	 */
	public function getRefinements()
    {
		return $this->getParameterValues(SearchParameter::$REFINE, true);
	}
	
	/**
	 * Adds a refinement
	 * A refinement is way to precise your query by adding restriction to
	 * a set of categories related terms. A refinement can be a selection
	 * or an exclusion of a category (or related term).
	 * 
	 * @param string $refId the id of a category or related term {@link Category::getId()}, {@link RelatedTerm::getId()}
	 * @see SearchParameter::$REFINE
	 * 
	 */
	public function addRefinement($refId, $excluded)
    {
		$refs = $this->getRefinements();
		
		$refId = ($excluded ? "-" : "+") + $refId;
		
		$found = false;
		foreach($refs as $ref)
        {
			if($ref == $refId){
				$found = true;
				break;
			}
		}

		if(!$found)
			$this->standardParameters[] = new SearchParameter(SearchParameter::$REFINE, $refId);
	}
	
	/**
	 * Removes a refinement from the list of refinements
	 * Warning, on the 4.6 product, the refinements defined in the context can not be removed with this method, see {@link SearchParameter::$ZAP_REFINE} instead
	 * 
	 * @param string $refId the id of the category
	 * @see SearchParameter::$REFINE
	 * @see SearchParameter::$CANCEL_REFINE
	 */
	public function cancelRefinement($refId)
    {
		$found = false;
		foreach ($this->standardParameters as $param){
			
			if($param.getName() == SearchParameter::$REFINE){
				if($param.getValue().substring(1) == $refId){
					$found = true;
					continue;
				}
			}
			$filteredParams[] = $param;
		}

		$this->standardParameters = $filteredParams;

		if(!$found) {
			$this->standardParameters[] = new SearchParameter(SearchParameter::$CANCEL_REFINE, $refId);
		}
	}
	
	/**
	 * Returns the current security tokens
	 * 
	 * @see SearchParameter::$SECURITY
	 */
	public function getSecurityTokens()
    {
		return $this->getParameterValues(SearchParameter::$SECURITY, true);
	}
	
	/**
	 * Adds a security token
	 * Security tokens are pushed by connectors thanks to the meta "security"
	 * When security token check is enabled on the search server, the documents
	 * must have the provided security tokens to be retrieved. Note that the token "EVERYBODY" is
	 * automatically added so there is no need to add it in your query.<br>
	 * (equivalent to {@link SearchParameter::$SECURITY})
	 * 
	 *  @see SearchParameter::$SECURITY
	 */
	public function addSecurityToken($token) {
		
		$refs = $this->getSecurityTokens();
		
		$found = false;
		foreach($refs as $ref){
			
			if($ref == $token){
				$found = true;
				break;
			}
		}
		
		if(!$found)
			$this->standardParameters[] = new SearchParameter(SearchParameter::$SECURITY, $token);
		
	}
	
	/**
	 * Removes a security token from the list of security tokens
	 * @param string $token
	 * @see SearchParameter::$SECURITY
	 */
	public function removeSecurityToken($token){
		
		foreach ($this->standardParameters as $param){
			
			if($param.getName() == SearchParameter::$SECURITY){
				if($param.getValue() == $token){
					continue;
				}
			}
			$filteredParams[] = $param;
		}
		
		$this->standardParameters = $filteredParams;
	}
	
	/**
	 * Retrieve a meta from the specified index field. (does not modify the search logic)
	 * 
	 * @param metaName (mandatory)
	 * @param indexField (mandatory)
	 * @param highlighting sets the meta should be highlighted or not
	 * @param maxLength sets the maximum length of the meta, optional, if missing default value will be used
	 * 
	 * @see SearchParameter::$ADD_HIT_META
	 */
	public function addMetaFromField($metaName, $indexField, $highlighting, $maxLength) {

        $string = $metaName . '@' . $indexField . ":" . ($highlighting ? "1" : "0") . ":" . ($maxLength != null ? (string) $maxLength : "");
		$this->standardParameters[] = new SearchParameter(SearchParameter::$ADD_HIT_META, $string);
		
	}
	
	/**
	 * Retrieve a summarized meta from the specified index field. (does not modify the search logic)
     * 
	 * Warning, this feature is not currently supported on the 4.6 product
	 * 
	 * @param metaName (mandatory)
	 * @param indexField (mandatory)
	 * @param highlighting sets the meta should be highlighted or not
	 * @param maxLength sets the maximum length of the meta, optional, if missing default value will be used
	 * 
	 * @see SearchParameter::$ADD_HIT_META
	 */
	public function addSummaryMetaFromField($metaName, $indexField, $highlighting, $maxLength) {
		
        $string = $metaName . ':summary@' . $indexField . ":" . ($highlighting ? "1" : "0") . ":" . ($maxLength != null ? (string) $maxLength : "");
		$this->standardParameters[] = new SearchParameter(SearchParameter::$ADD_HIT_META, $string);
	}
	
	/**
	 * Retrieve a list of meta from the specified index field which content is CSV encoded. (does not modify the search logic)
	 * 
	 * Warning, this feature is not currently supported on the 4.6 product
	 * 
	 * @param array $metaNames a list of meta name (mandatory)
	 * @param string $indexField (mandatory) index field name from which the metas should be retrieved
	 * @param array $originalNames list of original names, if null metaNames list will be used
	 * @param array $highlighting sets the meta should be highlighted or not
	 * @param array $maxLength sets the maximum length of the meta, optional, if missing default value will be used
	 * 
	 * @see SearchParameter::$ADD_HIT_META
	 */
	public function addMetasFromCSVField($metaNames, $indexField, $originalNames = null, $highlighting = null, $maxLength = null) {
		
		$string = "";
		
		$string .= '<';
		
		$first = true;
		foreach ($metaNames as $meta) {
			if ($first) {
				$first = false;
			}
			else {
				$string .= ',';
			}
			$string .= $meta;
		}
		
		$string .= '>';
		
		$string .= '@' . $indexField;
		
		if ($originalNames!= null && count($originalNames) > 0) {
			
			$string .= '<';
			
			$first = true;
			foreach ($originalNames as $name) {
				if ($first) {
					$first = false;
				}
				else {
					$string .= ',';
				}
				$string .= $name;
			}
			
			$string .= '>';
			
		}
		
		$string .= ':' . ($highlighting ? "1" : "0");
		if ($maxLength != null) {
			
			$string .= ':' .$maxLength;
		}
		
		$this->standardParameters[] = new SearchParameter(SearchParameter::$ADD_HIT_META, $string);
		
	}
	
	/**
	 * Remove a hit meta that can be defined in the search logic (the search logic is not modified) or within this SearchQuery
	 *
	 * Warning, this feature is not currently supported on the 4.6 product
	 * 
	 * @param string $metaName name of the meta to remove
	 * 
	 * @see SearchParameter::$REMOVE_HIT_META
	 */
	public function removeMeta($metaName) {
		
		$this->standardParameters[] = new SearchParameter(SearchParameter::$REMOVE_HIT_META, $metaName);
	}
	
    
	/**
	 * 
	 * Adds a new category group in the search results. Does not modify the search logic, this setting is only used
	 * for the current query.
	 * 
	 * <p>
	 * Warning, this feature is not currently supported on the 4.6 product
	 * 
	 * @param name category group name
	 * @param root root path of the category group, ex: "Top/Source"
	 * @param directory name of category field (optional)
	 * @param inHits configures whether the categories should be retrieved or not 
	 * @param maxCategories maximum number of categories in the group (optional) 
	 * @param maxCategoryTreeDepth maximum (optional)
	 * @param maxCategoriesPerLevel (optional)
	 * @param sortFunction supported values are: "count", "relevancy", "alphanum", "num", "date" (optional)
	 * 
	 * @see SearchParameter::$ADD_CATEGORY_GROUP
	 */
	public function addCategoryGroup($name, $root, $directory, $inHits, $maxCategories, $maxCategoryTreeDepth, $maxCategoriesPerLevel, $sortFunction) {
		
		$string = $name . "@" .$root;
		
		if ($directory != null) {
			$string .= "@" . $directory;
		}
		
        $string .= ':' . ($inHits ? "1" : "0") . ':' . ($maxCategories != null ? (string) $maxCategories : "") . ":";
        $string .= ($maxCategoryTreeDepth != null ? (string) $maxCategoryTreeDepth : "") . ":";
        $string .= ($maxCategoriesPerLevel != null ? (string) $maxCategoriesPerLevel : "") . ":";
        $string .= ($sortFunction != null ? $sortFunction : "");

		$this->standardParameters[] = new SearchParameter(SearchParameter::$ADD_CATEGORY_GROUP, $string);
		
	}

	/**
	 * Removes a Category Group that may have been defined in the search logic or with {@link SearchQuery::addCategoryGroup()}
	 *
	 * Warning, this feature is not currently supported on the 4.6 product
	 * 
	 * @param string $name the name of the category group the remove for this query
	 * @see SearchParameter::$REMOVE_CATEGORY_GROUP
	 */
	public function removeCategoryGroup($name) {
		
		$this->removeSearchParameter(new SearchParameter(SearchParameter::$REMOVE_CATEGORY_GROUP, $name));
	}
	
	
	/**
	 * Add a virtual which can then be used for the sorting 
	 * or be retrieved using {@link SearchQuery#addMetaFromField(String, String, boolean, Integer)}
	 *
	 * Warning, this feature is not currently supported on the 4.6 product
	 * 
	 * @param string $name virtual field name
	 * @param string $expression
	 * 
	 * @see SearchParameter::$ADD_VIRTUAL_FIELD
	 */
	public function addVirtualField($name, $expression) {

		$paramValue = $name . ':' . $expression;

		$this->addSearchParameter(new SearchParameter(SearchParameter::$ADD_VIRTUAL_FIELD, $paramValue));
	}
	
	/**
	 * Note that only the virtual field definition will be removed, if the field is retrieved as the meta, 
	 * the meta must be also be removed.
     *
	 * Warning, this feature is not currently supported on the 4.6 product
	 * 
	 * @param string $name name of the virtual field definition to remove
	 * 
	 * @see SearchParameter::$REMOVE_VIRTUAL_FIELD
	 */
	public function removeVirtualField($name)
    {
		$this->removeSearchParameter(new SearchParameter(SearchParameter::$REMOVE_VIRTUAL_FIELD, $name));
	}
	
	/**
	 * Sets the static ranking expression to use for this query
     *
	 * Warning, this feature is not currently supported on the 4.6 product
     *
	 * @param string $expression static ranking expression
	 * @see SearchParameter::$STATIC_RANKING_EXPRESSION
	 */
	public function setStaticRankingExpression($expression)
    {
		$this->setStandardParameter(SearchParameter::$STATIC_RANKING_EXPRESSION, $expression);
	}
	
	/**
	 * Remove the static ranking expression for the current query
	 * 
	 * Warning, this feature is not currently supported on the 4.6 product
	 * 
	 * @see SearchParameter::$STATIC_RANKING_EXPRESSION
	 */
	public function unSetStaticRankingExpression()
    {
		$this->setStandardParameter(SearchParameter::$STATIC_RANKING_EXPRESSION, "");
	}
	
	/**
	 * 
	 * Sets the query timeout
	 * 
	 * Warning, this feature is not currently supported on the 4.6 product
	 * 
	 * @param int millis query timeout in milliseconds
	 * 
	 * @see SearchParameter::$TIMEOUT
	 */
	public function setTimeout($millis)
    {

		if ($millis != null)
        {
			$this->setStandardParameter(SearchParameter::$TIMEOUT, (string) $millis);
		}
		else
        {
			$this->removeParameter(SearchParameter::$TIMEOUT);
		}
	}

	/**
	 * 
	 * Returns the query timeout
     *
	 * Warning, this feature is not currently supported on the 4.6 product
     *
	 * @return the query timeout (milliseconds) if available
	 * @see SearchParameter::$TIMEOUT
	 */
	public function getTimeout() {

		$value = $this->getParameterValue(SearchParameter::$TIMEOUT, true);
		if($value == null){
			return null;
		}

		return (int) $value;
	}
	
	/**
	 * 
	 * Allows to configure the collapsing
	 * 
	 * Warning, this feature is not currently supported on the 4.6 product
	 * 
	 * @param boolean $lsb use the first 32 bits of the field
	 * @param boolean $msb use the bits from 33 to 64 of the field
	 * @see SearchParameter::$COLLAPSING
	 */
	public function setCollapsing($lsb, $msb) {

		$value = "disabled";

		if ($lsb && $msb) {
			$value = "enabled";
		}
		else if($lsb) {
			$value = "lsb_only";
		}
		else if($msb) {
			$value = "msb_only";
		}

		$this->setStandardParameter(SearchParameter::$COLLAPSING, $value);
	}

	/**
	 * 
	 * Warning, this feature is not currently supported on the 4.6 product
	 * 
	 * @return collapsing state : "disabled", "lsb_only", "msb_only", "enabled" 
	 * 
	 * @see SearchParameter::$COLLAPSING
	 */
	public function getCollapsing() {
		
		return $this->getParameterValue(SearchParameter::$COLLAPSING, true);

	}
	
	
	/**
	 * Adds a search parameter which is composed by a name and a value.
	 * 
	 * Using search parameters instead of the different setters on the SearchQuery may be
	 * convenient when the query is built form http parameters
	 * 
	 * Standard parameter names are static fields of the class SearchParameter 
	 * (ex: {@link SearchParameter::$LANG}). (Note that these names are then translated to the
	 * corresponding parameters in the selected api version (4.6 or 5.0)
	 * 
	 * @param string $name the name of search parameter
	 * @param mixed $value
	 * 
	 * @see SearchParameter
	 */
	public function addParameter($name, $value){
		
		$this->addSearchParameter(new SearchParameter($name, $value));
	}
	
	/**
	 * Adds a search parameter to your query.
	 * 
	 * Using search parameters instead of the different setters on the SearchQuery may be
	 * convenient when the query is built form http parameters
	 * 
	 * Standard parameter names are static fields of the class SearchParameter 
	 * (ex: @link{SearchParameter::$LANG}). (Note that these names are then translated to the
	 * corresponding parameters in the selected api version (4.6 or 5.0)
	 * 
	 * @param SearchParameter $param
	 * 
	 * @see SearchParameter
	 */
	public function addSearchParameter(SearchParameter $param)
    {
		$isStandard = SearchParameter::isStandard($param->getName());

		if($isStandard){

			$paramKey = SearchParameter::standardParameterKey($param->getName());

			if(SearchParameter::allowMultipleValues($paramKey)){
				$this->standardParameters[] = $param;
			}
			else{
				$this->setStandardParameter($param->getName(), $param->getValue());
			}
			
		}
		else{
			$this->notStandardParameters[] = $param;
		}
		
	}

	/**
	 * Removes the parameter(s) with the given name
	 * if several parameters have the same name they all will be removed
	 * 
	 * @param string $name
	 * @see SearchParameter
	 */
	public function removeParameter($name)
    {
		if(SearchParameter::isStandard($param->getName()))
        {
            foreach ($this->standardParameters as $i => $p)
            {
                if ($p->getName() == $param->getName())
                {
                    unset($this->standardParameters[$i]);
                }
            }
		}
		else
        {
            foreach ($this->notStandardParameters as $i => $p)
            {
                if ($p->getName() == $param->getName())
                {
                    unset($this->notStandardParameters[$i]);
                }
            }
		}
	}
	
	/**
	 * Removes the specified parameter
	 * @param SearchParameter $param the specified parameter
	 * @see SearchParameter
	 */
	public function removeSearchParameter(SearchParameter $param){

		if(SearchParameter::isStandard($param->getName()))
        {
            foreach ($this->standardParameters as $i => $p)
            {
                if ($p->getName() == $param->getName() && $p->getValue() == $param->getValue())
                {
                    unset($this->standardParameters[$i]);
                }
            }
		}
		else
        {
            foreach ($this->notStandardParameters as $i => $p)
            {
                if ($p->getName() == $param->getName() && $p->getValue() == $param->getValue())
                {
                    unset($this->notStandardParameters[$i]);
                }
            }
		}
	}
	
	/**
	 * Returns the parameter with the given name
	 * if several search parameters have the same name only
	 * the first one will be returned
     *
	 * @param string $name
	 * @see SearchParameter
	 */
	public function getParameter($name){
		
		return $this->findParameter($name);
	}
	
	/**
	 * Returns all the parameters with the given name or all parameters if no name is given
	 * @param string $name
	 * @see SearchParameter
	 * 
	 */
	public function getParameters($name = null){
        if ($name === null)
        {
            return array_merge($this->standardParameters, $this->notStandardParameters);
        }
        else
        {
            return $this->findParameters($name);
        }
	}

	private function findParameters($name, $isStandard){
		
		if (is_null($isStandard))
        {
			$isStandard = SearchParameter::isStandard($name);
        }

		if($isStandard)
        {
			$paramList = $this->standardParameters;
		}
		else
        {
			$paramList = $this->notStandardParameters;
		}

		$results = array();

		foreach($paramList as $param)
        {

			if($name == $param->getName())
            {
				$results[] = $param;
			}

		}

		return $results;

	}

	private function findParameter($name, $isStandard = null){

		if (is_null($isStandard))
        {
			$isStandard = SearchParameter::isStandard($name);
        }

		if($isStandard)
        {
			$paramList = $this->standardParameters;
		}
		else
        {
			$paramList = $this->notStandardParameters;
		}

		foreach($paramList as $param){

			if($name == $param->getName()){
				return $param;
			}

		}

		return null;
	}

	private function getParameterValue($name, $isStandard){

		$param = $this->findParameter($name, $isStandard);

		if($param !== null){
			return $param->getValue();
		}

		return null;
	}

	private function getParameterValues($name, $isStandard){

		$params = $this->findParameters($name, $isStandard);

        $values = array();

        foreach ($params as $param){
            $values[] = $param->getValue();
        }

        return $values;
	}

	private function setStandardParameter($name, $value){

		$param = $this->findParameter($name, true);
		if($param === null){
			$param = new SearchParameter($name, $value);
			$this->standardParameters[] = $param;
		}
		else{
			$param->setValue($value);
		}
	}

	private $standardParameters = array();

	private $notStandardParameters = array();

	public function __toString(){
        $string = "";
		$i = 0;

		foreach($this->getParameters() as $param){
			if($i++ > 0){
				$string .= ", ";
			}
			$string .= $param.getName() . ": " . $param.getValue();

		}

		return $string;
	}
}

/**
 * @ignore
 */
class NullPointerException extends Exception {}
