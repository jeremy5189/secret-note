<?php
    session_start();
	date_default_timezone_set("Asia/Taipei");

	include('config.php');
	$act = "normal";
	
	$_SESSION['auth_code'] = substr( md5( uniqid()), 0, 10 );
	
	if( isset($_POST['flag']) && $_POST['flag'] == 'true' )
	{
		require_once('recaptchalib.php');
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
    			$url = "https://ssinrc.org/secret/?q=$id";
   
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
<script type="text/javascript">
 var RecaptchaOptions = {
    theme : 'white'
 };

$(document).ready( function() {
	reg_event();
});

if (location.protocol == "http:") 
{
	location.protocol = "https:";
}

var validateEmail = function(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 

var reg_event = function() {
    
    $('#du').click( function() {
		this.select();
	});
    
    $('#email_btn').click( function() {

        if( validateEmail( $('#emailBox').val() ) ) {
        
            $.post( 'add_email.php', { 'email': $('#emailBox').val(), 'msg_id': $('#msg_id').val(), 
                                        'auth_code': '<?php echo $_SESSION['auth_code']; ?>' }, function(data) {

                if( data == "OK" )  {
                    $('#email_btn').val("OK");   
                }
                else if( data == "ERROR" ) {
                    $('#email_btn').val("Error"); 
                }
                
            },"html");
        }
        else {
            alert("Not a email address");
        }
    });    
}

var send_mail = function(id, code) {
    $.post( 'mail.php', {'msg_id': id, 'auth_code': code } );
}

 </script>
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
				<h2>Enter your message</h2>
			<?php
				if( $error )
				{
					echo "<div id=\"warn\">Wrong Verify Code, Please Try Again.</div>";
				}
			?>
				<form method="post" action="index.php" name="main_form">
				<textarea name="input" class="input" rows="15" cols="55" autofocused required></textarea>
				<p id="note">Please read our <a href="about.php">policy</a> before continuing. More setting in next page.</p>
				<br/>
			<?php
          			require_once('recaptchalib.php');
          			echo "<div id=\"cap\">".recaptcha_get_html($publickey,null,true)."</div>";
        	?>
				<input type="submit" class="btn" value="Save">
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
							After that, your message will self destruct in $countdown_sec seconds. 
							The next visitor won't be able to see it.</p>";
						echo "<input type=\"text\" id=\"du\" value=\"$url\">";
						echo "<input type=\"hidden\" id=\"msg_id\" value=\"$id\">";
						echo "<h2>Notify me when this message gets read</h2>";
						echo "<input type=\"text\" class=\"em\" id=\"emailBox\">
						      <input type=\"button\" class=\"em\" id=\"email_btn\" value=\"Save\">";
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
    						$code = $_SESSION['auth_code'];
    						$_SESSION['to_email'] = $data->email;
    						
    						echo "<script>
    							var t = $countdown_sec;

    							var func = function() {
    								if( t <= 0 ) location.reload();
    								$('#count').html(t);
    								t-=1;
    								setTimeout( 'func();', 1000 );
    							}

    							$(document).ready(function(){
    								setTimeout( 'func();', 1000 );
    								send_mail( '$target', '$code' );
    							});

    							</script>";
    						echo "<h2>You have a message</h2>";
    						echo "<textarea name=\"display\" class=\"input\" rows=\"15\" cols=\"55\">$data->message</textarea>";
    						echo "<div id=\"cdiv\">This message will be self-destructed after <div id=\"count\">$countdown_sec</div></div>";
    					}
    					else
    					{
    						echo "<h2>Not Found</h2>";
    						echo "<p>The requested message either doesn't exist or has already been ereased.</p>";
    					}
    				}
    				else
    				{
    					echo "<h2>Not Found</h2>";
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
<p>Copyright &copy; 2013 <a href="http://blog.ssinrc.org">SSInRC</a> & <a href="http://jeremy.ssinrc.org">Jeremy Yen</a></p>
</div>
</body>
</html>
