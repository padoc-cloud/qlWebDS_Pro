<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>{script} - {script_name} - Admin</title>
			<meta name="description" content="{script} - {script_name} - Administration">
			<meta name="keywords" content="{script},{script_name}">
			<meta name="resource-type" content="document">
			<meta name="copyright" content="Copyright (c) {years} by {developer} {developer_website}. All Rights Reserved.">
			<meta name="generator" content="{full_version} - {script_name}">
			<meta http-equiv="content-type" content="text/html; charset={charset}">
		<link href="admin_template/images/favicon.ico" rel="shortcut icon">
		<link href="admin_template/style.css" rel="stylesheet" type="text/css">
		<link href="admin_template/CalendarControl.css" rel="stylesheet" type="text/css">
		<script src="../java_scripts/func.js" type="text/JavaScript" language="JavaScript">
		</script>
	</head>
	<body>
<div align="center">
	<table class="main_table">
		<tbody>
 			<tr>
 				<td class="top_left">
					&nbsp;<a href="./">Home</a> | <a href="../" target="_blank" title="View Your Site">View Site</a> | <a href="index.php?mod=users&amp;inc=logout" title="Log out from your Site's Admin">Log Out</a>
				</td>
				<td align="center" class="top_left">
					Your Website: <a href="{site_address}"target="_blank" title="View Your Site">{site_address}</a>
				</td>
				<td align="center" class="top_left">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="center" valign="middle">
					<br>{message}
				</td>
				<td>
  					<div>
						<font color="#CC0033"><b> &nbsp; You are using version: <a href="{developer_url}/modules.php?name=Version_History" target="_blank" title="Visit {developer_website} - Version_History">{full_version} - {script_name}</a></b></font>
						<ul class="tab-spacer">
							<li> &nbsp; </li>
						</ul>
    					<ul class="tab-instructions">
							<li>{script_group} - Help/Info Menu:</li>
						</ul>
						<ul class="tab-spacer">
							<li> &nbsp; </li>
						</ul>
    					<ul class="tabnav">
      						<li><a href="{developer_url}/modules.php?name={family_group}{script_name_short}_Features" target="_blank" title="{script} Main Features">Features</a></li>
      						<li><a href="{developer_url}/modules.php?name=Order" target="_blank" title="Order for All Versions of the Script as Well as Additional Services &amp; Features">Orders</a></li>
							<li><a href="https://www.{developer_website}/affiliates/" target="_blank" title="Join Our Affiliate Program">Affiliates</a></li>
      						<li><a href="{developer_url}/modules.php?name=Templates" target="_blank" title="Discover All {script} Templates available for All Versions">Templates</a></li>
      						<li><a href="{developer_url}/forum/" target="_blank" title="Visit {script} Support Forum">Support Forums</a></li>
      						<li><a href="{developer_url}/modules.php?name=Upgrades" target="_blank" title="Upgrade to the Latest Version">Upgrades</a></li>
						</ul>
    					<ul class="tabnav">
      						<li><a href="{developer_url}/modules.php?name=Installation_Customization" target="_blank" title="Order Custom Services">Customization</a></li>
      						<li><a href="{developer_url}/modules.php?name=Manuals" target="_blank" title="Read {script} Instruction Manual">Manuals</a></li>
      						<li><a href="{developer_url}/contact_us/contact-us.html" target="_blank" title="Contact {developer_website} Staff">Contact</a></li>
      						<li><a href="{developer_url}/modules.php?name=About" target="_blank" title="Get {developer_website} Contact Information">About</a></li>
							<li><a href="./" title="Read Latest {developer_website} News &amp; Announcements">News</a></li>
    					</ul>
						<ul class="tab-spacer">
							<li> &nbsp; </li>
						</ul>
    					<ul class="tab-instructions">
							<li>Admin - Main Navigation Menu:</li> 
						</ul>
						<ul class="tab-spacer">
							<li> &nbsp; </li>
						</ul>
    					<ul class="tabnav">
      						<li><a href="./" title="Admin Home"><u style="color: #4e7194;">Home</u></a></li>
      						<li><a href="index.php?mod=config&amp;inc=settings" class="{ctconfig}" title="Configure your Site"><u style="color: #4e7194;">Config</u></a></li>
      						<li><a href="index.php?mod=site&amp;inc=pages" class="{ctsite}" title="Set Up your Categories, E-mails, Advertisements, Banner Rotator &amp; Filter"><u style="color: #4e7194;">Site</u></a></li>
      						<li><a href="index.php?mod=links&amp;inc=approve_links" class="{ctlinks}" title="Approve or Deny Listings"><u style="color: #4e7194;">Listings</u></a></li>
      						<li><a href="index.php?mod=admin&amp;inc=main" class="{ctadmin}" title="Empty Cache, Backup, Clear PageRank, Switch Templates or Change Password"><u style="color: #4e7194;">Admin</u></a></li>
      						<li><a href="index.php?mod=tools&amp;inc=html_colors" class="{cttools}" title="Webmaster Tools &amp; Helpful Gadgets"><u style="color: #4e7194;">Webmaster Tools</u></a></li>
    					</ul>
						<ul class="tab-spacer">
							<li> &nbsp; </li>
						</ul>
  					</div>
				 </td>
 				<td class="right_block">
					<div class="buttons">
						{message_6}
					</div>
				</td>
 			</tr>
 			<tr>
 				<td class="middle_left">
    				<div class="buttons" {disconfig}>
    					<ul style="text-align: center;" class="tab-instructions">
							<li>Sub Navigation Menu:</li>
						</ul>
						<ul class="tab-spacer">
							<li> &nbsp; </li>
						</ul>
      					<ul>
        					<li class="top">Config Menu</li>
        					<li><a href="index.php?mod=config&amp;inc=settings">Config Settings</a></li>
        					<li><a href="index.php?mod=config&amp;inc=for_user">User Settings</a></li>
        					<li><a href="index.php?mod=config&amp;inc=payments">Listing Types &amp; Payments</a></li>
      					</ul>
						{message_1}
    				</div>
    				<div class="buttons" {dissite}>
    					<ul style="text-align: center;" class="tab-instructions">
							<li>Sub Navigation Menu:</li>
						</ul>
						<ul class="tab-spacer">
							<li> &nbsp; </li>
						</ul>
      					<ul>
        					<li class="top">Site Menu</li>
        					<li><a href="index.php?mod=site&amp;inc=pages">Main Menu Custom Pages</a></li>
        					<li><a href="index.php?mod=site&amp;inc=categories">Categories</a></li>
        					<li><a href="index.php?mod=site&amp;inc=search_category">&nbsp;&nbsp;Search Categories</a></li>
        					<li><a href="index.php?mod=site&amp;inc=emails">E-mails</a></li>
        					<li><a href="index.php?mod=site&amp;inc=ads">Advertisements</a></li>
        					<li><a href="index.php?mod=site&amp;inc=banners">Banner Rotator</a></li>
        					<li><a href="index.php?mod=site&amp;inc=filter">Filter</a></li>
        					<li class="top">HTML &amp; CSS Validators for<br>Your Site</li>
							<li style="text-align: center;">
								<a href="http://validator.w3.org/check?uri={site_address}" target="_blank" title="Validate your HTML">
									<img src="admin_template/images/valid_html_88x31.png" border="0" width="88" height="31" alt="Validate HTML">
								</a>
							</li>
							<li style="text-align: center;">
								<a href="#" onMouseOver="setVisible('validators', true, event); return false;" onMouseOut="setVisible('validators', false, event); return false;">
									<img src="admin_template/images/info.gif" alt="Info">
								</a>
								<div class="info_div" style="display: none" id="info_validators" onMouseOut="setVisible('validators', false, event); return false;">
									{script} is 100% HTML &amp; CSS compliant. If your code does NOT validate properly, that means that one of the codes in your Advertisements/Banner Rotator or custom code in your template might have HTML or CSS errors.
								</div>
							</li>
							<li style="text-align: center;">
								<a href="http://jigsaw.w3.org/css-validator/validator?uri={site_address}" target="_blank" title="Validate your CSS">
									<img src="admin_template/images/valid_css_88x31.gif" border="0" width="88" height="31" alt="Validate CSS">
								</a>
							</li>
						</ul>
						{message_2}
    				</div>
    				<div class="buttons" {dislinks}>
    					<ul style="text-align: center;" class="tab-instructions">
							<li>Sub Navigation Menu:</li>
						</ul>
						<ul class="tab-spacer">
							<li> &nbsp; </li>
						</ul>
      					<ul>
        					<li class="top">Listings Menu</li>
        					<li><a href="index.php?mod=links&amp;inc=approve_links">Approve Pending Listings</a></li>
        					<li><a href="index.php?mod=links&amp;inc=search_approve">&nbsp;&nbsp;Search Pending Listings</a></li>
        					<li><a href="index.php?mod=links&amp;inc=active_links">Active Listings</a></li>
        					<li><a href="index.php?mod=links&amp;inc=search_active">&nbsp;&nbsp;Search Active Listings</a></li>
        					<li><a href="index.php?mod=links&amp;inc=inactive_links">Inactive Listings</a></li>
        					<li><a href="index.php?mod=links&amp;inc=search_inactive">&nbsp;&nbsp;Search Inactive Listings</a></li>
        					<li><a href="index.php?mod=links&amp;inc=payments">Payments</a></li>
        					<li><a href="index.php?mod=links&amp;inc=import_links">Import Listings</a></li>
      					</ul>
						{message_3}
    				</div>
    				<div class="buttons" {disadmin}>
    					<ul style="text-align: center;" class="tab-instructions">
							<li>Sub Navigation Menu:</li>
						</ul>
						<ul class="tab-spacer">
							<li> &nbsp; </li>
						</ul>
      					<ul>
        					<li class="top">Admin Menu</li>
        					<li><a href="index.php?mod=admin&amp;inc=main">Main</a></li>
        					<li><a href="index.php?mod=admin&amp;inc=templates">Templates</a></li>
        					<li><a href="index.php?mod=admin&amp;inc=profile">Admin Profile</a></li>
        					<li><a href="index.php?mod=admin&amp;inc=emailer">E-mailer</a></li>
        					<li><a href="index.php?mod=admin&amp;inc=users">Users</a></li>
      					</ul>
						{message_4}
    				</div>
			<div class="buttons" {distools}>
    					<ul style="text-align: center;" class="tab-instructions">
							<li>Sub Navigation Menu:</li>
						</ul>
						<ul class="tab-spacer">
							<li> &nbsp; </li>
						</ul>
      					<ul>
        					<li class="top">Tools Menu</li>
        					<li><a href="index.php?mod=tools&amp;inc=html_colors">HTML Colors</a></li>
        					<li><a href="index.php?mod=tools&amp;inc=domain_availability_checker">Domain Checker</a></li>
        					<li><a href="index.php?mod=tools&amp;inc=pagerank_buttons">PageRank Buttons</a></li>
      					</ul>
						{message_4}
    				</div>
 				</td>
 				<td class="middle_main">
					{content}
					<div>
						&nbsp;
					</div>
				</td>
 				<td class="right_block">
					<div class="buttons">
						{message_5}
					</div>
				</td>
 			</tr>
			<!-- DO NOT CHANGE OR DELETE BELOW THIS LINE!!! //-->
 			<tr>
 				<td colspan="3" class="foot">
					&nbsp;
					<script src="../java_scripts/copyright_years.js" type="text/JavaScript" language="JavaScript">
					</script>
					<b>{foot}</b> &nbsp;|&nbsp; <b>Version:</b> {full_version} - {script_name} &nbsp;|&nbsp; <b>License:</b> {brand_type}-{license_type}<br>&nbsp;&nbsp;<a href="{developer_url}/modules.php?name=Privacy" target="_blank" title="Read Our Privacy Policy">Privacy</a> &nbsp;|&nbsp; <a href="{developer_url}/modules.php?name=Licenses" target="_blank" title="Order Unbranded or Multi-Domain Licenses">Order Unbranded or Multi-Domain Licenses</a> &nbsp;|&nbsp; <i style="color: #cc8200;">Thank You for choosing {script} - {script_name}!</i>
				</td>
			</tr>
			<tr>
				<td colspan="3" class="foot">
					qlWebScripts.com is NOT affiliated with Google, Inc. &bull; Google&trade; and PageRank&trade; are registered trademarks of Google, Inc.
				</td>
			</tr>
			<tr>
				<td colspan="3" class="foot">
					qlWebScripts.com is NOT affiliated with Alexa Internet, Inc. &bull; Alexa&trade; is a registered trademark of Alexa Internet, Inc.
				</td>
			</tr>
		</tbody>
	</table>
</div>
	</body>
</html>
