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
<!-- This file was designed for the Fancy Template //-->
<table style="background-color:#293a41" width="90%" cellpadding="0" cellspacing="10" align="center">
 <tbody>
 <tr>
  <td colspan="2">
  	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td class="topmenu" width="100%">
		<table cellpadding="0" cellspacing="0" align="center">
		<tr>
		<td style="height: 0px; vertical-align: middle; color: #FFFFFF; padding-bottom: 10px;" align="center">{top_block}</td>
		</tr>
		</table>
		{if site_active==1}
		<table width="100%" class="toplinkbg" cellpadding="0">
		<tr>
		<td style="padding: 5px 0px 5px 15px;">
            <!-- Info Links //-->
			<table cellpadding="0" align="left">
			<tr>
			<td style="padding-right: 15px;"><a href="{home_url}">Home</a></td>
			{if add_link==1}
			<td style="padding-right: 15px;"><a href="{home_url}{add_site_url}"><b>{lang add_site}</b></a></td>
			{end add_link}
			<!-- Main Pages -->
			{if pages_disp==1}
			{row pages}
			<td style="padding-right: 15px;">{page link}[1]</td>
			{/row pages}
			{end pages_disp}
			
			{if allow_users==1}
			
			<td style="padding-right: 15px;"><a href="{home_url}?page=login">Login</a></td>
			<td style="padding-right: 15px;"><a href="{home_url}?page=registration">Register</a></td>
			{end allow_users}
			{if user_logged_in==1}
			<td style="padding-right: 15px;"><a href="{home_url}?page=user_account">My Account: <b>{user}</b></a></td>
			<td style="padding-right: 15px;"><a href="{home_url}?page=logout">Logout</a></td>
			{end user_logged_in}
			</tr>
			</table>
		</td>
		</tr>
		</table>
		{end site_active}
	</td>
	</tr>
	</table>
  </td>
 </tr>
 <tr>
  <!-- Left //-->
  <td class="ql_left">
   {if site_active==1}
   <div class="button2id">
      <!-- Search //-->
      <ul>
    	 <li class="menu_top2">Search</li>
         <li style="padding: 10px;">
      <form action="{home_url}index.php" method="get">
         <input name="words" value="" maxlength="70" style="background-color: #FFFFFF; border: 1px solid #CDDFED; padding: 3px; width: 120px;" type="text">&nbsp;&nbsp;<input name="submit" value="Find" type="submit">
         <input name="o" value="search" type="hidden">
      </form>
		 </li>
      </ul>
      <!-- Phone Search //-->
      {if phone_search==1}
      <ul>
    	 <li class="menu_top2">Phone Search</li>
         <li style="padding: 10px;">
      <form action="{home_url}index.php" method="get">
         <input name="number" value="" maxlength="70" style="background-color: #FFFFFF; border: 1px solid #CDDFED; padding: 3px; width: 120px;" type="text">&nbsp;&nbsp;<input name="submit" value="Find" type="submit">
         <input name="o" value="phone_search" type="hidden">
      </form>
		 </li>
      </ul>
      {end phone_search}
   </div>
   <div id="button">
	<!-- Random Site //-->
	{if random_site==1}
			<ul style="text-align: center;">
				<li class="menu_top2">{lang random site title}</li>
			</ul>
				<div class="statistics" style="text-align: center;">
		  		<a href="{random_site_url}" target="_blank" title="Visit {random_site_url}">
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
       <li class="menu_top2">Main Categories</li>
       {row categories}
       <li>{categ link}[1]</li>
       {/row categories}
      </ul>
      {end categories_on_left}
   </div>
  {if main==1}
  <div id="statistics" style="margin-top: 5px;">
  <ul>
    <li class="menu_top2">{lang statistics}</li>
  {if statistics==1}
    <li style="padding: 10px;">
    {lang hmany_active}: {hmany_active}<br>
    {lang hmany_featured}: {hmany_featured}<br>
    {lang hmany_pending}: {hmany_pending}<br>
    {lang hmany_new_today}: {hmany_new_today}<br>
    {lang hmany_approved_today}: {hmany_approved_today}<br>
    {lang hmany_categ}: {hmany_categ}<br>
    </li>
  {end statistics}
  </ul>
  </div>
  <div class="button2id">
	<ul>
    	<li><a href="{home_url}{latest_listings}">Latest Listings</a></li>
        <li><a href="{home_url}{most_visited}">Most Visited</a></li>
 	</ul>
  </div>
  {if users_online==1}
  <div class="button2id">
  	<ul>
	   	<li class="menu_top2">{lang users online title}</li>
	</ul>
  </div>
  <div>
	<ul>
    	<li style="padding: 10px;">
  	{lang hmany_users_online}: {hmany_users_online}<br>
  		</li>
	</ul>
  </div>
  {end users_online}
  {end main}
  {end site_active}
  <div class="ql_right" align="center">
  {left_block}
  </div>
  </td>
  <!-- Main //-->
  <td class="ql_main">
   <table width="100%" cellpadding="0" cellspacing="0">
   <tr>
   <td  width="100%">
  {if banner_rotator==1}
    <div align="center">
	<div class="banner"> {banner_code} </div>
	</div>
  {end banner_rotator}
  {main}
  	</td>
      <!-- Right //-->
	  <td class="ql_right">{right_block}</td>
	   </tr>
	   </table>
  </td>
 </tr>
 <tr>
  <!-- Footer. CAUTION!!! Do NOT change below this line!!! If your remove the {foot} or {thumbnail_backlink} below - your site and/or Thumbnails will eventually STOP working! //-->
  <td colspan="3" class="ql_foot">&nbsp; &copy; {year_foot} {company}. All Rights Reserved.{foot}{thumbnail_backlink}&nbsp;</td>
 </tr>
</tbody>
</table>
  </body>
</html>
