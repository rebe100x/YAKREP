<?php
/**
 * The Category is the main component of the navigation. The category is a node of the category tree as it may hold a list children categories 
 * Use the id of the category for the refinements
 */
class Category {

   /**
    * DISPLAYED: State of a category which is not currently used for a refinement or an exclusion.
    * EXCLUDED: State of a category which is used for an exclusion.
    * REFINED: State of a category which is used for a refinement.
    * @var array
    */
    static public $STATE = array("DISPLAYED" => 0, "REFINED" => 1, "EXCLUDED" => 2);
    private $id = '';
    private $path = '';
    private $title = '';
    private $infos = array();
    private $state = '';
    private $categories = array();
  
  /**
   * @ignore
   */
  public function setParent(Category $parent)
  {
      $this->parent = $parent;
  }

  /**
   * @ignore
   */
  public function addCategory(Category $cat)
  {
      $this->categories[$cat->getId()] = $cat;
      $cat->setParent($this);
  }

  /**
   * get the full path of the category (ex: "Top/Author/Martin")
   * @return string
   */
  public function getPath() {
    return $this->infos['fullPath'];
  }
  
  /**
   * get the category title (in general the path chunk, ex: Martin for Top/Author/Martin)
   * @return string
   */
  public function getTitle() {
  	return $this->infos['path'];
  }

  /**
   * get the id of the Category, this id can be used for a refinement (must be prefixed by a "+" or "-" for an exclusion when using {@link SearchParameter::$REFINE})
   * @return string 
   */
  public function getId() {
  	return $this->infos["id"];
  }

  /**
   * get the parent category within the category tree (ex: for the category with the following path "Top/Source/nntp/microsoft" the parent category returned will have the following path "Top/Source/nntp")
   * @return Category
   */
  public function getParent() {
  	return $this->parent;
  }

  /**
   * get the state of the category (Category::$STATE['REFINED'], Category::$STATE['EXCLUDED'] or Category::$STATE['DISPLAYED'])
   * @return int
   */
  public function getState() {
  	return $this->infos["state"];
  }
  
  /**
   * return an associative array ontaining informations relative to the Category.
   * Available keys (not exhaustive) :<br>
   * "zapId", Category id for zap action<br>
   * "count", Category count (number of occurrences of this category)<br>
   * "score", Category score (sum of the scores of all matching hits)<br>
   * @return array 
   */
  public function getInfos() {
    return $this->infos;
  }

  /**
   * @ignore
   */
  public function setInfos($infos)
  {
    return $this->infos = $infos;
  }

  /**
   * get the children categories
   * @return array 
   */
  public function getCategories() {
    return $this->categories;
  }
}
