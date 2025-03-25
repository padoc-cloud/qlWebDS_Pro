<script src="java_scripts/listing_edit.js" type="text/JavaScript" language="JavaScript">
</script>
<form action="{address}" method="post" name="payform" enctype="multipart/form-data" id="a">
<table class="form_o">
<tbody>
 <tr>
    <td>
    {error}
    	<span {payment style}>
        <table class="form_i">
         <tbody>
           <tr>
             <td class="nag">{lang edit listing}<br><br>Please <font color="#336600"><b>Select</b></font> Listing Type If You Want To Upgrade To Featured Listing</td><td class="nag"><br><br>{currency}</td> 
           </tr>
          <tr {style type[1]}>
            <td class="lCForm"><input id="type1" onClick="links53(false,false,{is_logo_1});" name="link_type" value="1" type="radio" {checked type[1]}> <label for="type1">{link type1} {is_reciprocal_1}</label></td>
            <td class="rCForm">{payment type[1]}</td>
          </tr>
          <tr {style type[2]}>
            <td class="lCForm"><input id="type2" onClick="links53(false,false,{is_logo_2});" name="link_type" value="2" type="radio" {checked type[2]}> <label for="type2">{link type2} {is_reciprocal_2}</label></td>
            <td class="rCForm">{payment type[2]}</td>
          </tr>
          <tr {style type[3]}>
            <td class="lCForm"><input id="type3" onClick="links53(false,false,{is_logo_3});" name="link_type" value="3" type="radio" {checked type[3]}> <label for="type3">{link type3} {is_reciprocal_3}</label></td>
            <td class="rCForm">{payment type[3]}</td>
          </tr>
          <tr {style type[4]}>
            <td class="lCForm"><input id="type4" onClick="links53(false,false,{is_logo_4});" name="link_type" value="4" type="radio" {checked type[4]}> <label for="type4">{link type4} {is_reciprocal_4}</label></td>
            <td class="rCForm">{payment type[4]}</td>
          </tr>
          <tr {style type[5]}>
            <td class="lCForm"><input id="type5" onClick="links53(false,false,{is_logo_5});" name="link_type" value="5" type="radio" {checked type[5]}> <label for="type5">{link type5} {is_reciprocal_5}</label></td>
            <td class="rCForm">{payment type[5]}</td>
          </tr>
          <tr {style type[6]}>
            <td class="lCForm"><input id="type6" onClick="links53(true,false,{is_logo_6});" name="link_type" value="6" type="radio" {checked type[6]}> <label for="type6">{link type6} {is_reciprocal_6}</label></td>
            <td class="rCForm">{payment type[6]}</td>
          </tr>
          <tr {style type[7]}>
            <td class="lCForm"><input id="type7" onClick="links53(true,true,{is_logo_7});" name="link_type" value="7" type="radio" {checked type[7]}> <label for="type7">{link type7} {is_reciprocal_7}</label></td>
            <td class="rCForm">{payment type[7]}</td>
          </tr>
         </tbody>
         </table>
         </span>
        <table class="form_i">
         <tbody>
           <tr>
             <td class="nag" colspan="2"><font color="#CC3300"><b>*</b></font> = required fields</td>
           </tr>
           <tr>
             <td class="lCForm">{lang title}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm"><input name="title" value="{title value}" maxlength="125" size="58" type="text"></td>
		   <tr>
			 <td class="lCForm">&nbsp;</td>
			 <td class="rCForm"><font color="#CC3300">&uarr;</font> Title must be between 3 and {max_title_lenght} characters<br><font color="#CC3300">&uarr;</font> <b>C</b>apitalize the <b>F</b>irst <b>L</b>etter of <b>E</b>ach <b>W</b>ord</td>
		   </tr>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang url}:</td>
             <td class="rCForm"><input name="url" value="{url value}" maxlength="200" size="58" type="text"></td>
   		   <tr>
			 <td class="lCForm">&nbsp;</td>
			 <td class="rCForm"><font color="#CC3300">&uarr;</font> <b>No Subfolders or Deep Links here!</b></td>
		   </tr>
           </tr>
          </tbody>
          <tbody id="links3" name="links3" style="display: none;">
           <tr>
             <td class="lCForm">{lang title} 1:</td>
             <td class="rCForm"><input name="title1" value="{title1 value}" maxlength="100" size="58" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang url} 1:</td>
             <td class="rCForm"><input name="url1" value="{url1 value}" maxlength="200" size="58" type="text"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang title} 2:</td>
             <td class="rCForm"><input name="title2" value="{title2 value}" maxlength="100" size="58" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang url} 2:</td>
             <td class="rCForm"><input name="url2" value="{url2 value}" maxlength="200" size="58" type="text"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang title} 3:</td>
             <td class="rCForm"><input name="title3" value="{title3 value}" maxlength="100" size="58" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang url} 3:</td>
             <td class="rCForm"><input name="url3" value="{url3 value}" maxlength="200" size="58" type="text"></td>
           </tr>
          </tbody>
          <tbody id="links5" name="links5" style="display: none;"> 
           <tr>
             <td class="lCForm">{lang title} 4:</td>
             <td class="rCForm"><input name="title4" value="{title4 value}" maxlength="100" size="58" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang url} 4:</td>
             <td class="rCForm"><input name="url4" value="{url4 value}" maxlength="200" size="58" type="text"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang title} 5:</td>
             <td class="rCForm"><input name="title5" value="{title5 value}" maxlength="100" size="58" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang url} 5:</td>
             <td class="rCForm"><input name="url5" value="{url5 value}" maxlength="200" size="58" type="text"></td>
           </tr>
          </tbody>
          <tbody> 
           <tr>
             <td class="lCForm">{lang description}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm"><textarea name="description" cols="58" rows="12" id="description">{description value}</textarea></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang keywords}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm"><input name="keywords" value="{keywords value}" maxlength="85" size="58" type="text"></td>
		   </tr>
		   <tr>
			 <td class="lCForm">&nbsp;</td>
			 <td class="rCForm"><font color="#CC3300">&uarr;</font> {lang keywords note}</td>
           </tr>  
           <tr class="colored">
             <td class="lCForm">{lang company}:<font color="#CC3300">*</font></td>
             <td class="rCForm"><input name="company" value="{company value}" maxlength="200" size="58" type="text" readonly="readonly"></td>
           </tr>  
           <tr>
             <td class="lCForm">{lang product}:</td>
             <td class="rCForm"><input name="product" value="{product value}" maxlength="200" size="58" type="text"></td>
           </tr>  
           <tr class="colored">
             <td class="lCForm">{lang address}:</td>
             <td class="rCForm"><input name="address" value="{address value}" maxlength="200" size="58" type="text"></td>
           </tr>  
           <tr>
             <td class="lCForm">{lang city}:</td>
             <td class="rCForm"><input name="city" value="{city value}" maxlength="200" size="58" type="text"></td>
           </tr>  
           <tr class="colored">
             <td class="lCForm">{lang state}:</td>
             <td class="rCForm"><input name="state" value="{state value}" maxlength="200" size="58" type="text"></td>
           </tr>  
           <tr>
             <td class="lCForm">{lang zip}:</td>
             <td class="rCForm"><input name="zip" value="{zip value}" maxlength="200" size="58" type="text"></td>
           </tr> 
           <tr class="colored">
             <td class="lCForm">{lang country}:</td>
			 <td class="rCForm">{country}</td>
           </tr>
           <tr>
             <td class="lCForm">{lang tel}:</td>
             <td class="rCForm"><input name="tel" value="{tel value}" maxlength="200" size="58" type="text"></td>
           </tr>  
           <tr class="colored">
             <td class="lCForm">{lang fax}:</td>
             <td class="rCForm"><input name="fax" value="{fax value}" maxlength="200" size="58" type="text"></td>
           </tr>  
           <tr>
             <td class="lCForm">{lang email}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm"><input name="email" value="{email value}" maxlength="255" size="58" type="text"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang reciprocal} URL:</td>
             <td class="rCForm"><input name="reciprocal" value="{reciprocal value}" maxlength="200" size="58" type="text"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang reciprocal}:</td>
             <td class="rCForm"><textarea readonly="readonly" name="my_reciprocal" cols="58" rows="3" id="my_reciprocal">{my_reciprocal value}</textarea></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang note}:</td>
             <td class="rCForm"><textarea name="note" cols="58" rows="6" id="note">{note value}</textarea></td>
           </tr>
           <tbody id="logo_section" name="logo_section" style="display: none;"> 
           <tr>
             <td class="lCForm">{lang logo_upload}:</td>
             <td class="rCForm"><input name="logo_upload" type="file" maxlength="200" size="55" accept="image/gif, image/jpeg"></td>
           </tr>
           </tbody>
         </tbody>
        </table>
		{error}
    </td>
 </tr>
<tr>
 <td class="rCForm" style="text-align: center;"><input name="submit" value="{lang submit}" type="submit" class="button2">&nbsp;&nbsp;<input name="inactivate" value="{lang inactivate}" type="submit" class="button2" {inactivate style} onClick="return confirmDelete('{inactivate confirmation}');">&nbsp;&nbsp;<input name="cancel" value="{lang cancel}" type="button" class="button2" onClick="history.back()"></td>
</tr>
</tbody>
</table>
</form>
