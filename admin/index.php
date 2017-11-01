<?php 

if(!isset($_GET['widget_id']) || empty($_GET['widget_id']))
{
	echo "Widget ID missing"; exit();
}

$widget_id = $_GET['widget_id'];

$smsg = '';
$emsg = '';

if(isset($_GET['msg']))
{
    if($_GET['msg'] == 'success')
    {
        $smsg = 'Settings have been updated successfully';
    }
}

$file_app = '../data/'.$widget_id.'.json';

 if(!file_exists($file_app))
 {
 	$myfile = fopen($file_app, "w");

 	$default_json_data = (object) array(
	'video_url'      => '',
	'playlist'       => '',
	'fs'             => '0',
	'autoplay'       => '0',
	'controls'       => '0',
	'rel'            => '0',
	'showinfo'       => '0',
	'version'        => '3',
	'loop'           => '0',
	'modestbranding' => '0',
	'disablekb'      => '1',
	'mute'           => '0'
 	);

 	$data = json_encode($default_json_data,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	fwrite($myfile, $data);
 } 

 $app = file_get_contents($file_app);

 $data_json = json_decode(@$app, true);

 if(!empty($data_json['playlist']))
 {
 	$playlist_data = @$data_json['video_url'].','.@$data_json['playlist'];
 }
 else
 {
 	$playlist_data = @$data_json['video_url'];
 }
 

 $playlist = explode(',', $playlist_data);

if(@$_POST)
{
	$playlist_data_value = array();

	if(!empty($_POST['playlist']))
	{
	    $playlist_count = count($_POST['playlist']);

	    $data_json['video_url'] = $_POST['playlist'][0];

	    if($playlist_count > 1)
	    {
	    	$remove_video_url = array_shift($_POST['playlist']);

	    	if(!empty($_POST['playlist']))
	    	{
	    		foreach ($_POST['playlist'] as $key => $row_v) 
	    		{
	    			if(!empty($row_v))
	    			{
	    				$playlist_data_value[] = $row_v;
	    			}
	    		}

	    		$data_json['playlist'] = implode(',', $playlist_data_value);
	    	}
	    }
	    else
	    {
	    	$data_json['playlist'] = '';
	    }
	    

		$data_json['autoplay'] = (isset($_POST['autoplay'])) ? $_POST['autoplay'] : '0';
		$data_json['rel']      = (isset($_POST['rel'])) ? $_POST['rel'] : '0';
		$data_json['showinfo'] = (isset($_POST['showinfo'])) ? $_POST['showinfo'] : '0';
		$data_json['loop']     = (isset($_POST['loop'])) ? $_POST['loop'] : '0';
		$data_json['controls'] = (isset($_POST['controls'])) ? $_POST['controls'] : '0';
		//$data_json['mute']     = (isset($_POST['mute'])) ? $_POST['mute'] : '0';
	}

	$encode_json = json_encode($data_json,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

	if(file_put_contents($file_app,$encode_json))
	{
		$smsg = "Settings has been updated successfully"; 
		header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&msg=success');
		die;
	}
	else
	{
		$emsg = "fail to update Settings";
	}
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Youtube for Screenplify</title>

    <link rel="stylesheet" href="../assets/libs/bootstrap-3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/libs/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/libs/hint-2.4.1.min.css">
    <link rel="stylesheet" href="../assets/css/app-admin.css">
    <link rel="stylesheet" href="../assets/css/app-main.css">
</head>

<body>

    <div class="nw-fm-left-icons-panel">

        <a href="https://www.screenplify.com/" aria-label="Youtube" target="_blank" class="nw-fm-left-icon hint--right" style="margin-bottom: auto;"><span class="border"></span><i class="glyphicon glyphicon-info-sign"></i></a>

        <a href="index.php?widget_id=<?php echo $widget_id; ?>" aria-label="Settings" class="nw-fm-left-icon hint--right active"><span class="border"></span><i class="glyphicon glyphicon-cog"></i></a>

        <div style="margin-top: auto; opacity: 0; visibility: hidden; pointer-events: none;">
            <a href="#" aria-label="Close and Reload" class="nw-fm-left-icon btn-close-reload hint--right" style="opacity: 0; pointer-events: none;"><span class="border"></span><i class="glyphicon glyphicon-refresh"></i></a>
            <a href="plugin-properties.php?widget_id=<?php echo $widget_id; ?>" aria-label="Plugin Properties" class="nw-fm-left-icon hint--right <?php if($active_page == 'plugin-properties'){ echo 'active'; } ?>"><span class="border"></span><i class="glyphicon glyphicon-tasks"></i></a>
        </div>

    </div>


    <div class="container-fluid">
        <div class="row">

            <div class="col-sm-12 main">

                <div id="logo"></div>

                <?php if(!empty($smsg)){ ?>
                <div class='alert alert-success text-capitalize'>
                    <?php echo $smsg; ?>
                </div>
                <?php } ?>

                <?php if(!empty($emsg)){ ?>
                <div class='alert alert-danger text-capitalize'>
                    <?php echo $emsg; ?>
                </div>
                <?php } ?>

                <h1 class="page-header">Configuration</h1>


                <form name="" method="post" action="" class="form-horizontal">

                    <div class="form-group">
                        <label class="col-sm-2 control-label"> Video URL </label>
                        <div class="col-sm-5 input-group-list">
                            <!-- <input class="form-control" type="text" required name="video_url" value="<?php echo @stripslashes($data_json['video_url']); ?>"> -->
                            
                            <?php if(!empty($playlist)){ foreach ($playlist as $key=> $row) { ?>
                            <div class="input-group">
                                <span class="input-group-addon handle ui-sortable-handle">
									<i class="fa fa-bars"></i>
								</span>
                                <input type="text" class="form-control" name="playlist[]" value="<?php echo @stripslashes($row); ?>">
                                <span class="input-group-btn">
									<button class="btn btn-link btn-clone hint--top" aria-label="Add Row" type="button"><i class="fa fa-plus"></i></button>
									<button class="btn btn-link btn-remove hint--top" aria-label="Remove Row" type="button"><i class="fa fa-remove"></i></button>
								</span>
                            </div>
                            <?php } } ?>
                            
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"> Options </label>
                        <div class="col-sm-5">

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="autoplay" value="1" <?php if(@$data_json[ 'autoplay']=='1' ){ ?> checked
                                    <?php } ?>> Autoplay
                                </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="controls" value="1" <?php if(@$data_json[ 'controls']=='1' ){ ?> checked
                                    <?php } ?>> Control Bar
                                </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="rel" value="1" <?php if(@$data_json[ 'rel']=='1' ){ ?> checked
                                    <?php } ?>> Show related videos
                                </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="showinfo" value="1" <?php if(@$data_json[ 'showinfo']=='1' ){ ?> checked
                                    <?php } ?>> Show Video information
                                </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="loop" value="1" <?php if(@$data_json[ 'loop']=='1' ){ ?> checked
                                    <?php } ?>> Loop Video
                                </label>
                            </div>

                            <?php /* ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="mute" value="1" <?php if(@$data_json[ 'mute']=='1' ){ ?> checked
                                    <?php } ?>> Mute Video
                                </label>
                            </div>
                            <?php */ ?>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="btn-submit" class="btn btn-success">Save Changes</button>
                    </div>

                </form>



                <!-- FOOTER -->

            </div>
            <!-- END ./main -->
        </div>
        <!-- END ./row -->

    </div>
    <!-- END ./container-fluid -->

    <script src="../assets/libs/jquery-1.12.3.min.js"></script>
    <script src="../assets/libs/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {


            //Clone Btn
            if ($('.btn-clone').length) {
                $(document).on('click', '.btn-clone', function(e) {
                    e.preventDefault();
                    var $btn = $(this);
                    var $list = $btn.parents('.input-group-list');
                    var $item = $btn.parents('.input-group');

                    $item
                        .clone()
                        .appendTo($list)
                        .find('.form-control')
                        .val('');
                });

                //Remove btn
                $(document).on('click', '.btn-remove', function(e) {
                    e.preventDefault();
                    $(this).parents('.input-group').fadeOut('normal', function() {
                        $(this).remove();
                    });
                });
            }


            //Youtube List
            if ($('.input-group-list').length) {
                $('.input-group-list').sortable({
                    axis: "y",
                    placeholder: "sort-placeholder",
                    //items: ".input-group:not(:first-child)",
                    items: ".input-group",
                    handle: ".handle"
                });
            }

        });
    </script>

</body>

</html>