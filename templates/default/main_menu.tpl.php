<!-- This file may be used with Default, Blue, Coffee, Golden, Khaki, Midnight and Rusty Templates //-->
<table class="main_menu">
	<tbody>
		{row rows}
			<tr>
				<td>
					<p>{main categ}[1]</p>
					<p>{subbcategs}[1]</p>
				</td>
				<td>
					<p>{main categ}[2]</p>
					<p>{subbcategs}[2]</p>
				</td>
				<td>
					<p>{main categ}[3]</p>
					<p>{subbcategs}[3]</p>
				</td>
			</tr>
		{/row rows}
	</tbody>
</table>
{if latest_feat_links==1}
	<div class="ql_last_featured" align="center">
		<b>{lang latest_featured_links}: &nbsp; &nbsp; &nbsp; &nbsp;</b>
		<br>
		{row featured_latest}
		  	<div class="image">
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
		<div class="spacer">
			&nbsp;
		</div>
	</div>
{end latest_feat_links} 
{if latest_links==1} 
	<div class="ql_last">
		<b>{lang latest_links}:</b>
		{row latest}
			<p>
			<a href="{info_url}[1]" title="Find out more about {title}[1]">
				<u>{title}[1]</u>
			</a> - {short_description}[1]
			</p>
		{/row latest}
	</div>
{end latest_links}
<br>
{site_main_ads}
