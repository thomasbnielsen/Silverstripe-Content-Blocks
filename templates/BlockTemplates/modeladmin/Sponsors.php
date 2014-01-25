<?php

class SponsorAdmin extends ModelAdmin {
	public static $managed_models = array(
		'Sponsor'
	);
    private static $menu_title = 'Sponsors'; 
	private static $url_segment = 'sponsors';
	//private static $menu_icon = 'themes/northsea/images/icon-vessel.png';
}