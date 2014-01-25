<?php

class SectionAdmin extends ModelAdmin {
	public static $managed_models = array(
		'Section'
	);
    private static $menu_title = 'Sections'; 
	private static $url_segment = 'sections';
	//private static $menu_icon = 'sectionmodule/icon.png';
}