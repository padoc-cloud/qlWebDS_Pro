<!-- This file was designed for the Majestic Template //-->
<table class="main_menu" cellpadding="0" cellspacing="5">
	<tbody>
		{row rows}
			<tr>
				<td>
  					<p class="cat">{main categ}[1]</p>
  					<p>{subbcategs}[1]</p>
				</td>
				<td>
  					<p class="cat">{main categ}[2]</p>
  					<p>{subbcategs}[2]</p>
				</td>
				<td>
  					<p class="cat">{main categ}[3]</p>
  					<p>{subbcategs}[3]</p>
				</td>
			</tr>
		{/row rows}
	</tbody>
</table>
{if latest_feat_links==1} 
	<div class="ql_last_featured">
		<ul style="margin: 0px;">
       		<li class="menu_top" style=" margin-bottom: 5px;">{lang latest_featured_links}</li>
       		<li style="border: 1px solid #000; padding: 10px; margin: 0px; background-color: #EFF4BC;">  
				<table cellpadding="0" cellspacing="0" align="center">
					<tr>
						<td align="center">
							{row featured_latest}
		  						<div class="image2">
		  		<span class="{featured_image}[1]">
							  		<a href="{featured_url}[1]" target="_blank" title="Visit {featured_url}[1]" onclick="visit_count({id}[1],'{home_url}[1]');">
										<img src="{thumbnail_code_url}{featured_url}[1]" style="border-color: #DC913E; border: 1px;" alt="Site Thumbnail for {featured_url}[1]">
									</a>
									<br>
				</span>
				<a href="{info_url}[1]" title="View {featured_verbiage}[1] for {featured_link}[1]">
										<u>{title}[1]</u>
									</a>
		  						</div>
							{/row featured_latest}
						</td>
					</tr>
				</table>
	   		</li>  
		</ul>  
	</div>
{end latest_feat_links} 
{if latest_links==1} 
	<div class="ql_last">
   		<ul style="margin-top: 10px;">
    		<li class="menu_top" style="margin-bottom: 5px;">{lang latest_links}</li>
       		<li style="border: 1px solid #DDDDDD; padding: 10px; background-color: #AFAF78;">  
				{row latest}
					<p style="margin-bottom: 10px;">
					<a href="{info_url}[1]" title="Find out more about {title}[1]">
						<u>{title}[1]</u>
					</a>
					<br>{short_description}[1]
					</p>
				{/row latest}
	  		</li>  
		</ul>  
	</div>
{end latest_links}
<br>
<div class="ql_right2" style="text-align: center; color:#000;">
	{site_main_ads}
</div>
