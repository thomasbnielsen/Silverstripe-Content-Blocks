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
		$gridFieldConfig = GridFieldConfig::create()->addComponents(
			new GridFieldToolbarHeader(),
			new GridFieldAddNewButton('toolbar-header-right'),
			new GridFieldSortableHeader(),
			new GridFieldDataColumns(),
			new GridFieldPaginator(20)
		);
			
		$gridFieldConfig->addComponent(new GridFieldDetailFormCustom());
		$gridFieldConfig->addComponent(new GridFieldEditButton());
		$gridFieldConfig->addComponent(new GridFieldCopyButton());
		$gridFieldConfig->addComponent(new GridFieldDeleteAction());
		$gridFieldConfig->addComponent(new GridFieldOrderableRows('Sort'));
			
		$gridField = new GridField("Sections", "Sections (Content blocks)", $this->owner->Sections(), $gridFieldConfig);
		
		$classes = array_values(ClassInfo::subclassesFor($gridField->getModelClass()));
		
		if (count($classes) > 1 && class_exists('GridFieldAddNewMultiClass')) {
			$gridFieldConfig->removeComponentsByType('GridFieldAddNewButton');
			$gridFieldConfig->addComponent(new GridFieldAddNewMultiClass());
		}

		$fields->addFieldToTab("Root.Sections", $gridField);
		//$this->extend('updateCMSFields', $fields);
		return $fields;
	}

	function ActiveSections() {
		$sections = $this->owner->Sections()->filter(array('Active' => '1'));
		return $sections;
	}
	
	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		// Run on dev buld
		
		// Test if we have required dependencies, maybe we could add some of this as options. Test if installed, else just use basic GridField
		if (!class_exists('GridFieldExtensions')) {
			exit('<li style="color: red">The GridField Extension module by ajshort is required: https://github.com/ajshort/silverstripe-gridfieldextensions</li>');
		}
		if (!class_exists('GridFieldCopyButton')) {
			exit('<li style="color: red">The GridField Copy Button module is required: https://github.com/uniun/silverstripe-copybutton</li>');
		}
		// If css file does not exist on current theme, copy from module
		$copyfrom = BASE_PATH . "/".SECTION_MODULE_DIR."/css/section.css";
		$theme = SSViewer::current_theme();
		$copyto    = "../themes/".$theme."/css/section.css";
		
		if(!file_exists($copyto)) {
			if(file_exists($copyfrom)) {
				copy($copyfrom,$copyto);
				echo '<li style="color: green">section.css copied to: '.$copyto.'</li>';
			} else {
				echo "The default css file was not found: " . $copyfrom;
			}
		}
	}	
	
	
	public function contentcontrollerInit($controller) {
		Requirements::themedCSS('section');
		//Requirements::themedCSS('fluidsection');
	}
}