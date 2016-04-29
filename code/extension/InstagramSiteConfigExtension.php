<?php

class InstagramSiteConfigExtension extends DataExtension {

    private static $db = array(
        'InstagramAccessToken' => 'Text',
        'InstagramHashtag' => 'Varchar'
    );

    public function updateCMSFields($fields) {
        $fields->addFieldToTab("Root.Instagram", new TextField("InstagramAccessToken"));
        $fields->addFieldToTab("Root.Instagram", new TextField("InstagramHashtag"));
    }
}
