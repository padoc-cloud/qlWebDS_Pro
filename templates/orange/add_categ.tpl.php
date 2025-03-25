<form action="{address}" method="post" name="add_category">
	<table class="form_o">
		<tbody>
			{error}
 			<tr>
    			<td>
        			<table class="form_i">
         				<tbody>
           					<tr>
             					<td class="nag" colspan="2">{add_category}<br><font color="#CC3300">*</font> = <font color="#CC3300">{lang required}</font></td>
           					</tr>
           					<tr>
             					<td class="lCForm">{name}:<font color="#CC3300">*</font></td>
             					<td class="rCForm"><input name="name" value="{name value}" maxlength="40" size="58" type="text"><br><font color="#CC3300">&uarr;</font> <b>C</b>apitalize the <b>F</b>irst <b>L</b>etter of <b>E</b>ach <b>W</b>ord</td>
           					</tr>
           					<tr class="colored">
             					<td class="lCForm"><br>{description}:</td>
             					<td class="rCForm"><textarea name="description" cols="58" rows="4" id="description">{description value}</textarea></td>
           					</tr> 
           					<tr>
             					<td class="lCForm">{keywords}:</td>
             					<td class="rCForm"><input name="keywords" value="{keywords value}" maxlength="255" size="58" type="text"></td>
           					</tr>
			{if iscaptcha==1}
			<tr class="colored">
				<td class="lCForm"><br>Security Code:</td>
				<td style="text-align: center;">Code: <input name="captcha_refresh" value="Refresh" type="submit" class="button2"><br>{captcha img}<br><b>Not cAsE sensitive</b></td>
			</tr>
           <tr>
		   	<td class="lCForm">{captcha text}</td>
			<td class="rCForm" style="text-align: center; vertical-align: middle;"> <b><font color="#CC3300">&rarr;</font></b> {captcha input} &nbsp; {captcha text2}</td>
           </tr>
           {end iscaptcha}
<tr class="colored">
 <td class="lCForm">Submit:<font color="#CC3300"><b>*</b></font></td>
 <td class="rCForm" style="text-align: center;">
	<input name="submit" value="{add_category}" type="submit" class="button2">
 </td>
</tr>
         </tbody>
        </table>
		{error}
    </td>
 </tr>
<tr>
<td style="text-align: left;">
	<a href="JavaScript:history.back();" title="{lang back}" class="link2">
		&laquo; <u>{back}</u>
	</a>
</td>
</tr>
		</tbody>
	</table>
</form>
<script type="text/JavaScript" language="JavaScript">
	document.add_category.name.focus();
</script>
<p>
{ad_add_category}
<p>
