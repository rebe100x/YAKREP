<?php
/**
 * @ignore
 */
class XMLV10Parser
{
    private $answer = null;
    private $version = null;
    private $baseApiUrl;
    
    public function __construct($xml, $version, $url)
    {
        if (false)
        {
            echo '>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><br/>';
            echo str_replace('<', '&lt;', $xml).'<br>';
            echo '<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<br/>';
        }

        $this->version = $version;
        $url = parse_url($url);
        $this->baseApiUrl = $url['scheme'].'://'.$url['host'] . ($url['port'] ? ':'.$url['port'] : '');
        $xmlReader = new XMLReader();
        $xmlReader->XML($xml);

        while ($xmlReader->read())
        {
            if ($xmlReader->nodeType == XMLReader::ELEMENT && $xmlReader->localName == "Answer")
            {
                $this->answer = $this->parseAnswer($xmlReader);
            }
        }
    }

    public function getAnswer()
    {
        return $this->answer;
    }
    
    public function parseAnswer($xml)
    {
        $answer = new SearchAnswer();
        $answer->setInfos($this->getAttributes($xml));
        
        while ($xml->read())
        {
            if ($xml->nodeType == XMLReader::ELEMENT)
            {
                switch ($xml->localName)
                {
                    case 'AnswerGroup':
                        $answer->addCategoryGroup($this->parseCategoryGroup($xml));
                        break;
                    case 'Hit':
                        $answer->addHit($this->parseHit($xml));
                        break;
                    case 'Time':
                        $answer->setTimes($this->getAttributes($xml));
                        break;
                    case 'SpellCheckSuggestion':
                        $answer->setSpellCheckSuggestion($this->parseSpellCheckSuggestion($xml));
                        break;
                    case 'Keyword':
                        $answer->setRelatedTerm($this->parseRelatedTerm($xml));
                        break;
                    case 'args':
                        $answer->setArgs($this->parseArgs($xml));
                }
            }
            else if ($xml->nodeType == XMLReader::END_ELEMENT)
            {
                if ($xml->localName == 'Answer')
                {
                    break;
                }
            }
        }

        return $answer;
    }

    public function parseArgs($xml)
    {
        while ($xml->read())
        {
            if ($xml->nodeType == XMLReader::ELEMENT)
            {
                switch ($xml->localName)
                {
                    case 'Arg':
                        $attrs = $this->getAttributes($xml, array('name', 'value'));
                        $args[$attrs['name']] = $attrs['value'];
                        break;
                }
            }
            else if ($xml->nodeType == XMLReader::END_ELEMENT)
            {
                if ($xml->localName == 'args')
                {
                    break;
                }
            }
        }
        
        return $args;
    }

    public function parseCategoryGroup($xml)
    {
        $catGroup = new CategoryGroup();
        $catGroup->setInfos($this->getAttributes($xml));

        while ($xml->read())
        {
            if ($xml->nodeType == XMLReader::ELEMENT)
            {
                switch ($xml->localName)
                {
                    case 'Category':
                        $catGroup->addCategory($this->parseCategory($xml));
                        break;
                }
            }
            else if ($xml->nodeType == XMLReader::END_ELEMENT)
            {
                if ($xml->localName == 'AnswerGroup')
                {
                    break;
                }
            }
        }
        
        return $catGroup;
    }
    
    public function parseCategory($xml)
    {
        $cat = new Category();
        $cat->setInfos($this->getAttributes($xml));

        while ($xml->read())
        {
            if ($xml->nodeType == XMLReader::ELEMENT)
            {
                switch ($xml->localName)
                {
                    case 'Category':
                        $cat->addCategory($this->parseCategory($xml));
                        break;
                }
            }
            else if ($xml->nodeType == XMLReader::END_ELEMENT)
            {
                if ($xml->localName == 'Category')
                {
                    break;
                }
            }
        }
        
        return $cat;
    }

