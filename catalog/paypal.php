<?php

  if (isset($_GET['t']) ) {
    $t = $_GET['t'];
  }
  
  if (isset($_GET['id']) ) {
    $id = $_GET['id'];
  }
  
  if (isset($_GET['cat_id']) ) {
    $cat_id = $_GET['cat_id'];
  }
  
  switch ($t) {
    case 'cancel':
      $tpl_main = '<br><br><br><b>Your Payment was Cancelled. Your Listing will be Deleted. <br><br>If you still want your Listing to appear in our Directory, please close this window/tab and proceed to PayPal again. <br>Thank You!</b>';
      break;

    case 'return':

	      // get templates
	      $tmp_tpl = new TemplateClass(); 
	      $tmp_tpl->SetTemplate(DIR_TEMPLATE.'infos.tpl.php');
	        
      	  $tpl_pay_array['{address}'] = CATALOG_ADDRESS.'index.php?pay=paypal&id='.$id.'&cat_id='.$cat_id;
      	  if (PAY_BEFORE_SUBMIT) {
	      	  $tpl_pay_if['pay_b4_submit'] = 1;
      	  } else {
	      	  $tpl_pay_if['pay_b4_submit'] = 0;
      	  }
      	  $cnt_tpl = new TemplateClass(); 
	      $cnt_tpl->SetTemplate(DIR_TEMPLATE.'payment.tpl.php');    
	      $cnt_tpl->ReplaceIn($tpl_pay_array);
    	  $cnt_tpl->IfRegion($tpl_pay_if);
	      
	      $toTemplate['{content}'] = $cnt_tpl->Get();
	      $toTemplate['{home}'] = $g_params->Get('site','site_name');
	      $tmp_tpl->ReplaceIn($toTemplate);
	      $tpl_main = $tmp_tpl->Get();
      
      break;
            
    case 'notify':
      include('ipn.php'); 
      break;                
      
    default:
      $tpl_main = 'error';
      break;  
  }
  
?>
