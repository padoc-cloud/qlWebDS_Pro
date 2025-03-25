<table class="form_o">
	<tbody>
		<tr>
    		<td>
    			{error}
        		<form action="{address}" method="post" name="add_site">
        			<table class="form_i">
         				<tbody>
           					<tr>
           						<td class="nag" colspan="2">{lang add_site} to Category: <font color="#18562C"><b>{category}</b></font><br><font color="#CC3300"><b>*</b></font> = <font color="#CC3300">{lang required}</font></td>
          					</tr>
				            <tr>
				                <td class="lCForm">{lang company}:<font color="#CC3300">*</font></td>
				                <td class="rCForm" valign="middle"><input name="company" value="{company value}" maxlength="150" size="58" type="text"></td>
				            </tr>
           					<tr class="colored">
             					<td class="lCForm">{lang email}:<font color="#CC3300"><b>*</b></font>&nbsp;</td>
             					<td class="rCForm"><input name="email" value="{email value}" maxlength="255" size="58" type="text"></td>
           					</tr>
				            <tr>
				                <td class="lCForm"><br>{lang url}:</td>
             					<td class="rCForm"> &nbsp; {lang add listing no website}<br><input name="url" value="{url value}" maxlength="150" size="58" type="text"></td>
           					 </tr>
           					<tr class="colored">
             					<td class="lCForm">Submit:<font color="#CC3300"><b>*</b></font></td>
             					<td class="rCForm" style="text-align: center;"><input name="submit_step1" value="{lang next}" type="submit" class="button2"></td>
           					</tr>
           					<tr>
             					<td class="lCForm" colspan="2">
									<a href="JavaScript:history.back();" title="{lang back}" class="link2">
										&laquo; <u>{lang back}</u>
									</a>
								</td>
           					</tr>
         				</tbody>
        			</table>
       			</form>
				<script type="text/JavaScript" language="JavaScript">
					document.add_site.company.focus();
				</script>
    		</td>
		</tr>
	</tbody>
</table>
{message_step1}
<br><p>
{ad_step1}
<p>
