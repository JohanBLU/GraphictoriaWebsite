<style>
	@media only screen and (max-width: 500px) {
		.footer {
			height:95px
		}
	}
</style>
<div style="margin-bottom:150px"></div>
<?php
	if ($GLOBALS['loggedIn'] && $GLOBALS['userTable']['themeChoice'] == 0 || $GLOBALS['loggedIn'] == false) {
		echo '<footer class="footer container center" style="box-shadow:0 1px 50px 0 rgba(34,36,38,.15);">';
	}else{
		echo '<footer class="footer container center">';
	}
?>
	<h5 style="color:grey;font-size:22px;margin-bottom:0px">Graphictoria</h5>
	<p style="margin-bottom:0px">All rights belong to their respective owners</p>
	<a href="/forum+73836">Privacy</a> | <a href="/forum+62109">Terms</a> | <a onclick="showDMCA();">DMCA</a> | Renders: <?php echo context::getUserCount(); ?> | Router Requests : <?php echo context::getRouterCount();?>
</footer>