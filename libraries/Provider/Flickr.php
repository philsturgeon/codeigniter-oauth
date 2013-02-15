<?php

class OAuth_Provider_Flickr extends OAuth_Provider {

	public $name = 'flickr';
	public $uid_key = 'user_nsid';

	public function url_request_token()
	{
		return 'http://www.flickr.com/services/oauth/request_token';
	}

	public function url_authorize()
	{
		return 'http://www.flickr.com/services/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'http://www.flickr.com/services/oauth/access_token';
	}
	
	public function get_user_info(OAuth_Consumer $consumer, OAuth_Token $token)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::forge('resource', 'GET', 'http://api.flickr.com/services/rest', array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token' => $token->access_token,
			'nojsoncallback' => 1,
			'format' => 'json',
			'user_id' => $token->uid,
			'method' => 'flickr.people.getInfo',
		));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		$response = json_decode($request->execute(), true);
		$user = $response['person'];

		// Create a response from the request
		return array(
			'uid' => $user['nsid'],
			'name' => isset($user['realname']['_content']) ? $user['realname']['_content'] : $user['username']['_content'],
			'nickname' => $user['username']['_content'],
			'location' => isset($user['location']['_content']) ? $user['location']['_content'] : NULL,
			'image' => $user['iconserver'] ? "http://farm{$user['iconfarm']}.staticflickr.com/{$user['iconserver']}/buddyicons/{$user['nsid']}.jpg" : NULL,
			'urls' => array(
				'photos' => $user['photosurl']['_content'],
				'profile' => $user['profileurl']['_content'],
			),
		);
	}

} // End Provider_Flickr