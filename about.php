
<?php include('config.php'); ?>
<!DOCTYPE>
<html>
<head>
<title>CMS</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="description" content="Classified Message Service, 提供機密訊息閱後銷毀服務" />
<script type="text/javascript" src="jquery-1.9.0.min.js"></script>
<link rel="stylesheet" href="style.css" type="text/css" />
<style type="text/css">
#main {
	font-size: 16px;
	line-height: 20px;
}
</style>
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
			<h2>說明</h2>
			<p>Classified Message Service， 機密訊息服務， 簡稱CMS，提供訊息讀後銷毀服務．
				使用者可將自訂訊息輸入後，將產生的網站以電子郵件或即時訊息傳給收訊人，
				當收訊人開啓該連結後，會看到使用者輸入的訊息，但<?php echo $countdown_sec; ?>秒後，該訊息將自動銷毀．</p>
			</div>
			<div class="post">
			<h2>聲明</h2>
			<p>本服務<b>不保證</b>使用者輸入之訊息<b>不會</b>被第三人(包括不法監聽者等)取得，亦<b>不負</b>任何賠償責任．
				對於已自毀之訊息，使用者不得要求使其回復．若使用者不同意上述條款，請勿將資料輸入至本服務．</p>
			<p>We do not guarantee the security of your message, neither do we take any responsibility. 
				For message which has been self destructed, users can not ask to retrieve it. 
				If you don't accept our policy, please input no message to our website. </p>
			</div>

			<div class="post">
			<h2>關於</h2>
			<p>本服務作者為 Jeremy Yen，網站由<a href="http://blog.ssinrc.org">松山高中資訊研究社</a>架設提供，若您對本網站有任何意見與問題，
				歡迎來信至 jeremy5189 (at) gmail.com</p>
			</div>

		</div>
		
	</div>
</div>

<div id="footer">
<p>Copyright &copy; 2013 <a href="http://blog.ssinrc.org">SSInRC</a> & <a href="http://jeremy.ssinrc.org">Jeremy Yen</a></p>
</div>
</body>
</html>
