<?php
/**
 * Init
 */
	$config = json_decode(file_get_contents(dirname(__FILE__) . '/config.json'));
	require('./vimeo.php/autoload.php');
	$lib = new \Vimeo\Vimeo('', '');
	$lib->setToken($config->access_token);

	// If you only want to look at an album, instead of all of your videos, you can change "/me/videos" to "/me/albums/{album_id}".
	$source = '/me/videos';

/**
 * Handle POSTing to this file, which will generate the signed link
 */
	if ($_POST['uri'] && strtotime($_POST['start_time']) && strtotime($_POST['end_time'])) {

		$uri = $_POST['uri'];
		$start_time = strtotime($_POST['start_time']);
		$end_time = strtotime($_POST['end_time']);

		// sign the data to ensure it can't be tampered with
		$sig = hash_hmac('sha1', json_encode([
			'u' => $uri,
			's' => $start_time,
			'e' => $end_time
		]), $config->access_token);

		echo '<a href="/view.php?uri=' . urlencode($uri) . '&start_time=' . $start_time . '&end_time=' . $end_time . '&sig=' . $sig . '">Video Link</a>';
		die();
	}

/**
 * Make the API call to list all connected videos
 */
	$videos = $lib->request($source . '?fields=uri,name,description,pictures.sizes,embed.html');

/**
 * Display the list of videos
 */
	?>
	<form method="POST" action="">
		<ul>
			<?php foreach ($videos['body']['data'] as $video) : ?>
				<?php
					$display_picture = null;
					foreach ($video['pictures']['sizes'] as $picture) {
						if ($picture['width'] === 200) {
							$display_picture = $picture;
						}
					}?>
				<li><input name="uri" type="radio" value="<?=$video['uri']?>"><?=$video['name']?></option><br /><img src="<?=$display_picture['link']?>"></li>
				<br />
			<?php endforeach ?>
		</ul>

		<label for="start_time">Start Time</label><input id="start_time" type="text" name="start_time" />
		<label for="end_time">End Time</label><input id="end_time" type="text" name="end_time" />
		<button>Generate Link</button>
	</form>