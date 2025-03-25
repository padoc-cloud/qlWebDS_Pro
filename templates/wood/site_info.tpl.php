<table class="site_info" cellpadding="0" cellspacing="2">
	<tr>
		<td class="{featured/normal}">
			<a href="./" title="{home}" class="link1"><u>{home}</u></a> &nbsp;&raquo; {title}
			<hr>
		</td>
	</tr>
	<tr>
		<td class="{featured/normal}">
			<div class="site" style="margin-top: 5px; 5px; border: 1px solid #000000; vertical-align: bottom;">
        	<span class="{url_disp}">
				&nbsp;&nbsp;<a href="{address}" target="_blank" title="Visit {address}" onclick="visit_count({id},'{home_url}');"><img src="{thumbnail_code_url}{address}" title="Thumbnail for {address}" border="0" alt="Thumbnail for {address}"></a>
				&nbsp;&nbsp;<a href="{alexa_code_url_traffic}{address}" title="View Detail Alexa Traffic and Stats for {title}">
				<script src="{alexa_code_url_java}{address}" type="text/JavaScript" language="JavaScript">
				</script>
				</a>
				&nbsp;&nbsp;<img src="{alexa_code_url_graph}" title="Alexa Traffic Ranking for {address}" width="268" height="100" border="0" alt="Alexa Traffic Stats for {title}">
				<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="#CC3300">&uarr;</font> Click to Visit <font color="#CC3300">&uarr;</font>
        	</span>
			</div>
		</td>
	</tr>
	<tr>
		<td class="{featured/normal}">
			<div class="site">
				<p class="{company_disp}"><span class="si_text">{lang comp_name}: </span>{company}</p>
				<p><span class="si_text"><b>{lang title}:</b></span> &raquo; <span class="{url_disp}"><a href="{address}" class="link1" target="_blank" onclick="visit_count({id},'{home_url}');"><u>{title}</u></a> &laquo; (click to visit)</span><span class="{title_only_disp}">{title}</span></p>
				<p class="{product_disp}"><span class="si_text">{lang prod_name}: </span>{product}</p>
      			<p><span class="si_text"><b>{lang url}:</b></span> <a href="{address}" class="link1" target="_blank" onclick="visit_count({id},'{home_url}');">{address}</a><span class="{url_disp}">
				<br>-&nbsp; <a href="#" onclick="window.open('broken.php?id={id}&amp;s={categ}', 'qlWeb', 'height=250,resizable=yes,scrollbars=yes,width=550');return false;" title="Report Incorrect Info/Broken Link" class="si_text"><u>{broken}</u></a></span></p>
				<p><span class="si_text"><b>{lang categories}:</b></span> {categories}</p>
				<p><span class="si_text"><b>{lang deep links}</b></span> {deep links}</p>
				<p><span class="si_text"><b>{lang description_site_info}:</b></span> {description}</p>
      			<p class="{facebook_url_disp}"><span class="si_text"><img src="./images/directory_banners/facebook_45x45.png" border="0" align="bottom" width="15" height="15" alt="Facebook Icon"> <b>Facebook URL: </b></span> <span class="{facebook_url_disp}"><a href="{facebook_url}" target="_blank" title="Visit {title}'s Facebook Account"><u>{facebook_url}</u></a></span></p>
      			<p class="{twitter_url_disp}"><span class="si_text"><img src="./images/directory_banners/twitter_45x45.png" border="0" align="bottom" width="15" height="15" alt="Twitter Icon"> <b>Twitter URL: </b></span> <span class="{twitter_url_disp}"><a href="{twitter_url}" target="_blank" title="Visit {title}'s Twitter Account"><u>{twitter_url}</u></a></span></p>
      			<p class="{youtube_url_disp}"><span class="si_text"><img src="./images/directory_banners/youtube_45x45.png" border="0" align="bottom" width="15" height="15" alt="YouTube Icon"> <b>YouTube URL: </b></span> <span class="{youtube_url_disp}"><a href="{youtube_url}" target="_blank" title="Watch {title}'s YouTube Video"><u>{youtube_url}</u></a></span></p>
				<p><span class="{addr_disp}">
				<b>Address:</b><br>
				{addr}<br>
				{city} {state} {zip} &nbsp; <i>{country}</i>
				</span></p>
				<p><span class="{tel_fax_disp}"><span class="{tel_disp}"><b>Tel.</b> {tel} </span><span class="{fax_disp}"><b>Fax</b> {fax} </span></span></p>
				<p>
				<span class="si_text"><b>{lang added}:</b></span> {date}&nbsp;
				<span class="{url_disp}"><span class="si_text"><b>Web Archives:</b></span> <a href="{wayback_machine_code_url}{address}" target="_blank" title="See how {address} Looked Before"><u>Wayback Machine</u></a></span>&nbsp;
			{if show_pagerank==1}
				<p>
				<span class="{url_disp}"><span class="si_text"><b>{pagerank}</b></span></span>
				</p>
			{end show_pagerank}
				<p><span class="si_text"><b>{lang id}:</b></span> {id} <span class="si_text"> &nbsp;<b>{lang visits}:</b></span> {visit_count}</p>
			{if logo_display==1}
				<p style="text-align: center;">
				<a href="{address}" target="_blank" title="Visit {address}" onclick="visit_count({id},'{home_url}');"><img src="{logo_source}" title="Visit {address}" border="0" alt="{address}'s Banner"></a>
				</p>
			{end logo_display}
				<p class="{embedded_video_title_disp}"><span class="si_text">{lang video_title}: </span>{embedded_video_title}</p>
				<p class="{embedded_video_code_disp}"><span class="si_text">{embedded_video_code}</span></p>
			</div>
  			{if isadmin==1}
				<p style="clear: both; text-align: right; color: #336600; border-color: #FF0000; background-color: #FFFFFF;"><b>{admin menu}</b></p>
			{end isadmin} 
		</td>
	</tr>
</table>
<div style="text-align: center; margin-top: 3px; margin-bottom: 3px; vertical-align: bottom; width: 95%;">
	{site_info_ads}
</div>
