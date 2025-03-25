<!-- This file was designed for the Diamonds Template //-->
<table class="main_menu" width="100%" cellpadding="0" cellspacing="5">
<tbody>
{row rows}
<tr>
<td>
  <p class="cat">{main categ}[1]</p>
  <p class="subcat">{subbcategs}[1]</p>
</td>
<td>
  <p class="cat">{main categ}[2]</p>
  <p class="subcat">{subbcategs}[2]</p>
</td>
<td>
  <p class="cat">{main categ}[3]</p>
  <p class="subcat">{subbcategs}[3]</p>
</td>
</tr>
{/row rows}
</tbody>
</table>
<table width="100%" cellpadding="0" cellspacing="10">
<tr>
<td width="25%" align="left" valign="top">
{if latest_feat_links==1} 
<h3 align="left">{lang latest_featured_links}:</h3>
<div class="ql_last_featured">
	{row featured_latest}
		<p>
		  		<span class="{featured_image}[1]">
  		<a href="{featured_url}[1]" target="_blank" title="Visit {featured_url}[1]" onclick="visit_count({id}[1],'{home_url}[1]');">
			<img src="{thumbnail_code_url}{featured_url}[1]" style="border-color: #DC913E; border: 1px;" alt="Site Thumbnail for {featured_url}[1]">
		</a>
		<br>
				</span>
				<a href="{info_url}[1]" title="View {featured_verbiage}[1] for {featured_link}[1]">
			<u>{title}[1]</u>
		</a>
		</p>
		<br>
	{/row featured_latest}
</div>
{end latest_feat_links} 
</td>
<td width="1%" align="left" valign="top">&nbsp;</td>
<td width="74%" align="left" valign="top">
{if latest_links==1} 
<h3>{lang latest_links}:</h3>
<div class="ql_last">
{row latest}
<p>
<a href="{info_url}[1]" class="orange" title="Find out more about {title}[1]">
<u>{title}[1]</u>
<br>
</a> - {short_description}[1]
</p>
{/row latest}
</div>
{end latest_links}
</td>
</tr>
</table>
<br>
<div align="center" style="padding: 5px;">
{site_main_ads}
</div>
