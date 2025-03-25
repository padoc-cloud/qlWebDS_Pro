<script src="java_scripts/add_site_step2.js" type="text/JavaScript" language="JavaScript">
</script>
<form action="{address}" method="post" name="payform" enctype="multipart/form-data" id="a">
<table class="form_o">
<tbody>
 <tr>
    <td>
    {error}
        {if top_category==1}
        <div class="info">{top_category_info}</div>
        {end top_category}
		{message_step2}
		<p>
		<center>{ad_step2}</center>
		</p>
		<p> &nbsp; </p>
        <table class="form_i">
         <tbody>
			<tr>
				<td style="text-align: left;">
					<a href="JavaScript:history.back();" title="{lang back}" class="link2">
						&laquo; <u>{lang back}</u>
					</a>
				</td>
				<td> &nbsp; </td>
			</tr>
           <tr>
             <td class="nag"><b><font color="#CC3300"> &darr; Please Select Listing Type {lang subscription} &darr; </font></b></td>
			 <td class="nag">{currency}</td>
           </tr>
          <tr {style type[1]}>
            <td class="lCForm"><input id="type1" onClick="links53(false,false,{is_logo_1},{disp_reciprocal_1},{is_video_1});" name="link_type" value="1" type="radio" {checked type[1]}> <label for="type1">{link type1} {is_reciprocal_1}</label></td>
            <td class="rCForm">{payment type[1]}</td>
          </tr>
          <tr {style type[2]}>
            <td class="lCForm"><input id="type2" onClick="links53(false,false,{is_logo_2},{disp_reciprocal_2},{is_video_2});" name="link_type" value="2" type="radio" {checked type[2]}> <label for="type2">{link type2} {is_reciprocal_2}</label></td>
            <td class="rCForm">{payment type[2]}</td>
          </tr>
          <tr {style type[3]}>
            <td class="lCForm"><input id="type3" onClick="links53(false,false,{is_logo_3},{disp_reciprocal_3},{is_video_3});" name="link_type" value="3" type="radio" {checked type[3]}> <label for="type3">{link type3} {is_reciprocal_3}</label></td>
            <td class="rCForm">{payment type[3]}</td>
          </tr>
          <tr {style type[4]}>
            <td class="lCForm"><input id="type4" onClick="links53(false,false,{is_logo_4},{disp_reciprocal_4},{is_video_4});" name="link_type" value="4" type="radio" {checked type[4]}> <label for="type4">{link type4} {is_reciprocal_4}</label></td>
            <td class="rCForm">{payment type[4]}</td>
          </tr>
          <tr {style type[5]}>
            <td class="lCForm"><input id="type5" onClick="links53(false,false,{is_logo_5},{disp_reciprocal_5},{is_video_5});" name="link_type" value="5" type="radio" {checked type[5]}> <label for="type5">{link type5} {is_reciprocal_5}</label></td>
            <td class="rCForm">{payment type[5]}</td>
          </tr>
          <tr {style type[6]}>
            <td class="lCForm"><input id="type6" onClick="links53(true,false,{is_logo_6},{disp_reciprocal_6},{is_video_6});" name="link_type" value="6" type="radio" {checked type[6]}> <label for="type6">{link type6} {is_reciprocal_6}</label></td>
            <td class="rCForm">{payment type[6]}</td>
          </tr>
          <tr {style type[7]}>
            <td class="lCForm"><input id="type7" onClick="links53(true,true,{is_logo_7},{disp_reciprocal_7},{is_video_7});" name="link_type" value="7" type="radio" {checked type[7]}> <label for="type7">{link type7} {is_reciprocal_7}</label></td>
            <td class="rCForm">{payment type[7]}</td>
          </tr>
          <tr {style type[8]}>
            <td class="lCForm"><input id="type8" onClick="links53(false,false,{is_logo_8},{disp_reciprocal_8},{is_video_8});" name="link_type" value="8" type="radio" {checked type[8]}> <label for="type8">{link type8} {is_reciprocal_8}</label></td>
            <td class="rCForm">{payment type[8]}</td>
          </tr>
          <tr {style type[9]}>
            <td class="lCForm"><input id="type9" onClick="links53(false,false,{is_logo_9},{disp_reciprocal_9},{is_video_9});" name="link_type" value="9" type="radio" {checked type[9]}> <label for="type9">{link type9} {is_reciprocal_9}</label></td>
            <td class="rCForm">{payment type[9]}</td>
          </tr>
         </tbody>
         </table>
        <table class="form_i">
         <tbody>
           {if pay_and_step2==1}
           <tr>
             <td class="nag" colspan="2">{lang add_site} to Category: <font color="#336600">{category}</font><br><b><font color="#CC3300">*</font> = <font color="#CC3300">{lang required}</font></b></td>
           </tr>
           <tr>
             <td class="lCForm">{lang listing_title}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm">
				<script src="java_scripts/count_title_length.js" type="text/JavaScript" language="JavaScript">
				</script>
				<input name="title" value="{title value}" maxlength="125" size="62" type="text" 
				onKeyDown="Count(this.form.title,this.form.left,{max_title_lenght});" 
				onKeyUp="Count(this.form.title,this.form.left,{max_title_lenght});">
             </td>
		   </tr>
		   <tr>
             <td class="lCForm" valign="top">
				 &nbsp;<a href="{url value}" target="_blank" title="Check Your Site"><img src="{thumbnail_code_url}{url value}" title="Thumbnail" border="0" width="90" height="50" alt="Thumbnail"></a>
             </td>
             <td class="rCForm" valign="top">
				<input readonly type="text" name="left" size="3" maxlength="3" value="{max_title_lenght}"> characters remaining<br>
				<font color="#CC3300">&uarr;</font> Title must be between 3 and {max_title_lenght} characters<br><font color="#CC3300">&uarr;</font> <b>C</b>apitalize the <b>F</b>irst <b>L</b>etter of <b>E</b>ach <b>W</b>ord
			 </td>
		   </tr>
		   {end pay_and_step2}
           <tr class="colored">
             <td class="lCForm">{lang url}:</td>
             <td class="rCForm"><input name="url" value="{url value}" maxlength="200" size="62" type="text" {url readonly}></td>
           </tr>
           {if pay_and_step2==1}
   		   <tr class="colored">
			 <td class="lCForm"> &nbsp; </td>
			 <td class="rCForm"><font color="#CC3300">&uarr;</font> <b>No Subfolders or Deep Links here</b>
			 <br>&nbsp;What is a Deep Link? 
				<a href="#" onMouseOver="setVisible('deep_link_expl', true, event); return false;" onMouseOut="setVisible('deep_link_expl', false, event); return false;">
					&nbsp;<img src="images/directory_banners/info.gif" border="0" alt="Info">
				</a>
			 	<div class="info_div" style="display: none; vertical-align: middle;" id="info_deep_link_expl" onMouseOut="setVisible('deep_link_expl', false, event); return false;">
				{deep_link_expl}
				</div>
			 </td>
			</tr>
          </tbody>
          <tbody id="links3" name="links3" style="display: none;">
   		   <tr class="colored">
			 <td class="lCForm"> &nbsp; </td>
			 <td class="rCForm"><font color="#CC3300">&uarr;</font> To include deep links: select 3 or 5 deep links above</td>
		   </tr>
           <tr>
             <td class="lCForm">{lang title} 1:</td>
             <td class="rCForm"><input name="title1" value="{title1 value}" maxlength="100" size="62" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm"> &nbsp; {deep_link_1}:</td>
             <td class="rCForm"><input name="url1" value="{url1 value}" maxlength="200" size="62" type="text"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang title} 2:</td>
             <td class="rCForm"><input name="title2" value="{title2 value}" maxlength="100" size="62" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm"> &nbsp; {deep_link_2}:</td>
             <td class="rCForm"><input name="url2" value="{url2 value}" maxlength="200" size="62" type="text"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang title} 3:</td>
             <td class="rCForm"><input name="title3" value="{title3 value}" maxlength="100" size="62" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm"> &nbsp; {deep_link_3}:</td>
             <td class="rCForm"><input name="url3" value="{url3 value}" maxlength="200" size="62" type="text"></td>
           </tr>
          </tbody>
          <tbody id="links5" name="links5" style="display: none;"> 
           <tr>
             <td class="lCForm">{lang title} 4:</td>
             <td class="rCForm"><input name="title4" value="{title4 value}" maxlength="100" size="62" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm"> &nbsp; {deep_link_4}:</td>
             <td class="rCForm"><input name="url4" value="{url4 value}" maxlength="200" size="62" type="text"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang title} 5:</td>
             <td class="rCForm"><input name="title5" value="{title5 value}" maxlength="100" size="62" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm"> &nbsp; {deep_link_5}:</td>
             <td class="rCForm"><input name="url5" value="{url5 value}" maxlength="200" size="62" type="text"></td>
           </tr>
          </tbody>
           <tr>
             <td class="lCForm">{lang description_short}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm">
				<script src="java_scripts/count_description_short_length.js" type="text/JavaScript" language="JavaScript">
				</script>
				<textarea name="description_short" cols="62" rows="3" id="description_short" onKeyDown="Count(this.form.description_short,this.form.left1,{max_description_short_lenght});" onKeyUp="Count(this.form.description_short,this.form.left1,{max_description_short_lenght});">{description_short value}</textarea> 
				<br><input readonly type="text" name="left1" size="4" maxlength="4" value="{max_description_short_lenght}"> characters remaining
			 </td>
           </tr>
		   <tr>
			 <td class="lCForm"> &nbsp; </td>
			 <td class="rCForm"><font color="#CC3300">&uarr;</font> Short Description must be between {min_description_short_lenght} and {max_description_short_lenght} characters</td>
		   </tr>
           <tr class="colored">
             <td class="lCForm">{lang description}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm">
				<script src="java_scripts/count_description_length.js" type="text/JavaScript" language="JavaScript">
				</script>
				<textarea name="description" cols="62" rows="14" id="description" onKeyDown="Count(this.form.description,this.form.left2,{max_description_lenght});" onKeyUp="Count(this.form.description,this.form.left2,{max_description_lenght});">{description value}</textarea> 
				<br><input readonly type="text" name="left2" size="4" maxlength="4" value="{max_description_lenght}"> characters remaining
			 </td>
           </tr>
		   <tr class="colored">
			 <td class="lCForm"> &nbsp; </td>
			 <td class="rCForm"><font color="#CC3300">&uarr;</font> Long Description must be between {min_description_lenght} and {max_description_lenght} characters</td>
		   </tr>
           <tr>
             <td class="lCForm">{lang keywords}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm">
				<script src="java_scripts/count_keywords_length.js" type="text/JavaScript" language="JavaScript">
				</script>
				<input name="keywords" value="{keywords value}" maxlength="75" size="62" type="text" 
				onKeyDown="Count(this.form.keywords,this.form.left3,58);" 
				onKeyUp="Count(this.form.keywords,this.form.left3,58);">
				<br><input readonly type="text" name="left3" size="3" maxlength="3" value="58"> characters remaining
			 </td>
		   </tr>
		   <tr>
			 <td class="lCForm"> &nbsp; </td>
			 <td class="rCForm"><font color="#CC3300">&uarr;</font> {lang keywords note}</td>
           </tr>
           <tbody id="reciprocal_section" name="reciprocal_section" style="{reciprocal_display}"> 
           <tr class="colored">
             <td class="lCForm">{lang reciprocal} URL:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm"><input name="reciprocal" value="{reciprocal value}" maxlength="200" size="62" type="text"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang reciprocal}:</td>
             <td class="rCForm"><textarea readonly="readonly" name="my_reciprocal" cols="62" rows="3" id="my_reciprocal">{my_reciprocal value}</textarea></td>
           </tr>
           </tbody>
           {end pay_and_step2}
           <tr class="colored">
             <td class="lCForm">{lang company}:<font color="#CC3300">*</font></td>
             <td class="rCForm"><input name="company" value="{company value}" maxlength="200" size="62" type="text" readonly="readonly"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang email}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm"><input name="email" value="{email value}" maxlength="255" size="62" type="text" {email readonly}></td>
           </tr>
           {if pay_and_step2==1}
   		   <tr>
             <td class="nag">Optional Fields:</td>
             <td class="nag"> &nbsp; </td>
		   </tr>
           <tr>
             <td class="lCForm"> &nbsp; </td>
			 <td class="rCForm"><font color="#CC3300"> &nbsp;<b>Additional URLs:</b></font></td>
           </tr>
           <tr>
             <td class="lCForm">{lang facebook_url}:</td>
             <td class="rCForm"><input name="facebook_url" value="{facebook_url value}" maxlength="200" size="62" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang twitter_url}:</td>
             <td class="rCForm"><input name="twitter_url" value="{twitter_url value}" maxlength="200" size="62" type="text"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang youtube_url}:</td>
             <td class="rCForm"><input name="youtube_url" value="{youtube_url value}" maxlength="200" size="62" type="text"></td>
           </tr>
           <tbody id="video_section" name="video_section" style="{video_display}">
   		   <tr class="colored">
             <td class="lCForm"> &nbsp; </td>
             <td class="rCForm"><font color="#CC3300"> &nbsp;<b>Embedded Video:</b></font></td>
		   </tr>
           <tr>
             <td class="lCForm">{lang embedded_video_title}:</td>
             <td class="rCForm"><input name="embedded_video_title" value="{embedded_video_title value}" maxlength="58" size="62" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang embedded_video_code}:</td>
             <td class="rCForm"><textarea name="embedded_video_code" cols="62" rows="3" id="embedded_video_code">{embedded_video_code value}</textarea></td>
           </tr>
           </tbody>
   		   <tr>
             <td class="lCForm"> &nbsp; </td>
             <td class="rCForm"><font color="#CC3300"> &nbsp;<b>Product/Service Provided:</b></font></td>
		   </tr>
           <tr class="colored">
             <td class="lCForm">{lang product}:</td>
             <td class="rCForm"><input name="product" value="{product value}" maxlength="200" size="62" type="text"></td>
           </tr>
   		   <tr>
             <td class="lCForm"> &nbsp; </td>
             <td class="rCForm"><font color="#CC3300"> &nbsp;<b>Address/Location:</b></font></td>
		   </tr>
           <tr>
             <td class="lCForm">{lang address}:</td>
             <td class="rCForm"><input name="address" value="{address value}" maxlength="200" size="62" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang city}:</td>
             <td class="rCForm"><input name="city" value="{city value}" maxlength="200" size="50" type="text"></td>
           </tr>  
           <tr>
             <td class="lCForm">{lang state}:</td>
             <td class="rCForm"><input name="state" value="{state value}" maxlength="200" size="45" type="text"></td>
           </tr>  
           <tr class="colored">
             <td class="lCForm">{lang zip}:</td>
             <td class="rCForm"><input name="zip" value="{zip value}" maxlength="200" size="35" type="text"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang country}:</td>
			 <td class="rCForm">{country}</td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang tel}:</td>
             <td class="rCForm"><input name="tel" value="{tel value}" maxlength="200" size="40" type="text"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang fax}:</td>
             <td class="rCForm"><input name="fax" value="{fax value}" maxlength="200" size="40" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang note}:</td>
             <td class="rCForm"><textarea name="note" cols="62" rows="4" id="note">{note value}</textarea></td>
           </tr>
           <tbody id="logo_section" name="logo_section" style="{logo_display}">
           <tr>
             <td class="lCForm">{lang upload_logo}:</td>
             <td class="rCForm"><input name="logo_upload" type="file" maxlength="200" size="65" accept="image/gif, image/jpeg"><br><font color="#CC3300">&uarr;</font> Maximum Size: 125 kB</td>
           </tr>
           </tbody>
           {end pay_and_step2}
 {if pay_and_step1_2==1}
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
				<td style="text-align: center;">Code: <input name="{captcha refresh button}" value="Refresh" type="submit" class="button2"><br>{captcha img}<br><b>Not cAsE sensitive</b></td>
			</tr>
           <tr class="colored">
		   	<td class="lCForm">{captcha text}</td>
			<td class="rCForm" style="text-align: center; vertical-align: middle;"> <b><font color="#CC3300">&rarr;</font></b> {captcha input} &nbsp; {captcha text2}</td>
           </tr>
           {end iscaptcha}
<tr>
 <td class="lCForm" style="vertical-align: middle;">Agree &amp; Submit:<font color="#CC3300"><b>*</b></font></td>
 <td class="rCForm" style="text-align: center;">
	<script src="java_scripts/check_box.js" type="text/JavaScript" language="JavaScript">
	</script>
	I agree to the terms listed above <b><font color="#CC3300">&rarr;</font></b> <input type="checkbox" name="terms" id="terms" {terms_checked}>
<br><input name="{submit button}" value="{lang submit}" type="submit" class="button2" onclick="return validate(terms);"></td>
</tr>
{end pay_and_step1_2}
{if pay_and_step2==1}
<tr {second submit button display}>
 <td class="lCForm" style="vertical-align: middle;">Submit:</td>
 <td class="rCForm" style="text-align: center;">
	<input name="{submit button}" value="{lang submit}" type="submit" class="button2">
 </td>
</tr>
{end pay_and_step2}
</tbody>
</table>
{error}
</td>
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