    public function parseHit($xml)
    {
        $hit = new Hit($this->version, $this->baseApiUrl);
        $hit->setInfos($this->getAttributes($xml));

        while ($xml->read())
        {
            if ($xml->nodeType == XMLReader::ELEMENT)
            {
                switch ($xml->localName)
                {
                    case 'AnswerGroup':
                        $hit->addCategoryGroup($this->parseCategoryGroup($xml));
                        break;
                    case 'Meta':
                        $metas = $this->parseMeta($xml);
                        if (is_array($metas))
                        {
                            foreach ($metas as $meta)
                            {
                                $hit->addMeta($meta);
                            }
                        }
                        else
                        {
                            $hit->addMeta($metas);
                        }
                }
            }
            else if ($xml->nodeType == XMLReader::END_ELEMENT && $xml->localName == 'Hit')
            {
                break;
            }
        }

        return $hit;
    }

    public function parseSpellCheckSuggestion($xml) 
    {
        $spellSuggest = new SpellCheckSuggestion();
        $xml->moveToAttribute('newStr');
        $spellSuggest->setSuggestedText($xml->value);
        $xml->moveToElement();
        while ($xml->read())
        {
            if ($xml->nodeType == XMLReader::END_ELEMENT && $xml->localName == 'SpellCheckSuggestion')
            {
                break;
            }
        }
        return $spellSuggest;
    }

    public function parseRelatedTerm($xml) 
    {
        $term = new RelatedTerm();
        $term->setInfos($this->getAttributes($xml));

        return $term;
    }

    public function parseMeta($xml)
    {
        if ($xml->moveToAttribute('name') && $xml->value == "commonAttributes")
        {
            $xml->moveToElement();
            while($xml->read())
            {
                if ($xml->nodeType == XMLReader::ELEMENT)
                {
                    switch ($xml->localName)
                    {
                        case 'MetaText':
                        case 'MetaString':
                            $meta = new Meta();
                            $xml->moveToAttribute('name');
                            $meta->setName($xml->value);
                            $xml->moveToElement();
                            $meta->addValue($xml->readOuterXML());
                            break;
                    }
                    $metas[] = $meta;
                }
                else if ($xml->nodeType == XMLReader::END_ELEMENT && $xml->localName == 'Meta')
                {
                    break;
                }
            }
            return $metas;
        }
        else if ($xml->moveToAttribute('name') && $xml->value == 'fieldAttributes')
        {
            $xml->moveToElement();
            $meta = new Meta();
            while($xml->read())
            {
                if ($xml->nodeType == XMLReader::ELEMENT)
                {
                    if ($xml->moveToAttribute('name') && $xml->value == 'name')
                    {
                        $xml->moveToElement();
                        $meta->setName($xml->readString());
                    }
                    else if ($xml->moveToAttribute('name') && $xml->value == 'value')
                    {
                        $xml->moveToElement();

                        $meta->addValue($xml->readOuterXML());
                    }
                }
                else if ($xml->nodeType == XMLReader::END_ELEMENT && $xml->localName == 'Meta')
                {
                    break;
                }
            }
            return $meta;
        }
        else
        {
            $meta = new Meta();
            $xml->moveToAttribute('name');
            $meta->setName($xml->value);

            $xml->moveToElement();

            $meta->addValue($xml->readOuterXML());

            while ($xml->nodeType == XMLReader::END_ELEMENT && $xml->localName == 'Meta')
            {
                $xml->read();
            }

            return $meta;
        }
    }

    private function getAttributes(XMLReader $xml, $attrsLists = 'all')
    {
        $attrs = array();
        if (is_array($attrsLists))
        {
            foreach ($attrsLists as $attr)
            {
                $attrs[$attr] = $xml->getAttribute($attr);
            }
        }
        else if ($attrsLists == 'all')
        {
            while ($xml->moveToNextAttribute()) {
                $attrs[$xml->name] = $xml->value;
            }
        }

        // return the reader to the original Element
        $xml->moveToElement();
        return $attrs;
    }
}
