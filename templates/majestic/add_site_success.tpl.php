<table class="site_info">
	<tr>
 		<td class="{featured/normal}">
  			<a href="./" class="link2">
				<u>{home}</u>
			</a>
  			<hr>
 		</td>
	</tr>
	<tr>
 		<td align="center">
 			<div>
  				{if site_new==1}
  				<div style="text-align: left; valign: top; width: 85%;">
					<table class="site_info">
						<tr>
							<td align="left" valign="middle">
    							<font color="#0066CC">
									<b>Thank You! &nbsp;Your Listing is Pending Approval.<br>
									Here are the Details of your Submission:</b>
								</font>
									<br><br><b><u>Your Title:</u>
								<font color="#0066CC">
									 {title}
								</font>
									<br><u>Category you Chose:</u>
								<font color="#0066CC"> {category}
								</font></b>
							</td>
						</tr>
						<tr>
							<td align="center" valign="middle">
								<span class="{url_disp}">
								<a href="{address}" target="_blank" title="{title}">
									<img src="{thumbnail_code_url}{address}" border="1" style="border-color: rgb(221, 221, 221);" alt="Thumbnail for {title}">
								</a>
								</span>
							</td>
						</tr>
						<tr>
							<td>
    							<div style="text-align: left; valign: top; width: 85%;">
    								<font color="#0066CC"><b><u>Your Description:</u></b>
									</font> 
										{description}<br>
								</div>
							</td>
						</tr>
					</table>
  				</div>
  				{end site_new}
  				{if site_new==2}
  				<div>
					<table>
						<tr>
							<td align="left" valign="middle">
    							<font color="#0066CC"><b>Thank You! &nbsp;Your Listing has been submitted.<br>
									Here are the Details of your Submission:</b>
								</font>
									<br><br><b><u>Your Title:</u>
								<font color="#0066CC">
								 	{title}
								</font>
									<br><u>Category you Chose:</u>
								<font color="#0066CC">
									 {category}
								</font></b>
							</td>
						</tr>
						<tr>
							<td align="center" valign="middle">
								<a href="{address}" target="_blank" title="{title}">
									<img src="{thumbnail_code_url}{address}" border="1" style="border-color: rgb(221, 221, 221);" alt="Thumbnail for {title}">
								</a>
							</td>
						</tr>
						<tr>
							<td>
    							<div style="text-align: left; valign: top; width: 85%;">
    								<font color="#0066CC"><b><u>Your Description:</u></b></font> {description}<br>
								</div>
							</td>
						</tr>
					</table>
  				</div>
  				{end site_new}
  				{if site_new==3}
  				<div style="text-align: left; valign: top; width: 85%;">
					<table class="site_info">
						<tr>
							<td align="left" valign="middle">
    							<font color="#0066CC">
									<b>Thank You! &nbsp;Your need to pay before you can continue with your Submission.<br>
									Here are the Details of your Submission:</b>
								</font>
									<br><br><b><u>Your Name or Company Name:</u>
								<font color="#0066CC">
									 {company}
								</font>
									<br><u>Category you Chose:</u>
								<font color="#0066CC"> {category}
								</font></b>
							</td>
						</tr>
						<tr>
							<td align="center" valign="middle">
								<span class="{url_disp}">
								<a href="{address}" target="_blank" title="{company}">
									<img src="{thumbnail_code_url}{address}" border="1" style="border-color: rgb(221, 221, 221);" alt="Thumbnail for {company}">
								</a>
								</span>
							</td>
						</tr>
					</table>
  				</div>
  				{end site_new}
  				{if site_old_added==1}
  				<div style="text-align: center; font-size: 13px; color: #009900; valign: top; width: 85%;">
    				<b>Thank You! &nbsp;Your Listing already exists in another Category.<br><br>
					This Listing was submitted to: {category}.</b>
  				</div>
  				{end site_old_added}
  				{if site_old_added==2}
  				<div style="text-align: center; font-size: 13px; color: #009900; valign: top; width: 85%;">
    				<b>Your Listing already exists in the Directory.<br>
					The Listing has also reached a maximum number of Categories allowed.<br><br>
					Please Submit a Different Listing. </b>
  				</div>    
  				{end site_old_added}
  				{if ispayment==1}
  				<script type="text/javascript" language="JavaScript">
					function sendIt() {
					document.paypal_form.submit();
					}
				</script>
    			<center>
				<font color="#990000">
					<b>Thank You! Your Listing is awaiting a Payment Confirmation from PayPal.</b>
				</font>
					<br><br>
    			<font size="2" color="#339933">
    				{if site_new==2}
					<u><b>To &nbsp;ACTIVATE&nbsp; your Listing:</b></u><br>
    				{end site_new}
					Please Pay for your Submission Review now by Clicking the PayPal Button Below:
				</font>
					<br><br><b>After your Payment is completed, you may Submit another Listing.</b>
				<form action="https://www.paypal.com/cgi-bin/webscr" name="paypal_form" target="paypal" method="post">
      				{if subscription==0}
        			<input type="hidden" name="cmd" value="_xclick">
      				{end subscription}
      				{if subscription==1}
        			<input type="hidden" name="cmd" value="_xclick-subscriptions">
      				{end subscription}
        			<input type="hidden" name="business" value="{paypal_account}">
        			<input type="hidden" name="item_name" value="Listing ID #{id} in {directory}">
        			<input type="hidden" name="item_number" value="{id}">
        			<input type="hidden" name="amount" value="{payment_amount}">
        			<input type="hidden" name="quantity" value="1">
        			<input type="hidden" name="no_shipping" value="1">
        			<input type="hidden" name="return" value="{return_addr}">
        			<input type="hidden" name="cancel_return" value="{cancel_return}">
        			<input type="hidden" name="notify_url" value="{notify_url}">
        			<input type="hidden" name="custom" value="{link_type}">
        			<input type="hidden" name="no_note" value="1">
        			<input type="hidden" name="email" value="{email}">
        			<input type="hidden" name="currency_code" value="{co_code}">
      				{if subscription==1}
         			<input type="hidden" name="a3" value="{payment_amount}">
        	 		<input type="hidden" name="p3" value="{subscription_period}">
         			<input type="hidden" name="t3" value="M">
        			<input type="hidden" name="src" value="1">
         			<input type="hidden" name="sra" value="1">
      				{end subscription}
        			<input src="https://www.paypal.com/en_US/i/bnr/horizontal_solution_PPeCheck.gif" type="image" name="submit" onload="sendIt()" alt="Make Payments with PayPal. It's Fast, Free and Secure!">
     			</form>
				</center>
  			{end ispayment}
 			</div>
 		</td>
	</tr> 
</table>
{message_success}
<p>
{ad_success}
<p>
