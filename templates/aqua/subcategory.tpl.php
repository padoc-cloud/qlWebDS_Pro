<!-- This file was designed for the Aqua Template //-->
<p>
<div style="width: 100%; text-align: left;">
	<p><br>
	<span class="addlink" style="float: left;">
    	{if allow_add_categ==1}
   		&nbsp; <a href="{add_categ_url}" class="link2" title="{add_categ}">
			<u style="vertical-align: top;">{add_categ}</u> - <img src="{home_url}images/directory_banners/add_category.gif" align="bottom" border="0" alt="Add Category">
		</a>
		&nbsp;<b style="vertical-align: top;">&laquo; | &raquo;</b>&nbsp;
		{end allow_add_categ}
		{if allow_add_site==1}
    	<a href="{add_site_url}" class="link2" title="{add_site}">
			<u style="vertical-align: top;">{add_site}</u> - <img src="{home_url}images/directory_banners/submit.gif" align="bottom" border="0" alt="Submit Listing">
		</a>
		&nbsp;&nbsp;
		{end allow_add_site}
	<br>
  	</span>
	</p>
		<div class="top">
  			<span style="font-weight: bold;">
				{category names}
			</span>
		</div>
	<div>
		<br>
		<p style="font-weight: bold; text-align: justify;">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{description}
		</p>
	</div>
<br>
{admin top menu}
{row subcategories}
<table class="sub_menu">
		<tr>
			<td>{subcategory}[1]</td>
			<td>{subcategory}[2]</td>
			<td>{subcategory}[3]</td>
		</tr>
</table>
{/row subcategories}
{admin foot menu}
{if view_sites==1}
<div class="ql_pagging" style="margin-top: 10px; margin-bottom: 10px;">
	{lang page}: {pagging}
