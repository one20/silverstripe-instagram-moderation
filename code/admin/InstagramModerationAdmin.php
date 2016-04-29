<?php
class InstagramModerationAdmin extends ModelAdmin {

	private static $managed_models = array(
		"InstagramPost"
	);

    private static $url_segment = 'instagram-moderation';

    private static $menu_title = 'Instagram';

    private static $menu_icon = 'silverstripe-instagram-moderation/images/model-admin-icon.png';

}