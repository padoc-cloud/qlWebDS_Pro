<script src="java_scripts/check_box.js" type="text/JavaScript" language="JavaScript">
</script>
<form action="{address}" method="post" enctype="multipart/form-data" id="a" name="register">
<table class="form_o">
<tbody>
	<tr>
		<td style="text-align: left;">
			<a href="./" title="{lang back}" class="link2">
				&laquo; <u>{lang back}</u>
			</a>
		</td>
		<td>&nbsp;</td>
	</tr>
 <tr>
    <td>
    {error}
        <table class="form_i">
         <tbody>
           <tr>
             <td class="nag" colspan="2">{registration title}<br><font color="#CC3300"><b>*</b></font> = <font color="#CC3300">{lang required}</font></td>
           </tr>
           <tr>
             <td class="lCForm">{lang user}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm"><input name="user" value="{user value}" maxlength="15" size="58" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang real_name}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm"><input name="real_name" value="{real_name value}" maxlength="255" size="58" type="text"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang pass}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm"><input name="pass" value="{pass value}" maxlength="32" size="58" type="password"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang pass_confirm}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm"><input name="pass_confirm" value="{pass_confirm value}" maxlength="32" size="58" type="password"></td>
           </tr>
		   <tr>
             <td class="lCForm">{lang email}:<font color="#CC3300">*</font></td>
             <td class="rCForm"><input name="email" value="{email value}" maxlength="255" size="58" type="text"></td>
           </tr>
		   <tr>
		   <td class="lCForm"> &nbsp; </td>
		   <td class="rCForm"><font color="#CC3300"><b> Optional Fields:</b></font></td>
		   </tr>
           <tr class="colored">
             <td class="lCForm">{lang company}:</td>
             <td class="rCForm"><input name="company" value="{company value}" maxlength="255" size="58" type="text"></td>
           </tr>  
           <tr>
             <td class="lCForm">{lang address}:</td>
             <td class="rCForm"><input name="address" value="{address value}" maxlength="255" size="58" type="text"></td>
           </tr>  
           <tr class="colored">
             <td class="lCForm">{lang city}:</td>
             <td class="rCForm"><input name="city" value="{city value}" maxlength="255" size="58" type="text"></td>
           </tr>  
           <tr>
             <td class="lCForm">{lang state}:</td>
             <td class="rCForm"><input name="state" value="{state value}" maxlength="200" size="58" type="text"></td>
           </tr>  
           <tr class="colored">
             <td class="lCForm">{lang zip}:</td>
             <td class="rCForm"><input name="zip" value="{zip value}" maxlength="255" size="58" type="text"></td>
           </tr>  
           <tr>
             <td class="lCForm">{lang country}:</td>
			 <td class="rCForm">{country}</td>
           </tr>  
           <tr class="colored">
             <td class="lCForm">{lang tel}:</td>
             <td class="rCForm"><input name="tel" value="{tel value}" maxlength="255" size="58" type="text"></td>
           </tr>  
           <tr>
             <td class="lCForm">{lang fax}:</td>
             <td class="rCForm"><input name="fax" value="{fax value}" maxlength="255" size="58" type="text"></td>
           </tr>
 <tr class="colored">
  <td class="lCForm"><br><br><br>TOS -<br>(terms&nbsp;of&nbsp;service):</td>
  <td class="rCForm" style="text-align: left;">
    By using this site, contacting, registering and/or submitting listing(s), I agree to the following: <a href="{submit_guidelines}" target="_blank" title="Guidelines &amp; TOS"><u>Guidelines &amp; Terms of Service</u></a>. 
	To waive any claim(s) relating to the inclusion, exclusion, placement, or deletion of any submission to this directory. 
	To grant royalty-free license to use, edit, publish, copy, modify or delete any submission and to grant editorial power over it's title(s), description(s) and META tags. 
	To receive e-mail(s) confirming submission(s) as well as occasional announcement, informational and/or promotional e-mails.
  </td>
 </tr>
			{if iscaptcha==1}
			<tr>
				<td class="lCForm"><br>Security Code:</td>
				<td style="text-align: center;">Code: <input name="captcha_refresh" value="Refresh" type="submit" class="button2"><br>{captcha img}<br><b>Not cAsE sensitive</b></td>
			</tr>
           <tr class="colored">
		   	<td class="lCForm">{captcha text}</td>
			<td class="rCForm" style="text-align: center; vertical-align: middle;"> <b><font color="#CC3300">&rarr;</font></b> {captcha input} &nbsp; {captcha text2}</td>
           </tr>
           {end iscaptcha}
<tr>
 <td class="lCForm" style="vertical-align: middle;"><br>Agree &amp; Submit:<font color="#CC3300"><b>*</b></font></td>
 <td class="rCForm" style="text-align: center;">
	I agree to the terms listed above <b><font color="#CC3300">&rarr;</font></b> <input type="checkbox" name="terms" id="terms" {terms_checked}><br><input name="submit" value="{lang submit}" type="submit" class="button2" onclick="return validate(terms);">
 </td>
</tr>
         </tbody>
        </table>
		{error}
    </td>
 </tr>
<tr>
<td style="text-align: left;">
	<a href="./" title="{lang back}" class="link2">
		&laquo; <u>{lang back}</u>
	</a>
</td>
</tr>
</tbody>
</table>
</form>
<script type="text/JavaScript" language="JavaScript">
	document.register.user.focus();
</script>
<p>
{ad_register}
<p>
