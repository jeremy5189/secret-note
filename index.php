<?php
	
	date_default_timezone_set("Asia/Taipei");

	include('config.php');
	$act = "normal";
	
	if( isset($_POST['flag']) && $_POST['flag'] == 'true' )
	{
		require_once('recaptchalib.php');
		$privatekey = "6LfFstwSAAAAAHmlijyL6D8KDS6SlvrHTElrD34i";
		$resp = recaptcha_check_answer ($privatekey,
	                                    $_SERVER["REMOTE_ADDR"],
	                                    $_POST["recaptcha_challenge_field"],
	                                    $_POST["recaptcha_response_field"]);

	  	if (!$resp->is_valid) 
	  	{
	    	// What happens when the CAPTCHA was entered incorrectly
	   		$error = true;
	  	} 
	  	else 
	  	{
	    	// Your code here to handle a successful verification
	    	$act = "show_result";
	    	$url = null;


	    	$DB_link = mysql_connect(DB_HOST, DB_USER, DB_PASS);
    		mysql_select_db(DB_NAME); 
    		mysql_query("SET NAMES UTF8;");

    		$time = date("Y-m-d H:i:s");
    		$ip = getClientIP();
    		$id = substr( md5( time()), 0, 6 );
    		$sql = "INSERT INTO ".DB_TABLE_NAME." (`id`, `time`, `ip`, `message`, `countdown`, `seen`) 
    				VALUES ('$id', '$time', '$ip', '%s', 1, 0);";	
    		$sql = sprintf( $sql, mysql_real_escape_string($_POST['input']));
    		$result = mysql_query($sql, $DB_link);
    		
    		if( $result )
    			$url = "http://ssinrc.org/secret/?q=$id";
   
    		mysql_close($DB_link);
	  	}
	}
	else if( isset($_GET['q']) )
	{
		$act = "show_message";
	}

function getClientIP( $test = "off" )
{
	if( $test == "off" ) 
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;    
    }
    else
    {
        return $test;
    }
    
}

?>
<!DOCTYPE>
<html>
<head>
<title>CMS</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="description" content="Classified Message Service, 提供機密訊息閱後銷毀服務" />
<script type="text/javascript" src="jquery-1.9.0.min.js"></script>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>

<body>
<div id="container" >
	<div id="header">
		<h1>Classified Message Service</h1>
	</div>
	<div id="menu">
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="about.php">About</a></li>
		</ul>
	</div>
	<div id="menuBottom">
	</div>
	
	<div id="content">

		<div id="main">
			<div class="post">
			<?php

				if( $act == "normal" )
				{
			?>
				<h2>Enter your classified message</h2>
			<?php
				if( $error )
				{
					echo "<div id=\"warn\">Wrong Captcha, Please Try Again.</div>";
				}
			?>
				<form method="post" action="index.php" name="main_form">
				<textarea name="input" rows="20" cols="75" autofocused required></textarea>
				<br/><br/>
			<?php
          			require_once('recaptchalib.php');
          			$publickey = "6LfFstwSAAAAAAlAE78m-OEy3EFwPtI4hW_aGmdY"; // you got this from the signup page
          			echo "<div id=\"cap\">".recaptcha_get_html($publickey)."</div>";
        	?>
				<input type="submit" class="btn" value="Submit">
				<input type="hidden" name="flag" value="true">
				</form>
			<?php
				}
				else if ( $act == "show_result" )
				{
					if( $url != null )
					{
						echo "<h2>Your Message Was Saved!</h2>";
						echo "<p>Please copy the link below, 
							whoever enters this link will see your message.
							After that, your message will self destruct in 10 seconds. 
							The next visitor won't be able to see it.</p>";
						echo "<input type=\"text\" id=\"du\" value=\"$url\">";
					}
					else
					{
						echo "<h2>Error!</h2>";
						echo "<p>Service is unavailable, please try again later.</p>";
					}
					
				}	
				else if ( $act == "show_message" )
				{
					$target = $_GET['q'];

					$DB_link = mysql_connect(DB_HOST, DB_USER, DB_PASS);
    				mysql_select_db(DB_NAME); 
    				mysql_query("SET NAMES UTF8;");

    				$sql = "SELECT * FROM ".DB_TABLE_NAME." WHERE `id` = '%s';";
    				$sql = sprintf( $sql, mysql_real_escape_string($target));

    				$result = mysql_query( $sql, $DB_link );
    				$data = mysql_fetch_object($result);

    				if( $data )
    				{
    					if( !$data->seen )
    					{
    						$sql = "UPDATE ".DB_TABLE_NAME." SET `seen` = 1 WHERE `id` = '$target';";
    						$result = mysql_query( $sql, $DB_link );
    						echo "<script>
    							var t = 10;

    							var func = function() {
    								if( t <= 0 )
    								{
    									document.URL=location.href;
    								}
    								$('#count').html(t);
    								t--;
    							}

    							setTimeout( func, 1000 );

    							</script>";
    						echo "<h2>You have a secret message</h2>";
    						echo "<textarea name=\"display\" rows=\"20\" cols=\"75\">$data->message</textarea>";
    						echo "<h2>This message will self destruct in <div id=\"count\"></div> second.</h2>";
    					}
    					else
    					{
    						echo "<h2>404 - Not Found</h2>";
    						echo "<p>The request message either doesn't exist or has already been destructed.</p>";
    					}
    				}
    				else
    				{
    					echo "<h2>404 - Not Found</h2>";
    					echo "<p>The request message #$target either doesn't exist or already been destructed.</p>";
    				}
    				mysql_close($DB_link);
				}
			?>
			</div>

		</div>
		
	</div>
</div>

<div id="footer">
<p>&copy; 2006 Your or your company name | Design by <a href="http://spacer.zoxt.net">Mike Yarmish</a></p>
</div>
</body>
</html>