<?php  

	if(!isset($_GET['widget_id']) || empty($_GET['widget_id']))
	{
		echo "ERROR : Widget ID missing"; exit();
	}

	$widget_id = $_GET['widget_id'];

	$file_app = 'data/'.$widget_id.'.json';

	 if(file_exists($file_app)){ 
	     $app = file_get_contents($file_app);

	 } else {
	     echo "ERROR : Data is Missing.";
	     exit();
	 }

	 $data_json = json_decode($app,true);
	 //print_r($data_json); exit();
		 
	$playlist_data = '';
	$video_playlist_id = array();

	if(!empty($data_json['video_url']))
	{
		preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data_json['video_url'], $match);
		$video_id = $match[1];
		$video_url = 'https://www.youtube.com/embed/'.$video_id;
	}

	if(!empty($data_json['playlist']))
	{
		$split_playlist_data = explode(',', $data_json['playlist']);

		if(!empty($split_playlist_data))
		{
			foreach ($split_playlist_data as $key => $value) 
			{
				preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $value, $match_id);
				$video_playlist_id[] = $match_id[1];
			}

			$playlist_data = implode(',', $video_playlist_id);
		}
	}

	if(empty($playlist_data))
	{
		$playlist_data = $video_id;
	}

	$url = @$video_url;
	$url .= '?autoplay='.$data_json['autoplay'];
	$url .= '&controls='.$data_json['controls'];
	$url .= '&rel='.$data_json['rel'];
	$url .= '&showinfo='.$data_json['showinfo'];
	$url .= '&version='.$data_json['version'];
	$url .= '&loop='.$data_json['loop'];
	$url .= '&playlist='.$playlist_data;
	$url .= '&modestbranding='.$data_json['modestbranding'];
	$url .= '&disablekb='.$data_json['disablekb'];
	$url .= '&fs='.$data_json['fs']; 

	//header("location:".$url);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Screenplify Youtube Plugin</title>
	<style>
		.overlay,
		iframe {
			position: fixed;
			top: 0;
			bottom: 0;
			left: 0;
			right: 0;
			width: 100%;
			height: 100%;
			z-index: 1;
		}
		.overlay {z-index: 2;}
	</style>

    <!-- NEEDED -->
    <?php
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; 
        $client = $protocol.$_SERVER['SERVER_NAME']."/c/client/assets/plugin_support/header_essentials.min.js";
        $front = $protocol.$_SERVER['SERVER_NAME']."/c/frontend/assets/plugin_support/header_essentials.min.js";
        $file = (file_get_contents($client))? $client : $front;    
    ?> 
    <script src="<?php echo $file; ?>"></script>
    <!-- END NEEDED -->
    
</head>
<body>

	<iframe 
		id="video" 
		frameborder="0" 
		allowfullscreen="1" 
		title="YouTube video player" 
		src="<?php echo $url; ?>">	
	</iframe>

	<?php if($data_json['autoplay']){ ?>
		<div class="overlay"></div>
	<?php } ?>

</body>
</html>



