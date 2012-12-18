<?php
require_once('XMLV10Parser.php');
require_once('SearchQuery.php');
require_once('SearchAnswer.php');

/**
 * Component used to send queries to an HTTP XMLV10 SearchAPI and format the response to different formats.
 * This component is compatible for 4.6 and 5.0 products The instantiation is done via the SearchClientFactory.
 */
abstract class SearchClient {
  protected $xmlResponse = null;
  protected $url = null;
  protected $version = null;

    public static function createSearchClient($url, $version)
    {

        if ($version == '4.6')
        {
            $client = new SearchClientV46($url, $version);
        }
        else if ($version == '5.0')
        {
            $client = new SearchClientV50($url, $version);

      }
      else if ($version == 'APIFRONT')
      {
          $client = new SearchClientApiFront($url, $version);
        }
        else
        {
            throw new SearchClientException("Unsupported search version.");
        }
    
        return $client;
    }
  
    private function __construct($url, $version)
    {
        $this->url = $url;
        $this->version = $version;
        SearchParameter::setVersion($version);
    }

    /**
     * @ignore
     */
    public function getVersion()
    {
        return $this->version;
    }
  
    abstract protected function getResults(SearchQuery $query);
  
    /**
     * @return an XMLV10 ResponseEnvelope (4.6) or XMLV10 Answer (5.0) as a DOM document
     * @throws SearchAPIException
     * @throws SearchClientException
     * @see SearchQuery
     */
    public function getResultsAsDocument(SearchQuery $query)
    {
        $this->getResults($query);
        $domObject = new DOMDocument();
        $domObject->loadXML($this->xmlResponse);
        return $domObject;
    }

    /**
     * @return an XMLV10 ResponseEnvelope (4.6) or XMLV10 Answer (5.0) as a SearchAnswer object
     * @throws SearchAPIException
     * @throws SearchClientException
     * @param $query
     * @see SearchQuery
     */
    public function getResultsAsSimplifiedObject(SearchQuery $query)
    {
        $this->getResults($query);
        $xmlParser = new XMLV10Parser($this->xmlResponse, $this->version, $this->url);
        return $xmlParser->getAnswer();
    }

  /**
   * @return an XMLV10 ResponseEnvelope (4.6) or XMLV10 Answer (5.0) as a string
   * @throws SearchAPIException
   * @throws SearchClientException
   * @see SearchQuery
   */
  public function getResultsAsString(SearchQuery $query) {
    $this->getResults($query);
    return $this->xmlResponse;
  }
}

/**
 * @ignore
 */
class SearchClientV46 extends SearchClient
{
    protected function getResults(SearchQuery $query)
    {
        $i = 0;
        $params = $query->getParameters();
        $xmlReq = "<RequestEnvelope xmlns='exa:com.exalead.xmlapplication' version='1.0'><requests>" .
                  "<SearchRequest xmlns='exa:com.exalead.search' requestId='" . $i . "'>";

        foreach ($params as $param)
        {
            $name = $param->getName();
            $value = $param->getValue();
                $xmlReq .= "<Arg xmlns='exa:com.exalead.xmlapplication' name='" . $name . "' value='" . htmlspecialchars(stripslashes($value), ENT_QUOTES, 'UTF-8') . "'/>";
        }

        $xmlReq .= "</SearchRequest>" .
           "</requests></RequestEnvelope>";

        $ch = curl_init($this->url);
        curl_setopt_array($ch, array(
				        CURLOPT_HTTPHEADER => array('Content-type: text/xml'), 
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $xmlReq
                       )
        );

        if (($result = curl_exec($ch)) !== false && curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200)
        {
            $this->xmlResponse = str_replace("xmlns=", "ns=", $result);
        }
        else
        {
            throw new SearchClientException(curl_error($ch));
        }

        curl_close($ch);
    }
}

/**
 * @ignore
 */
class SearchClientV50 extends SearchClient
{
    protected function getResults(SearchQuery $query)
    {
        $params = $query->getParameters();

        foreach ($params as $param)
        {
            $name = $param->getName();
            $value = $param->getValue();
            $params[$name] = htmlspecialchars(stripslashes($value), ENT_QUOTES, 'UTF-8');
        }

        $urlParams = http_build_query($params); 

        $ch = curl_init($this->url . '?' . $urlParams);

        curl_setopt_array($ch, array(
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_RETURNTRANSFER => true,
                              )
        );

        if (($result = curl_exec($ch)) !== false && curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200)
        {
            $this->xmlResponse = str_replace("xmlns=", "ns=", $result);
        }
        else
        {
            throw new SearchClientException(curl_error($ch));
        }

        curl_close($ch);
    }
}

/**
 * @ignore
 */
class SearchClientApiFront extends SearchClient
{
    protected function getResults(SearchQuery $query)
    {
        $i = 0;
        $params = $query->getParameters();
        $xmlReq = "<Query xmlns='exa:com.exalead.search.v10'><args>";

        foreach ($params as $param)
        {
            $name = $param->getName();
            $value = $param->getValue();
            $xmlReq .= "<Arg xmlns='exa:com.exalead.xmlapplication' name='" . $name . "' value='" . htmlspecialchars(stripslashes($value), ENT_QUOTES, 'UTF-8') . "'/>";
        }

        $xmlReq .= "</args></Query>";
     
//        echo str_replace("<", "&lt;", $xmlReq);
        $ch = curl_init($this->url);
        curl_setopt_array($ch, array(
        						CURLOPT_HTTPHEADER => array('Content-type: text/xml'), 
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_POST => true,
                                CURLOPT_POSTFIELDS => $xmlReq
                               )
        );

        if (($result = curl_exec($ch)) !== false && curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200)
        {
          $this->xmlResponse = str_replace("xmlns=", "ns=", $result);
          echo str_replace("<", "&lt;", $this->xmlResponse);
        }
        else
        {
        	throw new SearchClientException('Error: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n" . curl_error($ch));
        }
        
        curl_close($ch);
    }
}

class SearchClientException extends Exception {}
