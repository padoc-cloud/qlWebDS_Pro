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
<!-- {copyright} -->
<!-- This file was designed for the Aqua Template -->
<div style="text-align: center; width: 100%; background-image: url({home_url}templates/aqua/images/top-bg.gif); background-repeat: repeat-x; background-position: top;">
<table width="80%" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF" style="margin-top: 12px;">
 <tr>
 <td colspan="5" style="padding: 0px 10px 0px 10px;">
 	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<td valign="top" width="100%">
		<table cellspacing="0" cellpadding="0" width="100%">
		<tr>
		<td align="left" width="70%" nowrap="nowrap">
			<table cellpadding="0" cellspacing="0" width="100%">
				{if site_active==1}
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" align="left" style="margin-bottom: 10px;" width="100%">
							<tr>
								<!-- Info Links -->
								<td style="padding: 5px 15px 5px 15px;" class="topbutton" nowrap="nowrap"><a href="{home_url}">Home</a></td>
								<td><img src="{home_url}templates/aqua/images/spacer.gif" width="10" alt="spacer"></td>
								{if add_link==1}
								<td style="padding: 5px 15px 5px 15px;" class="topbutton" nowrap="nowrap"><a href="{home_url}{add_site_url}"><b>{lang add_site}</b></a></td>
								<td><img src="{home_url}templates/aqua/images/spacer.gif" width="10" alt="spacer"></td>
								{end add_link}
								<!-- Main Pages -->
								{if pages_disp==1}
	 						    {row pages}
								<td style="padding: 5px 15px 5px 15px;" class="topbutton" nowrap="nowrap">{page link}[1]</td>
								<td><img src="{home_url}templates/aqua/images/spacer.gif" width="10" alt="spacer"></td>
								{/row pages}
								{end pages_disp}
				 			</tr>
							{if allow_users==1}
							<tr>
								<td style="padding: 5px 15px 5px 15px;" class="topbutton" nowrap="nowrap"><a href="./?page=login">Login</a></td>
								<td><img src="{home_url}templates/aqua/images/spacer.gif" width="10" alt="spacer"></td>
								<td style="padding: 5px 15px 5px 15px;" class="topbutton" nowrap="nowrap"><a href="./?page=registration">Register</a></td>
								<td><img src="{home_url}templates/aqua/images/spacer.gif" width="10" alt="spacer"></td>
								{end allow_users}
								{if user_logged_in==1}
								<td style="padding: 5px 15px 5px 15px;" class="topbutton" nowrap="nowrap"><a href="./?page=user_account">My Account: <b>{user}</b></a></td>
								<td><img src="{home_url}templates/aqua/images/spacer.gif" width="10" alt="spacer"></td>
								<td style="padding: 5px 15px 5px 15px;" class="topbutton" nowrap="nowrap"><a href="./?page=logout">Logout</a></td>
								<td><img src="{home_url}templates/aqua/images/spacer.gif" width="10" alt="spacer"></td>
								{end user_logged_in}
							</tr>
						</table>
					</td>
				</tr>
				{end site_active}
				<tr><td style="padding-right: 10px;">
				<table width="100%" cellspacing="10" style="border: 1px solid #CCCCCC;" align="center">
					<tr><td style="text-align: center" width="100%">{top_block}</td></tr>
				</table>
				</td></tr>
			</table>
		</td>
		{if site_active==1}
		<td nowrap="nowrap" class="searchbg" width="30%">
		  <!-- Search -->
		  <form action="{home_url}index.php" method="get">
			 <input name="words" value="" maxlength="70" type="text"> <br>
			 <input name="submit" value="Find" type="image" src="{home_url}templates/aqua/images/search.gif" style="border: none;">
			 <input name="o" value="search" type="hidden">
		  </form>
		  <!-- Phone Search -->
    	  {if phone_search==1}
		  <p>
		  <form action="{home_url}index.php" method="get">
			 <div style="padding: 5px 15px 5px 15px;" class="topbutton">Phone Search</div>
			 <input name="number" value="" maxlength="70" type="text"> <br>
			 <input name="submit" value="Find" type="image" src="{home_url}templates/aqua/images/search.gif" style="border: none;">
			 <input name="o" value="phone_search" type="hidden">
		  </form>
		  </p>
      	  {end phone_search}
		</td>
		{end site_active}
		</tr>
		</table>
	</td>
	</tr>
	</table>
 </td>
 </tr>
 <tr>
  <!-- Left //-->
  {if site_active==1}
  <td class="ql_left" style="padding: 10px; padding-right: 0px;">
   <div style="margin: 0px; background-color: #016565; padding: 10px;">
   <div id="button">
	<!-- Random Site //-->
	{if random_site==1}
			<ul style="text-align: center;">
    			<li class="menu_top">{lang random site title}</li>
			</ul>
				<div class="statistics" style="text-align: center;">
		  		<a href="{random_site_url}" target="_blank" title="Visit {random_site_url}">
					<img src="{thumbnail_code_url}{random_site_url}" style="border-color: #DC913E; border: 1px;" alt="Site Thumbnail for {random_site_url}">
				</a>
				<br>
				<a href="{random_site_info_url}" title="View Statistics for {random_site_url}">
					<u style="color: #ffffff;">{random_site_title}</u>
				</a>
				</div>
	{end random_site}
      <!-- Main Categories //-->
      {if categories_on_left==1}
      <ul>
    	 <li class="menu_top">Main Categories</li>
    	 <li class="inner">
		 {row categories}
         {categ link}[1]
         {/row categories}
		 </li>
      </ul>
      {end categories_on_left}
  {if main==1}
      <ul style="margin-top: 10px;">
		 <li class="menu_top">{lang statistics}:</li>
		{if statistics==1}
		 <li class="inner2">
		 	{lang hmany_active}: {hmany_active}<br>
		 	{lang hmany_featured}: {hmany_featured}<br>
		 	{lang hmany_pending}: {hmany_pending}<br>
		 	{lang hmany_new_today}: {hmany_new_today}<br>
		 	{lang hmany_approved_today}: {hmany_approved_today}<br>
		 	{lang hmany_categ}: {hmany_categ}<br>
		 </li>
		{end statistics}
         <li class="inner"><a href="{home_url}{latest_listings}">Latest Listings</a></li>
         <li class="inner"><a href="{home_url}{most_visited}">Most Visited</a></li>
      </ul>
	  {if users_online==1}
      <ul style="margin-top: 10px;">
	   	<li class="menu_top">{lang users online title}</li>
    	<li class="inner2">
  			{lang hmany_users_online}: {hmany_users_online}<br>
		</li>
	  </ul>
	  {end users_online}
  {end main}
  </div>
  <img src="{home_url}templates/aqua/images/spacer.gif" border="0" width="200" height="1" alt="spacer"><br>
  <div style="text-align: center;" id="button2">{left_block}</div>
  </div>
  </td>
  {end site_active}
  <td><img src="{home_url}templates/aqua/images/spacer.gif" border="0" width="10" alt="spacer"></td>
  <!-- Main //-->
  <td class="ql_main">
  {if banner_rotator==1}
  <div class="ql_right2">
    <div class="banner"> {banner_code} </div>
  </div>
  {end banner_rotator}
  {main}
  </td>
  <td><img src="{home_url}templates/aqua/images/spacer.gif" border="0" width="10" alt="spacer"></td>
  <!-- Right //-->
  <td class="ql_right">
  <div class="ql_right2">
  {right_block}
  </div>
  </td>
 </tr>
 <tr>
  <td colspan="5"><img src="{home_url}templates/aqua/images/spacer.gif" border="0" height="10" alt="spacer"></td>
 </tr>
 <tr>
 <td colspan="5" style="padding: 10px 0px 10px 0px; background-color: #DDDDDD;">
 	<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
      		<!-- Footer. CAUTION!!! Do NOT change below this line!!! If your remove the {foot} or {thumbnail_backlink} below - your site and/or Thumbnails will eventually STOP working! //-->
  			<td width="100%" align="right" style="font-size: 10px;">&nbsp; &copy; {year_foot} {company}. All Rights Reserved.{foot}{thumbnail_backlink}&nbsp;</td>
		</tr>
	</table>
 </td>
 </tr>
</table>
</div>
	</body>
</html>
