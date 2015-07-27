<?php
class InsertBlocks extends BuildTask {
 
    protected $title = 'Blocks inserter';
 
    protected $description = 'Inserting blocks, defined in file, to db';
 
    protected $enabled = true;
 
    function run($request) {
    	$data = require_once(__DIR__.'/../../../BlockFiller.php');

        if ($data) {
            foreach(Block::get() as $block) {
                $block->delete();
            }
        }

    	//Loop through all blocks, defined in root/BlockFiller.php
    	foreach ($data as $key => $blockData) {
	    	$block = Block::create($blockData);

            //Find page by classname, so it can link block to page
            $page = Page::get()->filter([
                    'ClassName' => $blockData['ClassName']
                ])->first();
            
            //If page is found, write block
            if (isset($page->ID)) {
                $block->write();
                $page->Blocks()->add($block);
                $block->write();

        		//Loop through all blocks translations, which are defined in block section under 'trans'
    	    	foreach ($blockData['trans'] as $key => $translation) {
    		    	$blockTrans = BlockTranslation::create($translation);
    		    	$blockTrans->BlockID = $block->ID;
    		    	$blockTrans->write();
    	    	}

            }
    	}
    }
}