<!DOCTYPE>
<html>
<head>
<title>CMS</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="description" content="Classified Message Service, 提供機密訊息閱後銷毀服務" />
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
				<p>
				<h2>Enter your secret message</h2>
				<form method="post" action="index.php" name="main_form">
				<textarea name="input" rows="20" cols="75" autofocused required></textarea>
				<br/>
				<input type="submit" id="btn" value="Submit">
				</form>
				</p>
			</div>

		</div>
		
	</div>
</div>

<div id="footer">
<p>&copy; 2006 Your or your company name | Design by <a href="http://spacer.zoxt.net">Mike Yarmish</a></p>
</div>
</body>
</html>