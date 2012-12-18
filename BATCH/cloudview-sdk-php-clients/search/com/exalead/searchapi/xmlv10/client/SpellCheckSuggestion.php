<?php
/**
 * Spelling suggestion
 */
class SpellCheckSuggestion {

    public function setSuggestedText($text) {
      $this->suggestedText = $text;
    }

	public function getSuggestedText() {
		return $this->suggestedText;
	}
}