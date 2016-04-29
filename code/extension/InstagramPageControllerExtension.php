
<?php

class InstagramPageControllerExtension extends DataExtension {

	public function InstagramPosts(){
		$approvedPosts = DataObject::get('InstagramPost', 'Approved = 1');
		$data = new ArrayList();
		foreach($approvedPosts as $post){
			$image = json_decode($post->ImageData);
			$data->push(new ArrayData(array(
				'MediaID' => $post->MediaID,
				'ImageURL' => $image->images->low_resolution->url,
				'Link' => $image->link
			)));
		}
		$data = new ArrayData(array(
			'Posts' => $data
		));
		return $data->renderWith('InstagramPosts');		
	}

}
