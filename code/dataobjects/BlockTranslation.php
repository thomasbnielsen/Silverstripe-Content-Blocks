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
		$block = Block::get()->filter(['ID' => $this->BlockID])->first();
			
		return $this->renderWith([$block->Template, 'Block']); // Fall back to Block if selected does not exist
	}

	public function Images()
	{
		if ($this->Block()->Images()->Count() >= 1) {
			return $this->Block()->Images();
		}

		return false;
	}
}