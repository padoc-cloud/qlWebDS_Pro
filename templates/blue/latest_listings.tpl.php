<p>
<div style="width: 100%; text-align: left;">
<div class="ql_pagging" style="margin-top: 10px; margin-bottom: 10px;">
	{lang title}
</div>
<div class="image_latest_most">
<table class="sites" width="100%">
		{row sites}
		<tr>
 			<td class="{featured/normal}[1]">
  				<table style="border: 1px solid #3B4350;" cellpadding="1" cellspacing="1" width="100%">
  					<tr>
  						<td style="border: 0px;" align="left">
							<span class="{image_disp}[1]">
								<a href="{address}[1]" target="_blank" title="Visit {address}[1]" onclick="visit_count({id}[1],'{home_url}[1]');">
									<img src="{thumbnail_code_url}{address}[1]" title="Thumbnail for {address}[1]" style="border-color: #DC913E; border: 1px;" alt="Thumbnail for {title}[1]">
									<br>&nbsp; &nbsp;<font color="#CC3300">&uarr;</font> Click to Visit <font color="#CC3300">&uarr;</font>
								</a>
  							</span>
  						</td>
  						<td style="border: 0px;" align="left" width="100%">
  						    <p class="{company_disp}[1]">&raquo; {company}[1]</p>
   							<p>&raquo; <span class="{url_disp}[1]"><a href="{address}[1]" class="link1" target="_blank" title="Visit this Site" onclick="visit_count({id}[1],'{home_url}[1]');"><u>{title}[1]</u></a> &laquo; click to visit</span><span class="{title_only_disp}[1]">{title}[1]</span></p>
    						<p style="color: #23559C;"><span class="{url_disp}[1]"> &nbsp; &nbsp;{address}[1]</span></p>
   							<p>
    						-&nbsp; 
    						<span class="{url_disp}[1]"><a href="{more address}[1]" title="Listing's Detail Statistics" class="infolink"> [View Details]</a>
							<br>-&nbsp; <a href="#" onclick="window.open('broken.php?id={id}[1]&amp;s={categ}[1]', 'qlWeb', 'height=457,width=630,resizable=yes,scrollbars=yes');return false;" title="Report Incorrect Info/Broken Link" class="infolink" rel="nofollow">{broken}[1]</a></span>
    						<span class="{detail_disp}[1]"><a href="{more address}[1]" title="Listing's Details" class="infolink"> [View Details]</a></span>
   							</p>
							<span class="{addr_disp}[1]"><br>
							<b>Address:</b>
							{addr}[1]<br>
							{city}[1] {state}[1] {zip}[1] &nbsp; <i>{country}[1]</i>
							</span>
							<p><span class="{tel_fax_disp}[1]"><span class="{tel_disp}[1]"><b>Tel.</b> {tel}[1] </span><span class="{fax_disp}[1]"><b>Fax</b> {fax}[1] </span></span></p>
  						</td>
  					</tr>
  				</table> 
 			</td> 
		</tr>
	{/row sites}
</table>
</div>
</div>
