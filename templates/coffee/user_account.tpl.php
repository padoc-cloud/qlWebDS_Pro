<table class="form_o">
	<tbody>
		<tr>
    		<td>
        		<table class="form_i">
         			<tbody>
           				<tr>
           					<td class="nag" colspan="3">{lang user section}</td>
          				</tr>
           				<tr>
    			            <td class="lCForm" style="text-align: center;" colspan="3">{lang user}: <i>{user value}</i></td>
       					 </tr>
				        <tr class="colored">
							<td class="lCForm" style="text-align: center;"><a href="index.php?page=user_account_edit"><u>{lang account edit}</u></a></td>
							<td class="lCForm" style="text-align: center;"><a href="index.php?page=user_claim_listing"><u>{lang claim listing}</u></a></td>
							<td class="lCForm" style="text-align: center;"><a href="./?page=logout"><u>Log Out</u></a></td>
           				</tr>
         			</tbody>
        		</table>
    		</td>
		</tr>
		<tr>
    		<td>
        		<table class="form_i">
         			<tbody>
           				<tr>
   							<td class="nag" colspan="2">{lang listing section}</td>
       					</tr>
					</tbody>
       			</table>
    		</td>
		</tr>
		<tr>
    		<td>
        		<table class="form_i">
         			<tbody>
	       				<tr class="colored">
 						    <td class="l_head"><a href="index.php?page=user_account&amp;ord=date&amp;sort={dsort}"><u>{lang listing date}</u></a></td>
						    <td class="l_head"><a href="index.php?page=user_account&amp;ord=url&amp;sort={usort}"><u>{lang listing URL}</u></a></td>
							<td class="l_head"><a href="index.php?page=user_account&amp;ord=company&amp;sort={csort}"><u>{lang listing company}</u></a></td>
						    <td class="l_head"><a href="index.php?page=user_account&amp;ord=status&amp;sort={ssort}"><u>{lang listing status}</u></a></td>
						    <td class="l_head">{lang listing action}</td>
					    </tr>
   					    {row sites}
	       				<tr>
 						    <td class="l_body">{date}[1]</td>
						    <td class="l_body"><a href="{url}[1]" target="_blank"><u>{url}[1]</u></a></td>
							<td class="l_body">{company}[1]</td>
						    <td class="l_body">{status}[1]</td>
						    <td class="l_body"><a href="index.php?page=listing_edit&amp;id={id}[1]" title="Edit"><u>{lang listing edit}</u></a></td>
					    </tr>
					    {/row sites}
       				</tbody>
				</table>
    		</td>
		</tr>
	</tbody>
</table>
