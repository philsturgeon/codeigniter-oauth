<?php
/**
 * OAuth Twitter Provider
 *
 * Documents for implementing Twitter OAuth can be found at
 * <http://dev.twitter.com/pages/auth>.
 *
 * [!!] This class does not implement the Twitter API. It is only an
 * implementation of standard OAuth with Twitter as the service provider.
 *
 * @package    Kohana/OAuth
 * @category   Provider
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 * @since      3.0.7
 */

namespace OAuth;

class Provider_Linkedin extends Provider {

	public $name = 'linkedin';

	public function url_request_token()
	{
		return 'https://api.linkedin.com/uas/oauth/requestToken';
	}

	public function url_authorize()
	{
		return 'https://api.linkedin.com/uas/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'https://api.linkedin.com/uas/oauth/accessToken';
	}
	
	public function get_user_info(Consumer $consumer, Token $token)
	{
		// Create a new GET request with the required parameters
		$url = 'https://api.linkedin.com/v1/people/~:(id,first-name,last-name,headline,member-url-resources,picture-url,location,public-profile-url)';
		$request = Request::forge('resource', 'GET', $url, array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token' => $token->access_token,
		));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);
		
		$user = \Format::forge($request->execute(), 'xml')->to_array();
		
		// Create a response from the request
		return array(
			'uid' => $user['id'],
			'name' => $user['first-name'].' '.$user['last-name'],
			'nickname' => end(explode('/', $user['public-profile-url'])),
			'description' => $user['headline'],
			'location' => \Arr::get($user, 'location.name'),
			'urls' => array(
			  'Linked In' => $user['public-profile-url'],
			),
		);
	}

} // End Provider_Dropbox