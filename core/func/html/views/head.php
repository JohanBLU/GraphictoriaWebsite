<link type="text/css" rel="stylesheet" href="/core/html/css/bootstrap.css"/>
<link type="text/css" rel="stylesheet" href="/core/html/css/global.css?v=6"/>
<link type="text/css" rel="stylesheet" href="/core/html/css/font-awesome.min.css"/>
<link rel="icon" href="/favicon.ico">
<script src="/core/html/js/jquery-3.1.0.min.js"></script>
<script src="/core/html/js/gibberish-aes-1.0.0.min.js"></script>
<script src="/core/html/js/bootstrap.min.js"></script>
<meta name="csrf-token" content="<?php echo $GLOBALS['csrf_token'];?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="description" content="Graphictoria! Socialize with friends, play games and customize your character. Create an account for free and explore the older versions of an amazing game">
<meta name="keywords" content="Old Roblox, Graphictoria, XDiscuss, Socialize, Games, Forum, Character customisation">
<meta name="author" content="Graphictoria">
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-100898079-1', 'auto');
  ga('send', 'pageview');
</script>
<?php
	if ($GLOBALS['loggedIn'] && $GLOBALS['userTable']['themeChoice'] == 1) echo '<link type="text/css" rel="stylesheet" href="/core/html/css/blackstyle.css?v=9'.time().'"/>';
	if ($GLOBALS['loggedIn'] && $GLOBALS['userTable']['themeChoice'] == 0) echo '<link type="text/css" rel="stylesheet" href="/core/html/css/style.css?v28="/>';
	
	if (!$GLOBALS['loggedIn']) echo '<link type="text/css" rel="stylesheet" href="/core/html/css/style.css?v=28"/>';
?>