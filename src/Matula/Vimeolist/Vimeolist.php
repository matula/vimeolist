<?php namespace Matula\Vimeolist;

class Vimeolist
{
	private $base_url = 'http://vimeo.com/api/v2/{username}/videos.json';
	private $username;

	public function __construct($username = 'userscape') {
		$this->setUser($username);
		return $this;
	}

	/**
	 * Set the username for our list
	 *
	 * @return void
	 */
	public function setUser($username = NULL) {
		$this->username = is_null($username) ? $this->username : urlencode($username);
		return $this;
	}

	/**
	 * Set up the url and get the contents
	 *
	 * @return json
	 */
	private function getFeed() {
		$url  = str_replace('{username}', $this->username, $this->base_url);
		$feed = file_get_contents($url);
		return $feed;
	}

	/**
	 * Turn the feed into an object
	 *
	 * @return object
	 */
	public function parseFeed() {
		$json = $this->getFeed();
		$object = json_decode($json);
		return $object;
	}

	/**
	 * Get the list and format the return
	 *
	 * @return array
	 */
	public function getList() {
		$list = array();
		$posts = $this->parseFeed();
		foreach ($posts as $post) {
			$list[$post->id]['title']    = $post->title;
			$list[$post->id]['url']    = $post->url;
			$list[$post->id]['description'] = $post->description;
			$list[$post->id]['thumbnail'] = $post->thumbnail_small;
		}
		return $list;
	}
}
