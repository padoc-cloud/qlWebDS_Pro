<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>{title}</title>
			<meta name="description" content="{description}">
			<meta name="keywords" content="{keywords}">
			<meta name="author" content="{name}">
			<meta name="copyright" content="Copyright (c) {year_foot} by {company}. All Rights Reserved.">
			<meta name="robots" content="index,all,follow">
			<meta name="revisit-after" content="5 days">
			<meta name="distribution" content="global">
			<meta name="resource-type" content="document">
			<meta name="rating" content="general">
			<meta name="generator" content="{full_version} - {script_name}">
			<meta name="expires" content="0">
			<meta http-equiv="cache-control" content="no-cache">
			<meta http-equiv="content-type" content="text/html; charset={charset}">
		<link href="{home_url}{template}style.css" rel="stylesheet" type="text/css">
		<link href="{home_url}images/directory_banners/favicon.ico" rel="shortcut icon">
		<script src="{home_url}java_scripts/func.js" type="text/javascript" language="JavaScript">
		</script>
		{google_analytics}
	</head>
	<body>
<!-- {copyright} //-->
<!-- This file was designed for the Golden Template //-->
<div style="text-align: center; width: 100%;">
	<div style="text-align: left; margin: auto; width: 980px;">
		<table>
			<tbody>
				<!-- Main Banner/Logo //-->
				<tr>
					<td colspan="3" style="padding-left: 11px;">
						{top_block}
					</td>
				</tr>
				<!-- Site Description //-->
				<tr>
					<td colspan="3" style="padding-left: 11px; padding-right: 11px; font-family: verdana; font-weight: bold; text-align: justify;">
						{site_home_description}
					</td>
				</tr>
				<tr>
					<td class="ql_left">
						{if site_active==1}
						<!-- Main Menu //-->
						<div id="button">
							<ul>
								<li class="menu_top">Menu</li>
        						<li><a href="{home_url}">Home</a></li>
        						{if allow_users==1}
        						<li><a href="{home_url}?page=login">Login</a></li>
        						<li><a href="{home_url}?page=registration">Register</a></li>
        						{end allow_users}
        						{if user_logged_in==1}
        						<li><a href="{home_url}?page=user_account">My Account: <br><b>{user}</b></a></li>
        						<li><a href="{home_url}?page=logout">Logout</a></li>
        						{end user_logged_in}
        						{if add_link==1}
        						<li class="submit"><a href="{home_url}{add_site_url}"><b>{lang add_site}</b></a></li>
        						<li class="submit"><a href="{home_url}{add_categ_url}"><b>{lang add_categ}</b></a></li>
        						{end add_link}
	      						<!-- Main Pages //-->
	      						{if pages_disp==1}
	    	 					{row pages}
    							<li>{page link}[1]</li>
	       						{/row pages}
	      						{end pages_disp}
      						</ul>
      						<!-- Search //-->
      						<ul>
    							<li class="menu_top">Search</li>
    							<li>
      								<form action="{home_url}index.php" method="get">
         							<p style="text-align: center;"><input name="words" value="" maxlength="70" type="text"></p>
         							<p style="text-align: center;"><input name="submit" value="Find" type="submit" class="button3"></p>
         							<input name="o" value="search" type="hidden">
      								</form>
    							</li>
      						</ul>
      						<!-- Phone Search //-->
		      				{if phone_search==1}
      						<ul>
    							<li class="menu_top">Phone Search</li>
    							<li>
      								<form action="{home_url}index.php" method="get">
         							<p style="text-align: center;"><input name="number" value="" maxlength="70" type="text"></p>
         							<p style="text-align: center;"><input name="submit" value="Find" type="submit" class="button3"></p>
         							<input name="o" value="phone_search" type="hidden">
      								</form>
    							</li>
      						</ul>
      						{end phone_search}
      				<!-- Random Site //-->
      				{if random_site==1}
      						<ul style="text-align: center;">
    							<li class="menu_top">{lang random site title}</li>
    						</ul>
							<div class="statistics" style="text-align: center;">
					  			<a href="{random_site_url}" target="_blank" title="Visit {random_site_url}" onclick="visit_count({random_site_id},'{home_url}');">
									<img src="{thumbnail_code_url}{random_site_url}" style="border-color: #DC913E; border: 1px;" alt="Site Thumbnail for {random_site_url}">
								</a>
								<br>
								<a href="{random_site_info_url}" title="View Statistics for {random_site_url}">
									<u>{random_site_title}</u>
								</a>
							</div>
      						{end random_site}
      						<!-- Main Categories //-->
      						{if categories_on_left==1}
      						<ul>
    							<li class="menu_top">Main Categories</li>
    	 					{row categories}
    							<li>{categ link}[1]</li>
       						{/row categories}
      						</ul>
      						{end categories_on_left}
						</div>
      					<!-- Latest/Most Popular Listings //-->
						<div id="button">
	   						<ul>
	   							<li class="menu_top">Latest &amp; Most Popular</li>
	   						</ul>
   						</div>
						<div id="button">
							<ul>
								<li><a href="{home_url}{latest_listings}">Latest Listings</a></li>
								<li><a href="{home_url}{most_visited}">Most Visited</a></li>
							</ul>
						</div>
      					<!-- Statistics //-->
						{if main==1}
						{if statistics==1}
						<div id="button">
	   						<ul>
	   							<li class="menu_top">{lang statistics}</li>
	   						</ul>
   						</div>
						<div class="statistics">
  							&nbsp;{lang hmany_active}: {hmany_active}<br>
  							&nbsp;{lang hmany_featured}: {hmany_featured}<br>
  							&nbsp;{lang hmany_pending}: {hmany_pending}<br>
  							&nbsp;{lang hmany_new_today}: {hmany_new_today}<br>
  							&nbsp;{lang hmany_approved_today}: {hmany_approved_today}<br>
  							&nbsp;{lang hmany_categ}: {hmany_categ}<br>
      						<!-- Users Online //-->
							{if users_online==1}
  							&nbsp;{lang hmany_users_online}: {hmany_users_online} User(s)<br>
							{end users_online}
						</div>
					{end statistics}
  					{end main}
  					{end site_active}
  					<!-- Left Block //-->
  					{left_block}
  				</td>
  				<td class="ql_main">
      			<!-- Banner Rotator //-->
  					{if banner_rotator==1}
    				<div class="banner">
				 		{banner_code} 
					</div>
  					{end banner_rotator}
      					<!-- Categories Block //-->
  						{main}
					</td>
      				<!-- Right Block //-->
  					<td class="ql_right">{right_block}</td>
 				</tr>
      			<!-- Footer //-->
				<tr>
					<!-- CAUTION!!! Do NOT change below this line!!! If your remove the {foot} or {thumbnail_backlink} below - your site and/or Thumbnails will eventually STOP working! //-->
  					<td colspan="3" class="ql_foot">&nbsp; &copy; {year_foot} {company}. All Rights Reserved.{foot}{thumbnail_backlink}&nbsp;</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
  </body>
</html>
