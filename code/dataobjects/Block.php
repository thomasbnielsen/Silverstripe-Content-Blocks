<?php
class Block extends DataObject {
    
	// private static $singular_name = 'Block';
	// private static $plural_name = 'Blocks';	
	private static $first_write = false;

	//public static $default_sort = 'SortOrder';

	private static $db = array(
		'Name' 		=> 'Varchar',
		'Header' 	=> "Enum('None, h1, h2, h3, h4, h5, h6')",
        'Link' 		=> 'Varchar',
		'VideoURL' 	=> 'Varchar',
		'Template' 	=> 'Varchar',
		'Active' 	=> 'Boolean(1)',
		'Classes' 	=> 'Text'
    );

	private static $many_many = array(
		'Images' => 'Image',
		'Files' => 'File',
		'BlockTranslations' => 'BlockTranslation'
    );
	
	private static $many_many_extraFields = array(
		'Images' => array('SortOrder' => 'Int'),
		'Files' => array('SortOrder' => 'Int')
	);

	private static $has_one = array(
		'Page' => 'Page'
	);

	private static $defaults = array(
		'Active' => 1,
		'Page_Blocks[SortOrder]' => 999 // TODO: Fix sorting, new blocks should be added to the bottom of the list/gridfield
	);

	public function populateDefaults() {
		$this->Template = $this->class;
				
		parent::populateDefaults();
	}

	public function canView($member=null) {
		return Permission::check('ADMIN') || Permission::check('CMS_ACCESS_BlockAdmin') || Permission::check('CMS_ACCESS_LeftAndMain') ;
	}

	public function canEdit($member=null) {
		return Permission::check('ADMIN') || Permission::check('CMS_ACCESS_BlockAdmin') || Permission::check('CMS_ACCESS_LeftAndMain') ;
	}

	public function canCreate($member=null) {
		return Permission::check('ADMIN') || Permission::check('CMS_ACCESS_BlockAdmin') || Permission::check('CMS_ACCESS_LeftAndMain') ;
	}

	public function canPublish($member=null) {
		return Permission::check('ADMIN') || Permission::check('CMS_ACCESS_BlockAdmin') || Permission::check('CMS_ACCESS_LeftAndMain') ;
	}

	private static $summary_fields = array( 
		'ID' => 'ID',
		'Thumbnail' => 'Thumbnail',
		'Name' => 'Name',
		'Template' => 'Template',
		'ClassName' => 'Type',
		'getIsActive' => 'Active',
	);
	
	private static $searchable_fields = array(
		'ID' => 'PartialMatchFilter',
		'Name' => 'PartialMatchFilter',
		'Header' => 'PartialMatchFilter',
		'Active'
	);

	public function validate() {
        $result = parent::validate();
        if($this->Name == '') {
            $result->error('A block must have a name');
        }
        return $result;
    }
	
	public function getIsActive(){
		return $this->Active ? 'Yes' : 'No';
	}
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		
		$fields->removeByName('SortOrder');
		$fields->removeByName('Pages');
		$fields->removeByName('Active');
		$fields->removeByName('Header');
		$fields->removeByName('Images');
		$fields->removeByName('Files');
		$fields->removeByName('BlockTranslationsID');
		$fields->removeByName('BlockTranslations');

		//Translations
		foreach (json_decode(SS_LANGUAGES) as $key => $lang) {
			$existingTrans = (object)['Title' => null, 'Content' => null];
			if ($this->ID) {
				$existingTrans = BlockTranslation::get()->filter([
					'BlockID' => $this->ID,
					'Language' => $key
				])->first();
			}

		  	$fields->addFieldsToTab("Root.".$lang, new TextField('Title-'.$key, 'Title', $existingTrans->Title));
		  	$fields->addFieldsToTab("Root.".$lang, new TextareaField('Content-'.$key, 'Content', $existingTrans->Content));
		}

		// Media tab
		$fields->addFieldToTab('Root', new TabSet('Media'));

		// If this Block belongs to more than one page, show a warning
		// TODO: This is not working when a block is added under another block
		$pcount = $this->Pages()->Count();
		if($pcount > 1) {
			$globalwarningfield = new LiteralField("IsGlobalBlockWarning", '<p class="message warning">This block is in use on '.$pcount.' pages - any changes made will also affect the block on these pages</p>');
			$fields->addFieldToTab("Root.Main", $globalwarningfield, 'Name');
			$fields->addFieldToTab("Root.Media.Images", $globalwarningfield);
			$fields->addFieldToTab("Root.Media.Files", $globalwarningfield);
			$fields->addFieldToTab("Root.Media.Video", $globalwarningfield);
			$fields->addFieldToTab("Root.Template", $globalwarningfield);
			$fields->addFieldToTab("Root.Settings", $globalwarningfield);
		}
		
		$imgField = new SortableUploadField('Images', 'Images');
		$imgField->allowedExtensions = array('jpg', 'gif', 'png');
	
		$fields->addFieldsToTab("Root.Main", new TextField('Name', 'Name of block'));
		$fields->addFieldsToTab("Root.Main", new DropdownField('Header', 'Use name as header', $this->dbObject('Header')->enumValues()), 'Content');
		$fields->addFieldsToTab("Root.Main", new TextField('Classes', 'Classes'));

		$fields->addFieldToTab('Root.Media.Images', $imgField);
		
		$fileField = new SortableUploadField('Files', 'Files');

		$fields->addFieldToTab('Root.Media.Files', $fileField); 
		$fields->addFieldToTab('Root.Media.Video', new TextField('VideoURL', 'Video URL')); 
		
