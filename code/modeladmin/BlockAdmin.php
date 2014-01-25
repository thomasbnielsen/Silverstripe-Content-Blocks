<?php

class BlockAdmin extends ModelAdmin {
	public static $managed_models = array(
		'Block'
	);
    private static $menu_title = 'Blocks'; 
	private static $url_segment = 'blocks';
	//private static $menu_icon = 'sectionmodule/icon.png';
}