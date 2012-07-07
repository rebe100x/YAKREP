<?php
require_once('Meta.php');

/*
 * Holds the informations related to result. The informations are separated within the metas and the category groups which allow search navigation.
 */
class Hit
{
	private $infos = null;
	private $categoryGroups = array();
	private $metas = array();
    private $version = null;
    private $baseApiUrl = null;

    /**
     * @ignore 
     */
	public function __construct($version, $url)
    {
		$this->version = $version;
        $this->baseApiUrl = $url;
	}

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
	public function addMeta(Meta $meta)
    {
        if (!array_key_exists($meta->getName(), $this->metas))
        {
            $this->metas[(string) $meta->getName()] = $meta;
        }
	}

    /**
     * @ignore 
     */
	public function addCategoryGroup(CategoryGroup $cat)
    {
		$this->categoryGroups[(string) $cat->getName()] = $cat;
	}

	public function getUrl()
    {
		if (array_key_exists('url', $this->infos))
        {
			return $this->infos['url'];
		} else {
			return '';
		}
	}

    /**
     * an array containing all the Metas for the Hit
     * @return array
     */
	public function getMetas()
    {
		return $this->metas;
	}

    /**
     * a Meta defined by the specified name
     * @param string $name a Meta
     * @return Meta
     */
	public function getMeta($name)
    {
		return $this->metas[$name];
	}

    /**
     * a CategoryGroup for this result defined by the specified name
     * The categories contained in these groups can used as refinements for the navigation.
     * @param string $name a CategoryGroup id
     * @return array
     */
	public function getCategoryGroup($name)
    {
		return $this->categoryGroup[$name];
	}

    /**
     * a list of CategoryGroup retrieved for this result.
     * The categories contained in these groups can used as refinements for the navigation.
     * @return array
     */
	public function getCategoryGroups()
    {
		return $this->categoryGroups;
	}

    /*
     * tries to generate the url for the thumbnail of the document
     * returns null if an information is missing
     * @return string
     */
    function getThumbnail()
    {
        if ($this->version == 'APIFRONT')
        {
            return $this->getMeta('thumbnail')->getValue();
        }
        else if ($this->version == '4.6')
        {
            return $this->baseApiUrl
                    . '?action=getthumbnail'
                    . '&documentid=' . urlencode($this->infos['uri'])
                    . '&documentmime=' . urlencode($this->infos['mime'])
                    . '&documentextension=' . urlencode($this->infos['ext'])
                    . '&documentsource=' . urlencode($this->infos['source'])
                    . '&documentdetectedext=' . urlencode($this->infos['detectedExt']);
        }
        else if ($this->version == '5.0')
        {
            return $this->baseApiUrl . '/search-api/fetch/thumbnail'
                    . '?source=' . urlencode($this->infos['source'])
                    . '&uri=' . urlencode($this->infos['uri']);
        }
        return null;
    }

    /*
     * tries to generate the url for the document
     * returns null if an information is missing
     * @return string
     */
    function getDocument()
    {
        if ($this->version == '4.6')
        {
            return $this->baseApiUrl
                    . '?action=getdocument'
                    . '&documentid=' . urlencode($this->infos['url'])
                    . '&documentmime=' . urlencode($this->infos['mime'])
                    . '&documentextension=' . urlencode($this->infos['ext'])
                    . '&documentsource=' . urlencode($this->infos['source'])
                    . '&searchresultindex=' . urlencode($this->getMeta('hitindex')->getValue())
                    . '&documentdetectedext=' . urlencode($this->infos['detectedExt']);
        }
        else if ($this->version == '5.0')
        {
            return $this->baseApiUrl . '/search-api/fetch/raw'
                    . '?source=' . urlencode($this->infos['source'])
                    . '&uri=' . urlencode($this->infos['uri']);
        }

        return null;
    }

    /*
     * tries to generate the url for the document preview
     * returns null if an information is missing
     * @return string
     */
    function getDocumentPreview($wseq)
    {
        if ($this->version == 'APIFRONT')
        {
            return $this->getMeta('document')->getValue();
        }
        else if ($this->version == '4.6')
        {
            if ($this->getMeta('hitindex'))
            {
                return $this->baseApiUrl
                        . '?action=getDocumentPreview'
                        . '&documentid=' . urlencode($this->infos['url'])
                        . '&wth=' . urlencode($wseq)
                        . '&searchresultindex=' . urlencode($this->getMeta('hitindex')->getValue())
                        . '&documentmime=' . urlencode($this->infos['mime'])
                        . '&documentextension=' . urlencode($this->infos['ext'])
                        . '&documentsource=' . urlencode($this->infos['source'])
                        . '&documentdetectedext=' . urlencode($this->infos['detectedExt']);
            }
            else
            {
                return null;
            }
        }
        else if ($this->version == '5.0')
        {
            return $this->baseApiUrl . '/search-api/fetch/preview'
                    . '?source=' . urlencode($this->infos['source'])
                    . '&uri=' . urlencode($this->infos['uri']);
        }
        return null;
    }
}
