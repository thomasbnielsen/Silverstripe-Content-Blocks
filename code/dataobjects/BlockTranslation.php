<?php
class BlockTranslation extends DataObject {
	private static $db = [
        'Title'		=> 'Varchar',
		'Content' 	=> 'HTMLText',
		'Language' 	=> 'Varchar'
	];

	private static $has_one = array(
		'Block' => 'Block'
	);

	function forTemplate() {
		return $this->renderWith(array($this->Template, 'Block')); // Fall back to Block if selected does not exist
	}
}