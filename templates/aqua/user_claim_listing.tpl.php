<form action="{address}" method="post" name="payform" enctype="multipart/form-data" id="a">
<table class="form_o">
<tbody>
 <tr>
    <td>
    {claim error}
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
           <tr class="colored">
             <td class="lCForm">{lang id_code}:<font color="#CC3300"><b>*</b></font></td>
             <td class="rCForm"><input name="id_code" value="{id_code value}" maxlength="64" size="58" type="text"></td>
           </tr>
           <tr>
             <td class="lCForm">{lang email}:<font color="#CC3300">*</font></td>
             <td class="rCForm"><input name="email" value="{email value}" maxlength="255" size="58" type="text"></td>
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
