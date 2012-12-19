<?php

class SearchParameter {
    
    /**
     * @ignore
     */
    static $apiVersion = "5.0";

    /**
     * Key of the parameter used to set the query string ex: "exalead OR author:bourdoncle" See "Query Language Guide" for more details 
     * @var string
     * @see SearchQuery::setQuery()
     */
    public static $QUERY = "q";

    /**
     * Key of the parameter used to set the number of results that will be retrieved 
     * @var string
     * @see SearchQuery::setResultsSetLength()
     */
    public static $NRESULTS = "hf";

    /**
     * Key of the parameter used to set the index of the first result to retrieve (starts from 0)
     * @var string
     * @see SearchQuery::setResultsSetStart()
     */
    public static $START = "b";

    /**
     * Key of the parameter used to set the query language
     * @var string
     * @see SearchQuery::setLanguage()
     */
    public static $LANG = "l";

    /**
     * Key of the parameter used to set the field on which the results will be sorted Note that you can't sort on alphanumerical and category fields
     * @var string
     * @see SearchQuery::setSortField()
     */
    public static $SORT = "s";

    /**
     * Key of the parameter used to set whether the sort is ascending or not
     * @var string
     * @see SearchQuery::setAscendingSort()
     */
    public static $SORT_ASCENDING = "sa";

    /**
     * Key of the parameter used to add a security token Security tokens are pushed by connectors thanks to the meta "security" When security token check is enabled on the search server, the documents must have the provided security tokens to be retrieved. Note that the token "EVERYBODY" is automatically added so there is no need to add it in your query.
     * @var string
     * @see SearchQuery::addSecurityToken()
     */
    public static $SECURITY = "securityToken";

    /**
     * Key of the parameter used to add a refinement A refinement is way to precise by your query by adding restriction to a set of categories. A refinement can be a selection (category id prefixed by a "+") or an exclusion (category id prefixed by a "-") of a category.
     * @var string
     * @see SearchQuery::addRefinement()
     */
    public static $REFINE = "r";

    /**
     * Key of the parameter used to remove a refinement. This refinement can be in the context or a refinement that have been added with REFINE  parameter or SearchQuery.addRefinement(String, boolean).
     * @var string
     * @see SearchQuery::cancelRefinement()
     */
    public static $CANCEL_REFINE = "cancel_refine";

    /**
     * Key of the parameter used to "zap" refinement which consists in removing the refinement and adding the father if possible.
     * @var string
     * @see SearchParameter::$REFINE
     * @see SearchParameter::$CANCEL_REFINE
     */
    public static $ZAP_REFINE = "zr";

    /**
    * Key of the parameter used set the logic (default query) that will be used.
     * @var string
     * @see SearchQuery::setSearchLogic()
     */
    public static $LOGIC = "logic";

    /**
     * Key of the parameter used to set the search target on a 5.0 product
     *
     * Warning this parameter is not supported on the 4.6 product 
     * @var string
     */
    public static $TARGET = "target";

    /**
     * Key of the parameter used set the search context. This context is available in the Answer (via getContext) and in the SearchAnswer via getInfos().get("context") The search context allows to chain queries easily without having to keep the parameters used in the previous queries.
     * @var string
     * @see SearchAnswer::getInfos()
     * @see SearchQuery::setSearchContext()
     */
    public static $CONTEXT = "C";

    /**
     * Key of the parameter used to add category group. Note that you can override the settings of an existing group. Syntax: groupId@rootPath[@directory:inHits:maxCategories:maxCategoryTreeDepth:maxCategoriesPerLevel:sortFunction], ex: Source@Top/Source:1::3
     *
     * Warning this parameter is not supported on the 4.6 product
     * @var string
     * @see SearchQuery::addCategoryGroup()
     */
    public static $ADD_CATEGORY_GROUP = "add_category_group";
    
    /**
     * Key of the parameter used to remove a category group. (the parameter value is category group id)
     *
     * @var string
     * @see SearchQuery::removeCategoryGroup()
     */
    public static $REMOVE_CATEGORY_GROUP = "remove_category_group";
    
    /**
     * Key of the parameter used to add hit meta from an index field. This is allow to dynamically override the list of RetrivedField defined in the Search Logic used. Not that the Search Logic is not modified and this setting is only applied to the current query. There are two syntaxes depending whether in data is stored using the CSV format in the index field or not. (MultiRetrievedField in the Search Logic Config).
     * simple syntax: name[:summary]@indexField[:highlighting:maxLength] (hightlighting values are 1 or 0), ex: mymeta@myindexfield:0:100
     * syntax for csv encoded field:@indexField[:highlighting:maxLength], ex: @product_info:0
     *
     * Warning this parameter is not supported on the 4.6 product
     *
     * @var string
     * @see SearchQuery::addMetaFromField()
     */
    public static $ADD_HIT_META = "add_hit_meta";
    
    /**
     * Key of the parameter used to remove a meta from hits (parameter value retrieved field name) 
     *
     * Warning this parameter is not supported on the 4.6 product
     *
     * @var string
     * @see SearchQuery::removeMeta()
     */
    public static $REMOVE_HIT_META = "remove_hit_meta";
    
