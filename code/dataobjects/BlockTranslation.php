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
}