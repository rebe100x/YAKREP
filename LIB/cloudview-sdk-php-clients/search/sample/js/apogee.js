// Apogee Javascript

/*
 * Clean context
 */
function cleanContext() {
  if ($('searchContext')) {
	$('searchContext').value = '';
  }
  return true;
}

/*
 * Refines Function, used by pagination and the zapette
 */
function refine(refines) {
  for (var attr in refines) {
	var input = document.createElement('input');
	input.type = "hidden";
	input.name = attr;
	input.value = refines[attr];
	document.forms.searchForm.appendChild(input);
  }
  if (!refines['b']) { // Refining ! Resetting page to 0
	var start = document.createElement('input');
	start.type = 'hidden';
	start.name = 'b';
	start.value = '0';
	document.forms.searchForm.appendChild(start);
  }
  document.forms.searchForm.submit();
  return false;
}
