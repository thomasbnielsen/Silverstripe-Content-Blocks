<?php
class SectionModule extends DataExtension {

	public static $db = array(
	);

	public static $has_one = array(
	);
	
	static $has_many = array(
		'Sections' => 'Section'
	);

	public function updateCMSFields(FieldList $fields) {

		// Relation handler for sections		
		$SConfig = GridFieldConfig_RecordEditor::create(10);
		$SConfig->addComponent(new GridFieldOrderableRows('Sort'));
			
		$SConfig->addComponent(new GridFieldDetailFormCustom());
		
		// If the copy button module is installed, add copy as option
		if (!class_exists('GridFieldCopyButton')) {
			$SConfig->addComponent(new GridFieldCopyButton(), 'GridFieldDeleteAction');
		}

		$gridField = new GridField("Sections", "Sections (Content blocks)", $this->owner->Sections(), $SConfig);
		
		$classes = array_values(ClassInfo::subclassesFor($gridField->getModelClass()));
		
		if (count($classes) > 1 && class_exists('GridFieldAddNewMultiClass')) {
			$gridFieldConfig->removeComponentsByType('GridFieldAddNewButton');
			$gridFieldConfig->addComponent(new GridFieldAddNewMultiClass());
		}

		$fields->addFieldToTab("Root.Sections", $gridField);

		return $fields;
	}

	function ActiveSections() {
		$sections = $this->owner->Sections()->filter(array('Active' => '1'));
		return $sections;
	}
	
	// Run on dev buld
	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		
		// If css file does not exist on current theme, copy from module
		$copyfrom = BASE_PATH . "/".SECTION_MODULE_DIR."/css/section.css";
		$theme = SSViewer::current_theme();
		$copyto    = "../themes/".$theme."/css/section.css";
		
		if(!file_exists($copyto)) {
			if(file_exists($copyfrom)) {
				copy($copyfrom,$copyto);
				echo '<li style="green: green">section.css copied to: ' . $copyto . '</li>';
			} else {
				echo '<li style="red">The default css file was not found: ' . $copyfrom . '</li>';
			}
		}
	}	
	
	public function contentcontrollerInit($controller) {
		Requirements::themedCSS('section');
		//Requirements::themedCSS('fluidsection');
	}
}