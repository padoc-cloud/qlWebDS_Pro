<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>Report Broken Link/Incorrect Information</title>
			<meta name="robots" content="noindex, nofollow">
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
		{if done==0}
		{error}
		<form action="" method="POST" name="broken">
		<table align="center">
			<tr><td align="center" colspan="2"> &nbsp; </td></tr>
			<tr><td align="center" colspan="2"><b>Do you want to report a broken link or other incorrect data?</b></td></tr>
			<tr><td align="center" colspan="2">
					Comment: (optional)<br> <textarea cols="60" rows="5" name="comment">{comment value}</textarea><br><br>
			</td></tr>
			{if iscaptcha==1}
			<tr>
				<td><br>Security Code:</td>
				<td style="text-align: center;">Code: <input name="captcha_refresh" value="Refresh" type="submit" class="button2"><br>{captcha img}<br><b>Not cAsE sensitive</b></td>
 			</tr>
            <tr>
			   	<td>{captcha text}</td>
				<td style="text-align: center; vertical-align: middle;"> <b><font color="#CC3300">&rarr;</font></b> {captcha input} &nbsp; {captcha text2}</td>
            </tr>
            {end iscaptcha}
			<tr><td align="center" colspan="2">
					<input type="submit" id="yes" name="yes" value="Submit">
					<input type="submit" id="no" name="no" value="Exit" onClick="JavaScript:self.close()">
			</td></tr>
		</table>
		</form>
		<script type="text/JavaScript" language="JavaScript">
			document.broken.comment.focus();
		</script>
		{end done}
		{if done==1}
		<table align="center">
			<tr><td align="center"> &nbsp; </td></tr>
			<tr><td align="center"> &nbsp; </td></tr>
			<tr><td align="center">Thank You for Your Report!</td></tr>
			<tr><td align="center"><a href=JavaScript:window.close();>Close</a></td></tr>
		</table>
		{end done}
	</body>
</html>
