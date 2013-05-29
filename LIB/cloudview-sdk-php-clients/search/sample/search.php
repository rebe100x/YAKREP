<?php

require_once('init.php');
require_once('SearchClient.php');

// $searchClient = SearchClient::createSearchClient("http://reddmz011.prod.exalead.com:12100/searchV10", '4.6');
$searchClient = SearchClient::createSearchClient("http://reddmz011.prod:10600/search/xml/v10", '4.6');
// $searchClient = SearchClient::createSearchClient("http://reddmz011.prod:19200/search-api/search", '5.0');
// $searchClient = SearchClient::createSearchClient("http://reddmz012.prod:31010/search-api/search", '5.0');
// $searchClient = SearchClient::createSearchClient("http://api.exalead.com/search/web?output=searchv10", 'APIFRONT');

$searchQuery = new SearchQuery();

foreach ($_GET as $key => $value) {
    $searchParameters = $searchQuery->addParameter($key, $value);
}

try {
  $answer = $searchClient->getResultsAsSimplifiedObject($searchQuery);
} catch (Exception $e) {
  $error = $e->getMessage() . $e->getTraceAsString();
}

if ($answer) {

  $query = clean($_GET['q']); // remove html entities, etc.

  $answerInfos = $answer->getInfos();
  if ($answerInfos['context'])
  {
      unset($_GET);
      $_GET['q'] = $query;
      $_GET[SearchParameter::$CONTEXT] = $answerInfos['context'];
  }

  if (count($answer->getHits())) {
    /* Render the Refines template into the sidebar_right container */
    $templates['sidebar_right'] = 'refines.phtml';
  }
}

/* Render the search Form in the Header section */
$templates['header'] = 'searchHeader.phtml';

$templates['content'] = 'search.phtml';


/* Finally render the search.phtml template into the main container. */
include('layout.phtml');
