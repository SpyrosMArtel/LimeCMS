<?php session_start(); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script type="text/javascript" src="js/nav.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Shadows+Into+Light%7COpen+Sans%7CMontserrat%7CInconsolata' rel='stylesheet' type='text/css'>
		<style type="text/css">@import url("/templates/website/css/core.css");</style>
		<title><?php echo htmlspecialchars($results['pageTitle']) ?></title>
	</head>
	<body>
		<div id="pageContainer" class="inset_shadow">
			<div id="header">
				<p class="logo">WebSite</p>
				<?php if (isset($_SESSION['username'])) { ?>
					<p class="loggedIn">
						You are logged in as <b><?php echo htmlspecialchars($_SESSION['username']) ?></b>
					</p>
				<?php } ?>
			</div>
			<div id="navigation" class="one_edge_sh">
				<ul class="clearfix">
					<li><a href="/">Home</a></li>
					<li><a href="#">Page 1</a></li>
					<li><a href="#">Page 2</a></li>
					<li><a href="#">Page 3</a></li>
					<li><a href="#">Page 4</a></li>
				</ul>
				<a href="#" id="pull">Menu</a>
			</div>
