<script src="js/common.js"></script>
<?php
	if( isset($_SESSION['user'])  ) {
?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
<ul class="nav navbar-nav">
<li><p class="navbar-text"><a href="home.php"/>Interface</a></p></li>
<li><p class="navbar-text"><a href="manager.php"/>Manager</a></p></li>
<li></li>
</ul>
<p class="navbar-text pull-right"><a class="navbar-link" href="logout.php"/>Logout <?php echo $_SESSION['user'];?></a></p>
</nav>
<?php
	}
?>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="css/common.css" rel="stylesheet" media="screen">
