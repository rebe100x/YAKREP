<?

/*
 * Generic Helpers
 */

function clean($string) {
  return strip_tags(htmlspecialchars(stripslashes($string), ENT_QUOTES, 'UTF-8'));
}

function shouldDisplayCategory($catId) {
  global $Layout;

  if (DISPLAY_ALL_CATEGORIES === true ||
	  isset($Layout->synthesisCategories[$catId])) {
	return true;
  }
  return false;
}

function getCategoryName($catId) {
  global $Layout;

  if (isset($Layout->synthesisCategories[$catId])) {
	return $Layout->synthesisCategories[$catId];
  }
  return $catId;
}

function getHighlightedMetaValue(MetaValue $value)
{
	if ($value->isHighlighted()) {
		$func = create_function('$text', 'return "<b>".$text."</b>";');
    return str_replace($value->getHightlightedText(), array_map($func, $value->getHightlightedText()), $value->getStringValue());
	} else {
		return $value->getStringValue();
	}
}
