<?php
require_once 'Category.php';
/**
 * Top level component of the category tree which holds a list of categories 
 */
class CategoryGroup
{
    private $name = '';
    private $root = '';
    private $categories = array();
    private $level = 0;

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
    public function addCategory(Category $cat)
    {
        $this->categories[$cat->getId()] = $cat;
    }

    /**
     * the name of the group (the id of the XMLV10 AnswerGroup)
     * @return string
     */
    public function getName()
    {
		return $this->infos['display'];
	}

    /**
     * the root path of the group (ex: "Top/Source")
     * @return string
     */
    public function getRoot()
    {
		return $this->infos['root'];
	}

    /**
     * a list of categories (which is in fact a tree has each category can hold a list of categories)
     * @return string
     */
	public function getCategories()
    {
		return $this->categories;
	}

    /**
     * returns an iterator which allows iterate over the categories keeping the order of the categories in tree
     * @return Iterator
     */
    public function categoryTreeIterator()
    {
        return new CategoryTreeIterator($this);
    }

    /**
     * returns an iterator which allows iterate over the categories keeping the order of the categories in tree
     * @return Iterator
     */
    public function leafCategoriesIterator()
    {
        return new CategoryLeafIterator($this);
    }
}
