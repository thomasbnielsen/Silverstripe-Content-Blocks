<?php
class ContentBlocksModule extends DataExtension {

	private static $create_block_tab = true;
	private static $contentarea_rows = 12;

	private static $db = array();

	private static $has_one = array();
	
	private static $many_many = array(
		'ContentBlocks' => 'ContentBlock'
	);
	
	private static $many_many_extraFields = array(
        'ContentBlocks' => array(
			'SortOrder'=>'Int'
		)
    );
	
	public function updateCMSFields(FieldList $fields) {

		// Relation handler for Blocks		
		$SConfig = GridFieldConfig_RelationEditor::create(25);
        if (class_exists('GridFieldOrderableRows')) {
            $SConfig->addComponent(new GridFieldOrderableRows('SortOrder'));
        }
		$SConfig->addComponent(new GridFieldDeleteAction());
		
		// If the copy button module is installed, add copy as option
		if (class_exists('GridFieldCopyButton')) {
			$SConfig->addComponent(new GridFieldCopyButton(), 'GridFieldDeleteAction');
		}

		$gridField = new GridField("ContentBlocks", "Content blocks", $this->owner->ContentBlocks(), $SConfig);
		
		$classes = array_values(ClassInfo::subclassesFor($gridField->getModelClass()));

		$gridField->getConfig()
			->removeComponentsByType('GridFieldAddExistingAutocompleter')
			->getComponentByType('GridFieldDetailForm')
			->setItemRequestClass('CreateBlock_ItemRequest');

		$detail = $gridField->getConfig()->getComponentByType('GridFieldDetailForm');
		$block = new ContentBlock();
		//$block->PageID = $this->owner->ID; // this is has_many - we need many_many
		$this->owner->ContentBlocks()->add($block);
		$detail->setFields($block->getCMSFields());
		
		if (self::$create_block_tab) {
			$fields->addFieldToTab("Root.Blocks", $gridField);
		} else {
			// Downsize the content field
			$fields->removeByName('Content');
			$fields->addFieldToTab('Root.Main', HTMLEditorField::create('Content')->setRows(self::$contentarea_rows), 'Metadata');
			
			$fields->addFieldToTab("Root.Main", $gridField, 'Metadata');
		}
		
		return $fields;
	}

	public function ActiveBlocks() {
		return $this->owner->ContentBlocks()->filter(array('Active' => '1'))->sort('SortOrder');
	}
	
	public function OneBlock($id) {
		return Block::get()->byID($id);
	}	
	
	// Run on dev buld
	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		
		if (!Config::inst()->get('ContentBlocksModule', 'copy_css_to_theme')) {
			return;
		}
		
		// If css file does not exist on current theme, copy from module
		$copyfrom = BASE_PATH . "/".CONTENTBLOCKS_MODULE_DIR."/css/block.css";
		$theme = SSViewer::current_theme();
		$copyto    = BASE_PATH . "/themes/".$theme."/css/block.css";
		
		if(!file_exists($copyto)) {
			if(file_exists($copyfrom)) {
				copy($copyfrom,$copyto);
				echo '<li style="green: green">block.css copied to: ' . $copyto . '</li>';
			} else {
				echo '<li style="red">The default css file was not found: ' . $copyfrom . '</li>';
			}
		}
	}	
	
	public function contentcontrollerInit($controller) {
		if($this->owner->Blocks()->exists()){
			Requirements::themedCSS('block');
		}
	}

	/**
	* Simple support for Translatable, when a page is translated, copy all content blocks and relate to translated page
	* TODO: This is not working as intended, for some reason an image is added to the duplicated block
	* All blocks are added to translated page - or something else ...
	*/
	public function onTranslatableCreate() {
		
		$translatedPage = $this->owner;
		// Getting the parent translation
		//$originalPage = $translatedPage->getTranslation('en_US');
		$originalPage = $this->owner->getTranslation($this->owner->default_locale());
		foreach($originalPage->Blocks() as $originalBlock) {
			$block = $originalBlock->duplicate(true);
			$translatedPage->Blocks()->add($block);
		}

	}
	
}
