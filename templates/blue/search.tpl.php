<div class="top">
	<a href="./" class="link1">
		{home}
	</a>
</div>
<hr>
<div class="top">
	<b>Search Results For: </b>
	{words}
</div>
<hr>
<div class="top">
	<b>Found Categories: </b>
</div>
<div class="search_categories">
	{row categories}
		<a href="{category_url}[1]" class="link2">
			<u>{category_name}[1]</u>
		</a>, 
	{/row categories}
</div>
<hr>
<div class="top">
	<b>Found Listings: </b>
</div>
<table class="sites">
	{row sites}
		<tr>
 			<td class="{featured/normal}[1]">
  				<div style="float: left"> 
   					<p>&raquo; <a href="{address}[1]" class="link1" target="_blank"><u>{title}[1]</u></a> &laquo; (click to visit this site)</p>
   					<p>{description}[1]</p>
   					<p style="color: rgb(170, 34, 34);">www: {address}[1]</p>
   					<p> 
    				:: <a href="{more address}[1]" title="Site's Detail Statistics" class="infolink">[Site Details]</a> &nbsp; | &nbsp; <a href="#" onclick="window.open('broken.php?id={id}[1]&amp;s={categ}[1]', 'qlWeb', 'height=457,width=630,resizable=yes,scrollbars=yes');return false;" title="Report Incorrect Info/Broken Link" class="infolink">{broken}[1]</a> 
   					</p>
  				</div> 
 			</td> 
		</tr>
	{/row sites}
</table>
