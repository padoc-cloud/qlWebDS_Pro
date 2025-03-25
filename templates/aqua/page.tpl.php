<h3>{page title}</h3>
{if iscontactus==1}
<div>
	<font color="#CC3333">
		<b>{error}</b>
	</font>
 	<p>
	<form action="" method="post">
		<table class="form_o">
  			<tr>
				<td style="text-align: right;">
					<font color="#CC3300"><b>*</b></font>&nbsp;&nbsp;
				</td>
				<td>
					&nbsp;= <font color="#CC3300">{lang required}</font>
				</td>
			</tr>
  			<tr>
				<td>
					<label>
						Contact Reason:<font color="#CC3300"><b>*</b></font>
					</label>
				</td>
	  			<td>
       				<select name="title">
         				<option value=" " {sel_default}>--- Select Contact Reason ---</option>
         				<option value="Feedback: General Inquiry" label="General Inquiry" {sel_general}>General Inquiry</option>
         				<option value="Feedback: Advertising Inquiry" label="Advertising Inquiry" {sel_advertising}>Advertising Inquiry</option>
         				<option value="Feedback: Change My Listing's Info" label="Change My Listing's Info" {sel_change}>Change My Listing's Info</option>
         				<option value="Feedback: Reporting a Problem" label="Reporting a Problem" {sel_problem}>Reporting a Problem</option>
         				<option value="Feedback: Website Feedback" label="Website Feedback" {sel_feedback}>Website Feedback</option>
         				<option value="Feedback: Other" label="Other" {sel_other}>Other (please specify below)</option>
       				</select>
	  			</td>
  			</tr>
  			<tr>
				<td>
					Your Name:<font color="#CC3300"><b>*</b></font>
				</td>
				<td>
					<input type="text" name="name" style="width: 353px;" value="">
				</td>
			</tr>
  			<tr>
				<td>
					Message:<font color="#CC3300"><b>*</b></font>
				</td>
				<td>
					<textarea cols="55" rows="10" name="text">{message}</textarea>
				</td>
			</tr>
  			<tr>
				<td>
					Your E-mail:<font color="#CC3300"><b>*</b></font>
				</td>
				<td>
					<input type="text" name="mail" style="width: 353px;" value="{mail}">
				</td>
			</tr>
  			<tr>
				<td>
					Your Phone Number:
				</td>
				<td>
					<input type="text" name="phone" style="width: 353px;" value="{phone}">
				</td>
			</tr>
  			{if iscaptcha==1}
			<tr>
				<td>
					Security Code:
				</td>
				<td style="text-align: center;">
					<input name="captcha_refresh" value="Refresh" type="submit" class="button2"> Code:<br>{captcha img}
					<br>
					<b>Not cAsE sensitive</b>
				</td>
  			</tr>
  			<tr>
				<td>
					{captcha text}
				</td>
				<td style="text-align: center;">
					 <b><font color="#CC3300">&rarr;</font></b> {captcha input} &nbsp; {captcha text2}
				</td>
  			</tr>
  			{end iscaptcha}
  			<tr>
				<td style="vertical-align: middle;">
					Submit:<font color="#CC3300"><b>*</b></font>
				</td>
				<td style="text-align: center;">
					<input type="submit" value="Send" class="button2">
				</td>
			</tr>
		</table> 
	</form>
	<font color="#CC3333">
		<b>{error}</b>
	</font>
</div>
{end iscontactus}
{page content}
<p>
