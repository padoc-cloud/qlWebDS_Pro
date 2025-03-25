<?php

  # Create a reply to validate the PayPal post. (Standard PayPal Code)
  
  //-----------------------------------------------
  // Read post from PayPal system and create reply
  // starting with: 'cmd=_notify-validate'...
  // then repeating all values sent - VALIDATION.
  //-----------------------------------------------
  $postvars = array();
  while (list ($key, $value) = each ($_POST)) {
    $postvars[] = $key;
  }
  
  $req = 'cmd=_notify-validate';
  
  foreach ($_POST as $key => $value) {
    
    $value = urlencode(stripslashes($value));
    $req .= "&$key=$value";
    
  }
  
  # Create an HTTP header for the reply message, open a connection...
  
  //------------------------------------------
  // Create message to post back to PayPal...
  // Open a socket to the PayPal server...
  //------------------------------------------

  $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
  $header .= "Host: www.paypal.com\r\n";
  $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
  $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
  
  //------------------------------------------------
  // Get Item Info, check if this item still exists
  //------------------------------------------------

  $id = (int) $_GET['id'];
  $row = $g_site->GetSiteById($id);  
  $pRow = $g_site->GetLastPayment($id);  
  
  $date = date(DATETIME_FORMAT);  
  
  # Check Connection...
  
  //---------------------------------------------------------------------------
  // Check HTTP if connection made to PayPal is OK, If not, print an error msg
  //---------------------------------------------------------------------------

  if (!$fp) {
    $res = "FAILED";
    $aPayment['error'] = 'fsockopen "www.paypal.com" connection failed';
    $aPayment['is_error'] = 1;
  } else {
    fputs ($fp, $header . $req);

    while (!feof($fp)) {
      $res = fgets ($fp, 1024);
     
      if (strcmp ($res, "VERIFIED") == 0) {

        # If payment is complete.
        
        //------------------------------------
        // If the payment_status=Completed... 
        //------------------------------------
        
        if ( ($id>0) and strcmp($_POST['payment_status'], "Completed") == 0) {
          
          $site = $g_site->GetSiteById($id);
          
          // send email, payment received
          $psite = $site;
          $psite['email'] =  $payer_email;
          $g_site->SendEmail($g_params->GetParams('payment_received'), $psite );
          
          $aSite['last_payment'] = $date;
          $aSite['last_payment_id'] = $pRow['id'];

          if (!PAY_BEFORE_SUBMIT) {
	          if (LINK_INSTANTLY_APPEAR or PAID_LINK_INSTANTLY_APPEAR) {
	            $aSite['status'] = SITE_VIEW;
	            
	            // send email, site approved   
	            if ($site['status']!=SITE_VIEW) {     
	              $g_site->SendEmail($g_params->GetParams('site_approved_email'), $site );
	            }
	          
	          }
          } 
          
          $ok = $g_site->UpdateSite($aSite, $id);
          $aPayment['paid'] = PM_PAID;
          $aPayment['error'] = LANG_INFO_PAYMENT0;
          $aPayment['is_error'] = 0;
        }
  
        //-------------------------------------------
        // If the payment_status is NOT Completed... 
        //-------------------------------------------

        else {
          $aPayment['error'] = LANG_INFO_PAYMENT1;
          $aPayment['is_error'] = 1;
          $aPayment['paid'] = PM_NOT_COMPLETED;
        }
        break;
  
      # Deal with 'Unverified' transactions

      //------------------------------------------------------------
      // If UNVerified - It's 'Suspicious' and needs investigating!
      // Send an email to yourself so you can investigate it.
      //------------------------------------------------------------

      } else {
        $aPayment['error'] = LANG_INFO_PAYMENT2;
        $aPayment['is_error'] = 1;
        $aPayment['paid'] = PM_UNVERIFIED;

      }
      
    }
  }
  
  $aPayment['payment_period'] = $g_params->Get('payment', 'payment_period');
  
  $aPayment['business'] = $_POST['business'];
  $aPayment['receiver_email'] = $_POST['receiver_email'];
  $aPayment['email'] = $_POST['payer_email'];
  $aPayment['payment_date'] = $date;
  $aPayment['currency'] = $_POST['mc_currency'];
  $aPayment['amount'] = $_POST['mc_gross']; 
  $aPayment['log'] = serialize($_POST);
  
  $ok = false;
  if ($pRow['id']>0) {
    $ok = $g_site->UpdatePayment($aPayment, $pRow['id']);
  }  

  ?>
