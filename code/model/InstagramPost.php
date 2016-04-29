<?php
class InstagramPost extends DataObject {

	private static $instagram_uri = 'https://api.instagram.com/v1/';

	private static $db = array(
		"MediaID" => "Varchar(50)",
		"Approved" => "Boolean",
		"ImageData" => "Text"
	);

	private static $summary_fields = array(
		'Thumbnail' => 'Thumbnail',
		'Approved' => 'Approved'
	);

	private function requestPosts($maxtag=null) {
		$config = SiteConfig::current_site_config();
        $cmd = 'tags/'.$config->InstagramHashtag.'/media/recent';
        $rest = InstagramPost::$instagram_uri.$cmd."?access_token=".$config->InstagramAccessToken.'&count=100';
        if($maxtag){
        	$rest = $maxtag;
        }

        $post = curl_init();
        curl_setopt($post, CURLOPT_URL, $rest);
		curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);

		$result = curl_exec($post);
		curl_close($post);
		return json_decode($result);
	}

	private function buildPosts(&$request, &$fields){
		$posts = $request->data;
			
		$unmoderatedCount = 0;
		foreach ($posts as $post) {
			$moderated = DataObject::get('InstagramPost')->filter(array('MediaID' => $post->id));
			if(!$moderated->count()){
				$postData = '<div class="post"><img src="'.$post->images->standard_resolution->url.'" width="300" height="300" alt="" /><p>'.$post->caption->text.'</p></div>';

				$postField = new OptionsetField(
					$name = 'Instagram_'.$post->id,
					$title = $postData,
					$source = array(
					"approve" => "Approve",
					"reject" => "Reject"
					)
				);
				$imageField = new HiddenField('ImageData_'.$post->id, 'ImageData_'.$post->id, json_encode(array(
					'images' => $post->images,
					'link' => $post->link
				)));
				$postField->addExtraClass('instagram-post');
				$fields->add($postField);
				$fields->add($imageField);
				$unmoderatedCount++;
			}
		}
		if(!$unmoderatedCount){
			$request = $this->requestPosts($request->pagination->next_url);
			$this->buildPosts($request, $fields);
		}
	}

	public function getCMSFields() {
		if($this->ID == 0){
			$fields = FieldList::create();
			$request = $this->requestPosts();
			$this->buildPosts($request, $fields);


			$fields->add(new HiddenField('moderated', 'moderated', 'true'));
		} else {
			$fields = parent::getCMSFields();
			$fields->removeByName('MediaID');
			$fields->removeByName('ImageData');

			$imageData = json_decode($this->ImageData);
			$image = $imageData->images->standard_resolution->url;
			$fields->addFieldToTab('Root.Main', new LiteralField('', '<img src="'.$image.'">'), 'Approved');
		}
		return $fields;
	}

	public function validate(){
		$result = parent::validate();
		if(isset($this->record['moderated'])) {
			$prepend = 'Instagram_';
			$imagePrepend = 'ImageData_';

			foreach($this->record as $key => $val) {
				if(strpos($key, $prepend) !== false) {
					if($val == 'approve' || $val == 'reject'){
						$id = substr($key, strlen($prepend));
						$post = new InstagramPost();
						$post->MediaID = $id;
						$post->Approved = ($val == 'approve');
						$post->ImageData = $this->record['ImageData_'.$id];
						$post->write();
					}
				}
			}
		}
		if(!$this->MediaID) $result->error('<script>document.getElementById("Form_ItemEditForm_error").style.display="none";window.location.reload();</script>');
		return $result;
	}
	
	public function getThumbnail() { 
		$imageData = json_decode($this->ImageData);
		$lowRes = $imageData->images->thumbnail->url;
		return DBField::create_field('HTMLVarchar', '<img src="'.$lowRes.'" width="100" height="100"/>');
	}

}