</div>
<table class="sites" width="100%">
		<tr><td class="{featured/normal}[1]"><span class="{sort_disp}[1]">Sort by: <u>{sort_by_date}</u> :: <u>{sort_by_name}</u> :: <u>{sort_by_title}</u></span></td></tr>
	{row site_first}
		<tr>
 			<td class="{featured/normal}[1]">
  				<table cellpadding="0" cellspacing="0" width="100%">
  					<tr>
  						<td style="border: 0px;" align="left">
  							<div class="image2">
								<span class="{image_disp}[1]">
								<a href="{address}[1]" target="_blank" title="Visit {address}[1]" onclick="visit_count({id}[1],'{home_url}');">
									<img src="{thumbnail_code_url}{address}[1]" title="Thumbnail for {address}[1]" style="border-color: #DC913E; border: 1px;" alt="Thumbnail for {title}[1]">
									<br>&nbsp; &nbsp;<u><font color="#CC3300">&uarr;</font> Click to Visit <font color="#CC3300">&uarr;</font></u>
								</a>
								</span>
  							</div>
  						</td>
  						<td style="border: 0px;" align="left" width="100%">
  						    <p class="{company_disp}[1]">&raquo; {company}[1]</p>
   							<p>&raquo; <span class="{url_disp}[1]"><a href="{address}[1]" class="link1" target="_blank" title="Visit this Site" onclick="visit_count({id}[1],'{home_url}');"><u>{title}[1]</u></a> &laquo; click to visit</span><span class="{title_only_disp}[1]">{title}[1]</span></p>
    						<p style="color: #23559C;"><span class="{url_disp}[1]">&raquo; Website: <a href="{address}[1]" target="_blank" title="Visit {address}[1]" onclick="visit_count({id}[1],'{home_url}');"><u>{address}[1]</u></a></span></p>
    						<p style="color: #23559C;"><span>{description_short}[1]</span></p>
   							<p> 
    						-&nbsp;
    						<span class="{url_disp}[1]"><a href="{more address}[1]" title="Listing's Detail Statistics" class="infolink"> <img src="{home_url}images/directory_banners/details_16x16.gif" border="0" width="16" height="16" title="Details for {address}[1]" alt="Details for {title}[1]"> - [View Details]</a>
							<br>-&nbsp; <a href="#" onclick="window.open('{home_url}broken.php?id={id}[1]&amp;s={categ}[1]', 'qlWeb', 'height=457,width=630,resizable=yes,scrollbars=yes');return false;" title="Report Incorrect Info/Broken Link" class="infolink" rel="nofollow">{broken}[1]</a></span>
    						<span class="{detail_disp}[1]"><a href="{more address}[1]" title="Listing's Details" class="infolink"> [View Details]</a></span>
   							</p>
   							<span class="{addr_disp}[1]">
							<b>Address:</b><br>
							{addr}[1]<br>
							{city}[1] {state}[1] {zip}[1] &nbsp; <i>{country}[1]</i>
							</span>
							<p><span class="{tel_fax_disp}[1]"><span class="{tel_disp}[1]"><b>Tel.</b> {tel}[1]  </span><span class="{fax_disp}[1]"><b>Fax</b> {fax}[1] </span></span></p>
   							<p> 
    						{admin menu site}[1] 
   							</p>
  						</td>
  					</tr>
  				</table> 
 			</td> 
		</tr>
		{/row site_first}
		{if ad_no1==1}
		<tr>
 			<td align="center" class="td_add_no1">
  				<div style="text-align: center;">
					{ad}
				</div>
 			</td> 
		</tr>
		{end ad_no1}
 		<tr><td class="{featured/normal}[1]"><span class="{sort_disp}[1]">Sort by: <u>{sort_by_date}</u> :: <u>{sort_by_name}</u> :: <u>{sort_by_title}</u></span></td></tr>
		{row sites}
		<tr>
 			<td class="{featured/normal}[1]">
  				<table style="border: 1px solid #3B4350;" cellpadding="1" cellspacing="1" width="100%">
  					<tr>
  						<td style="border: 0px;" align="left">
  							<div class="image2">
								<span class="{image_disp}[1]">
								<a href="{address}[1]" target="_blank" title="Visit {address}[1]" onclick="visit_count({id}[1],'{home_url}');">
									<img src="{thumbnail_code_url}{address}[1]" title="Thumbnail for {address}[1]" style="border-color: #DC913E; border: 1px;" alt="Thumbnail for {title}[1]">
									<br>&nbsp; &nbsp;<u><font color="#CC3300">&uarr;</font> Click to Visit <font color="#CC3300">&uarr;</font></u>
								</a>
  								</span>
  							</div>
  						</td>
  						<td style="border: 0px;" align="left" width="100%">
  						    <p class="{company_disp}[1]">&raquo; {company}[1]</p>
   							<p>&raquo; <span class="{url_disp}[1]"><a href="{address}[1]" class="link1" target="_blank" title="Visit this Site" onclick="visit_count({id}[1],'{home_url}');"><u>{title}[1]</u></a> &laquo; click to visit</span><span class="{title_only_disp}[1]">{title}[1]</span></p>
    						<p style="color: #23559C;"><span class="{url_disp}[1]">&raquo; Website: <a href="{address}[1]" target="_blank" title="Visit {address}[1]" onclick="visit_count({id}[1],'{home_url}');"><u>{address}[1]</u></a></span></p>
    						<p style="color: #23559C;"><span>{description_short}[1]</span></p>
   							<p>
    						-&nbsp; 
    						<span class="{url_disp}[1]"><a href="{more address}[1]" title="Listing's Detail Statistics" class="infolink"> <img src="{home_url}images/directory_banners/details_16x16.gif" border="0" width="16" height="16" title="Details for {address}[1]" alt="Details for {title}[1]"> - [View Details]</a>
							<br>-&nbsp; <a href="#" onclick="window.open('{home_url}broken.php?id={id}[1]&amp;s={categ}[1]', 'qlWeb', 'height=457,width=630,resizable=yes,scrollbars=yes');return false;" title="Report Incorrect Info/Broken Link" class="infolink" rel="nofollow">{broken}[1]</a></span>
    						<span class="{detail_disp}[1]"><a href="{more address}[1]" title="Listing's Details" class="infolink"> [View Details]</a></span>
   							</p>
							<span class="{addr_disp}[1]">
							<b>Address:</b><br>
							{addr}[1]<br>
							{city}[1] {state}[1] {zip}[1] &nbsp; <i>{country}[1]</i>
							</span>
							<p><span class="{tel_fax_disp}[1]"><span class="{tel_disp}[1]"><b>Tel.</b> {tel}[1] </span><span class="{fax_disp}[1]"><b>Fax</b> {fax}[1] </span></span></p>
   							<p>
    						{admin menu site}[1] 
   							</p>
  						</td>
  					</tr>
  				</table> 
 			</td> 
		</tr>
	{/row sites}
</table>
<div class="ql_pagging" style="margin-top: 10px; margin-bottom: 10px; text-align: right;">
	{lang page}: {pagging}
</div>
	{end view_sites}
	{if allow_add_site==1}
	{if no_pages==1}
		<div style="margin-top: 10px; margin-bottom: 10px; text-decoration: underline; text-align: center;">
			<a href="{add_site_url}" class="link2" title="{add_site}">
				{lang be_first} - <img src="{home_url}images/directory_banners/submit.gif" align="bottom" border="0" alt="Submit Listing">
			</a>
		</div>
	{end no_pages}
	{end allow_add_site}
</div>
