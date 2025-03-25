<table class="form_o">
<tbody>
 <tr>
 	<td>
 		<div class="info">{message}</div>
 	</td>
 </tr>
 <tr>
 	<td align="center">
  				<script type="text/javascript" language="JavaScript">
					function sendIt() {
					document.paypal_form.submit();
					}
				</script>
    			<center>
				<font color="#990000">
					<b>Thank You! Your Upgrade is awaiting a Payment Confirmation from PayPal.</b>
				</font>
					<br><br>
    			<font size="2" color="#339933">
					<u><b>To &nbsp;ACTIVATE&nbsp; your Listing:</b></u><br>
					Please Pay for your Submission Review now by Clicking the PayPal Button Below:
				</font>
					<br>
				<form action="https://www.paypal.com/cgi-bin/webscr" name="paypal_form" target="paypal" method="post">
        			<input type="hidden" name="cmd" value="_xclick">
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
        			<input src="https://www.paypal.com/en_US/i/bnr/horizontal_solution_PPeCheck.gif" type="image" name="submit" alt="Make Payments with PayPal. It's Fast, Free and Secure!" onload="sendIt()">
     			</form>
				</center>
 	</td>
 </tr>
 <tr>
 	<td style="text-align: center;">
 		<a href="?page=user_account"><u>Back to User Account</u></a>
 	</td>
 </tr>
</tbody>
</table>