    /**
     * Key of the parameter used to add a new virtual field definition. Then this virtual field be can retrieved in the hits using SearchParameter#ADD_HIT_META parameter meta, and it can also be used to sort the results using the SearchParameter#SORT parameter. Note that you can override an existing definition.
     *
     * Syntax is : name:expression
     *
     * Warning this parameter is not supported on the 4.6 product
     *
     * @var string
     * @see SearchQuery::addVirtualField()
     */
    public static $ADD_VIRTUAL_FIELD = "add_virtual_field";
    
    /**
     * Key of the parameter used to remove a virtual definition definition using its name.
     *
     * Warning this parameter is not supported on the 4.6 product
     *
     * @var string
     * @see SearchQuery::removeVirtualField()
     */
    public static $REMOVE_VIRTUAL_FIELD = "remove_virtual_field";
    
    /**
     * Key of the parameter used to set the static ranking expression
     *
     * Warning this parameter is not supported on the 4.6 product
     *
     * @var string
     * @see SearchQuery::setStaticRanking()
     * @see SearchQuery::unsetStaticRanking()
     */
    public static $STATIC_RANKING_EXPRESSION = "static_ranking_expression";
    
    /**
     * Key of the parameter used to configure the collapsing of the results. Supported values: "disabled", "lsb_only", "msb_only", "enabled"
     *
     * Warning this parameter is not supported on the 4.6 product
     *
     * @var string
     * @see SearchQuery::setCollapsing()
     */
    public static $COLLAPSING = "collpasing";
    
    /**
     * Key of the parameter used to set the query timeout (ms).
     *
     * Warning this parameter is not supported on the 4.6 product
     *
     * @var string
     * @see SearchQuery::setTimeout()
     */
    public static $TIMEOUT = "timeout";

    private $name;
    private $value;


    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @ignore
     */
    static function isStandard($name = null)
    {
        if (is_null($name))
        {
            $name = $this->name;
        }

        if (self::$apiVersion == '4.6')
        {
            $nameIndex = 0;
        }
        else if (self::$apiVersion == '5.0')
        {
            $nameIndex = 1;
        }

        foreach (self::$standardParameterKey as $param)
        {
            if ($param[$nameIndex] == $name) 
            {
                return true;
            }
        }

        return false;
    }

    /**
     * @ignore
     */
    static public function standardParameterKey($name)
    {
        if (self::$apiVersion == '4.6')
        {
            return self::$standardParameterKey[$name][0];
        }
        else if (self::$apiVersion == '5.0')
        {
            return self::$standardParameterKey[$name][1];
        }
    }
    
    /**
     * @ignore
     */

    static public function setVersion($version)
    {
        self::$apiVersion = $version;
        foreach (self::$standardParameterKey as $parameterKey => $values)
        {
            self::${$parameterKey} = self::standardParameterKey($parameterKey);
        }
    }

    /**
     * @ignore
     */
    static public function allowMultipleValues($name)
    {
        if (self::$apiVersion == '4.6')
        {
            $nameIndex = 0;
        }
        else if (self::$apiVersion == '5.0')
        {
            $nameIndex = 1;
        }

        foreach (self::$standardParameterKey as $param)
        {
            if ($param[$nameIndex] == $name) 
            {
                return $param[2];
            }
        }

        return false;
    }

    /**
     * @ignore
     */
    static $standardParameterKey = array
    (
        "QUERY" => array("q", "q", false), 
		"NRESULTS" => array("hf", "nresults", false), 
		"START" => array("b", "start", false), 
		"LANG" => array("l", "lang", false), 
		"SORT" => array("s", "sort", false),
		"SORT_ASCENDING" => array("sa", "sort_ascending", false),
		"SECURITY" => array(null, "security", true),
		"REFINE" => array("r", "refine", true),
		"CANCEL_REFINE" => array(null, "cancel_refine", true),
		"ZAP_REFINE" => array("zr", "zap_refine", false),
		"LOGIC" => array("n", "logic", false),
		"TARGET" => array(null, "target", false),
		"CONTEXT" => array("C", "context", false),
		"ADD_CATEGORY_GROUP" => array(null, "add_category_group", true),
		"REMOVE_CATEGORY_GROUP" => array(null, "remove_category_group", true),
		"ADD_HIT_META" => array(null, "add_hit_meta", true),
		"REMOVE_HIT_META" => array(null, "remove_hit_meta", true),
		"ADD_VIRTUAL_FIELD" => array(null, "add_virtual_field", true),
		"REMOVE_VIRTUAL_FIELD" => array(null, "remove_virtual_field", true),
		"STATIC_RANKING_EXPRESSION" => array(null, "static_ranking_expression", false),
		"COLLAPSING" => array(null, "collapsing", false),
		"TIMEOUT" => array(null, "timeout", false)
     );

    /**
     * @ignore
     */
    static $notStandardParameterKey = array();
}