		// Template tab
		$optionset = array();
		$theme	= SSViewer::current_theme();
		$src	= BASE_PATH . "/themes/".$theme."/templates/BlockTemplates/";
		$imgsrc	= "/themes/".$theme."/templates/BlockTemplates/";
			
		// TODO: If ClassName == Block, return the templates of the folder.
		// If ClassName is something else (extension of block) then see if there is a folder with that name and only return templates from this folder

		if(file_exists($src)) {
			foreach (glob($src . "*.ss") as $filename) {	
				$name = $this->file_ext_strip(basename($filename));
				
				// Is there a template thumbnail
				$thumbnail = (file_exists($src . $name . '.png') ? '<img src="' .$imgsrc . $name . '.png" />' : '<img src="' .$imgsrc . 'Blank.png" />'); // TODO: Perhaps just add blank as alt for image, no need to check for existance?
				$html = '<div class="blockThumbnail">'.$thumbnail.'</div><strong class="title" title="Template file: '.$filename.'">'. $name .'</strong>';
				$optionset[$name] = $html;
			}
			
			$tplField = OptionsetField::create(
				"Template", 
				"Choose a template", 
				$optionset, 
				$this->Template
			)->addExtraClass('stacked');
			$fields->addFieldsToTab("Root.Template", $tplField);
			
		} else {
			$fields->addFieldsToTab("Root.Template", new LiteralField ($name = "literalfield", $content = '<p class="message warning"><strong>Warning:</strong> The folder '.$src.' was not found.</div>'));
		}

		// Settings tab
		$fields->addFieldsToTab("Root.Settings", new CheckboxField('Active', 'Active'));
		$fields->addFieldsToTab("Root.Settings", new TextField('Link', 'Link'));
		
		$PagesConfig = GridFieldConfig_RelationEditor::create(10);
		$PagesConfig->removeComponentsByType('GridFieldAddNewButton');
		$gridField = new GridField("Pages", "Related pages (This block is used on the following pages)", $this->Pages(), $PagesConfig);
		
		$fields->addFieldToTab("Root.Settings", $gridField);
		
		return $fields;
	}	

	function onBeforeWrite() {
		parent::onBeforeWrite();
		
		if (!$this->ID) {
			$this->first_write = true;
		}
		
	}

	function onAfterWrite() {
		parent::onAfterWrite();
				
	}

	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		// Run on dev build	- move to module file or why is it here?
		
		// If templates does not exist on current theme, copy from module
		$theme = SSViewer::current_theme();
		$copyto    = "../themes/".$theme."/templates/".CONTENTBLOCKS_TEMPLATE_DIR."/";
		
		if(!file_exists($copyto)) {
			$copyfrom = BASE_PATH . "/".CONTENTBLOCKS_MODULE_DIR."/templates/".CONTENTBLOCKS_TEMPLATE_DIR."/";
			if(file_exists($copyfrom)) {
				$this->recurse_copy($copyfrom, $copyto);
				echo '<li style="color: green">BlockTemplates copied to: '.$copyto.'</li>';
			} else {
				echo "The default template archive was not found: " . $copyfrom;
			}
		}
	}	

	// Should only unlink if a block is on more than one page
	public function canDelete($member = null) {
		if(!$member || !(is_a($member, 'Member')) || is_numeric($member)) $member = Member::currentUser();

		// extended access checks
		$results = $this->extend('canDelete', $member);
		
		if($results && is_array($results)) {
			if(!min($results)) return false;
			else return true;
		}

		// No member found
		if(!($member && $member->exists())) return false;
		
		$pcount = $this->Pages()->Count();
		if($pcount > 1) {
			return false;
		} else {
			return true;
		}
		
		
		return $this->canEdit($member);
	}

	function recurse_copy($src,$dst) {
		$dir = opendir($src);
		@mkdir($dst);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					$this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					copy($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}

	/* TODO: add function to calculate image widths based on columns? */
	public function ColumnClass($totalitems) {
		$totalcolumns	= 12; // should be configurable
		$columns = $totalcolumns / $totalitems;
		return $columns;
	}

	public function getThumbnail() { 
		if ($this->Images()->Count() >= 1) {
			return $this->Images()->First()->croppedImage(50,40);
		}
	}

	// Returns only the file extension (without the period).
	function file_ext($filename) {
		if( !preg_match('/\./', $filename) ) return '';
		return preg_replace('/^.*\./', '', $filename);
	}
	
	// Returns the file name, without the extension.
	function file_ext_strip($filename){
		return preg_replace('/\.[^.]*$/', '', $filename);
	}

	public function write()
	{
		$id = parent::write();

		foreach (json_decode(SS_LANGUAGES) as $slang => $lang) {
			if (isset($this->record['Title-'.$slang])) {
				$existingTrans = BlockTranslation::get()->filter([
					'BlockID' => $id,
					'Language' => $slang
				])->first();

				$trans = [
					'Language' => $slang
				];
				foreach ($this->record as $key => $value) {
					if (strpos($key, $slang) !== false) {
						$key = explode('-', $key);
						$trans[$key[0]] = $value;
					}
				}

				if ($existingTrans) {
					DB::query('UPDATE "BlockTranslation" SET "Title"=\''.$trans['Title'].'\', "Content"=\''.$trans['Content'].'\' WHERE "ID" = '.$existingTrans->ID.';');
				} else {
					$newTrans = BlockTranslation::create();
					$newTrans->Title = $trans['Title'];
					$newTrans->Content = $trans['Content'];
					$newTrans->Language = $slang;
					$newTrans->BlockID = $id;
					$newTrans->write();
				}
			}
		}
	}
}