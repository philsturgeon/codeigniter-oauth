<?php

class OAuth_Provider_Dropbox extends OAuth_Provider {

	public $name = 'dropbox';

	public function url_request_token()
	{
		return 'https://api.dropbox.com/0/oauth/request_token';
	}

	public function url_authorize()
	{
		return 'http://www.dropbox.com/0/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'https://api.dropbox.com/0/oauth/access_token';
	}
	
	public function get_user_info(OAuth_Consumer $consumer, OAuth_Token $token)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::forge('resource', 'GET', 'https://api.dropbox.com/0/account/info', array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token' => $token->access_token,
		));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		$user = json_decode($request->execute());
		
		// Create a response from the request
		return array(
			'uid' => $token->uid,
			'name' => $user->display_name,
			'location' => $user->country,
		);
	}

} // End Provider_Dropbox