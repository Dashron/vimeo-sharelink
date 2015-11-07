<?php
/**
 * Init
 */
	ini_set('display_errors', '1');
	require('./vimeo.php/autoload.php');

	// Note: The API calls and url links both depend on the access token.
	// If the access token changes, it will invalidate all links that have been distributed.
	$config = json_decode(file_get_contents(dirname(__FILE__) . '/config.json'));


/**
 * Validate parameters, signature, and timerange
 */
	$start_time = (int)$_GET['start_time'];
	$end_time = (int)$_GET['end_time'];
	$uri = $_GET['uri'];
	$signature = $_GET['sig'];

	if (empty($uri) || !$start_time || !$end_time || !$signature) {
		echo 'You have accessed an invalid URL';
		die();
	}



	$now = date('Y-m-d H:i:s');
	if (date('Y-m-d H:i:s', $start_time) > $now || date('Y-m-d H:i:s', $end_time) < $now) {
		echo 'You are only allowed to access this video between ' . date('Y-m-d H:i:s', $start_time) . ' and ' . date('Y-m-d H:i:s', $end_time);
		die();
	}

	// Verify the signature
	// NOTE: This is not a constant time string compare, so you can perform a timing attack to guess the signature.
	// Add this if necessary!
	if ($signature !== hash_hmac('sha1', json_encode([
			'u' => $uri,
			's' => $start_time,
			'e' => $end_time
		]), $config->access_token)) {

		echo 'You have accessed an invalid URL';
		die();
	}
/**
 * Show the video
 */
	$lib = new \Vimeo\Vimeo('', '');
	$lib->setToken($config->access_token);

	// If you only want to look at an album, instead of all of your videos, you can change "/me/videos" to "/me/albums/{album_id}".;
	$video = $lib->request($uri . '?fields=name,description,embed.html');

	echo $video['body']['name'] . '<br /><br />';
	echo $video['body']['description'] . '<br />';
	echo $video['body']['embed']['html'] . '<br />';