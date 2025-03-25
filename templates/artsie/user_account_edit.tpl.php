<form action="{address}" method="post" name="payform" enctype="multipart/form-data" id="a">
<table class="form_o">
<tbody>
 <tr>
    <td>
    {edit error}
        <table class="form_i">
         <tbody>
           <tr>
             <td class="nag">{lang title}</td>
           </tr>
         </tbody>
         </table>
        <table class="form_i">
         <tbody>
           <tr>
             <td class="nag" colspan="2"><font color="#CC3300"><b>*</b></font> = {lang required}</td>
           </tr>
           <tr>
             <td class="lCForm">{lang user}:</td>
             <td class="rCForm">{user value}</td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang real_name}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm"><input name="real_name" value="{real_name value}" maxlength="255" size="58" type="text"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang new_pass}:<font color="#CC3300">*</font></td>
             <td class="rCForm"><input name="pass" value="{pass value}" maxlength="32" size="58" type="password"></td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang new_pass_confirm}:<font color="#CC3300">*</font></td>
             <td class="rCForm"><input name="pass_confirm" value="{pass_confirm value}" maxlength="32" size="58" type="password"><label for="change_pass"> {change pass checkbox} </label><input type="checkbox" name="change_pass"></td>
           </tr>
		   <tr>
             <td class="lCForm">{lang email}:<font color="#CC3300">*</font></td>
             <td class="rCForm"><input name="email" value="{email value}" maxlength="255" size="58" type="text"></td>
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
          <tr class="tr2">
             <td class="lCForm">{lang country}:</td>
			 <td class="rCForm">{country}</td>
			 </td>
           </tr>
           <tr class="colored">
             <td class="lCForm">{lang tel}:</td>
             <td class="rCForm"><input name="tel" value="{tel value}" maxlength="255" size="58" type="text"></td>
           </tr>  
           <tr>
             <td class="lCForm">{lang fax}:</td>
             <td class="rCForm"><input name="fax" value="{fax value}" maxlength="255" size="58" type="text"></td>
           </tr>  
         </tbody>
        </table>
    </td>
 </tr>
<tr>
 <td class="rCForm" style="text-align: center;"><input name="submit" value="{lang submit}" type="submit" class="button2">&nbsp;&nbsp;<input name="cancel" value="{lang cancel}" type="button" class="button2" onClick="history.back()"></td>
</tr>
</tbody>
</table>
</form>
