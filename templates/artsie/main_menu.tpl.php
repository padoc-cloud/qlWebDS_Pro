<!-- This file was designed for the Artsie Template //-->
<table class="main_menu" cellpadding="0" cellspacing="1">
<tbody>
{row rows}
<tr>
<td style="border: 1px groove #a3cfcf; background-color: #effdfd;">
  <p>{main categ}[1]</p>
  <p>{subbcategs}[1]</p>
</td>
<td style="border: 1px groove #a3cfcf; background-color: #effdfd;">
  <p>{main categ}[2]</p>
  <p>{subbcategs}[2]</p>
</td>
<td style="border: 1px groove #a3cfcf; background-color: #effdfd;">
  <p>{main categ}[3]</p>
  <p>{subbcategs}[3]</p>
</td>
</tr>
{/row rows}
</tbody>
</table>
{if latest_feat_links==1} 
<div class="ql_last_featured">
   <ul style="margin-top: 10px;">
       <li class="menu_top">{lang latest_featured_links}</li>
       <li style="border-bottom: 3px solid #E4E4E4; padding: 10px; margin-top: 1px; background-color: #F1F1F1;">  
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
       <li class="menu_top">{lang latest_links}</li>
       <li style="border-bottom: 3px solid #E4E4E4; padding: 10px; margin-top: 1px; background-color: #F1F1F1;">  
			{row latest}
			<p style="margin-bottom: 10px;">
			<a href="{info_url}[1]" title="Find out more about {title}[1]"><u>{title}[1]</u></a><br>{short_description}[1]
			</p>
			{/row latest}
	   </li>  
   </ul>  
</div>
{end latest_links}
<br>
<div class="ql_right" style="text-align: center;">
{site_main_ads}
</div>
