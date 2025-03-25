<table class="form_o">
	<tbody>
		<tr>
    		<td>
    			{error}
        		<form action="{address}" method="post" name="login">
        			<table class="form_i">
         				<tbody>
           					<tr>
             					<td class="nag" colspan="2">{lang login title}<br><font color="#CC3300">*</font> = <font color="#CC3300">{lang required}</font></td>
           					</tr>
				            <tr>
				                <td class="lCForm">{lang user}:<font color="#CC3300"><b>*</b></font></td>
				                <td class="rCForm"><input name="user" maxlength="15" size="58" type="text"></td>
				            </tr>
					        <tr class="colored">
    				            <td class="lCForm">{lang pass}:<font color="#CC3300"><b>*</b></font></td>
					            <td class="rCForm"><input name="pass" maxlength="32" size="58" type="password"></td>
           					 </tr>
           					<tr>
             					<td class="lCForm">Submit:<font color="#CC3300"><b>*</b></font></td>
             					<td class="rCForm" style="text-align: center;"><input name="submit" value="{lang login button}" type="submit" class="button2"></td>
           					</tr>
					        <tr class="colored">
             					<td class="lCForm">{lang title}</td>
             					<td class="rCForm" style="text-align: center; vertical-align: middle;"><a href="index.php?page=forgot_pass"><u>{lang forgot pass}</u></a></td>
          					</tr>
<tr>
<td class="lCForm" colspan="2">
	<a href="./" title="{lang back}" class="link2">
		&laquo; <u>{lang back}</u>
	</a>
</td>
</tr>
         				</tbody>
        			</table>
       			</form>
				<script type="text/JavaScript" language="JavaScript">
					document.login.user.focus();
				</script>
    		</td>
		</tr>
	</tbody>
</table>
<p>
{ad_login}
<p>
