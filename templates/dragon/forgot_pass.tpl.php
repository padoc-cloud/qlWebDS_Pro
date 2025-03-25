<table class="form_o">
	<tbody>
		<tr>
    		<td>
    			{error}
        		<form action="{address}" method="post" name="forgot_pass">
        			<table class="form_i">
         				<tbody>
           					<tr>
           						<td class="nag" colspan="2">{lang title}</td>
          					</tr>
				            <tr>
				                <td class="lCForm" style="text-align: center;">{lang email}:</td>
				            </tr>
				            <tr>
				                <td class="lCForm" style="text-align: center;"><input name="email" maxlength="255" size="58" type="text"></td>
				            </tr>
           					<tr>
             					<td class="lCForm" style="text-align: center;"><input name="submit" value="{lang button}" type="submit" class="button2"></td>
           					</tr>
							<tr>
								<td style="text-align: left;">
									<a href="JavaScript:history.back();" title="{lang back}" class="link2">
										&laquo; <u>{lang back}</u>
									</a>
								</td>
							</tr>
         				</tbody>
        			</table>
       			</form>
				<script type="text/JavaScript" language="JavaScript">
					document.forgot_pass.email.focus();
				</script>
    		</td>
		</tr>
	</tbody>
</table>
<p>
{ad_forgot_pass}
<p>
