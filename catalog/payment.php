<?php

      if ($payment_period>0) {
        $tpl_adds_array['{subscription_period}'] = $payment_period;
        $tpl_adds_array['{subscription_period}'] = $payment_period;
        $if_region['subscription'] = 1;      
      }
      
      $tpl_adds_array['{co_code}'] = CURRENCY;
      $tpl_adds_array['{paypal_account}'] = PAYPAL_ACCOUNT; 
      
      $tpl_adds_array['{url}'] = $values['url'];
      $tpl_adds_array['{id}'] = $id;
      $tpl_adds_array['{link_type}'] = $values['link_type'];
            
      $tpl_adds_array['{payment_amount}'] = $payment['amount'];
      $tpl_adds_array['{email}'] = $payment['email'];

      $tpl_adds_array['{return_addr}'] = CATALOG_ADDRESS.'index.php?o=paypal&t=return&id='.$id.'&cat_id='.$category['id'];
      $tpl_adds_array['{cancel_return}'] = CATALOG_ADDRESS.'index.php?o=paypal&t=cancel&id='.$id.'&cat_id='.$category['id'];
      $tpl_adds_array['{notify_url}'] = CATALOG_ADDRESS.'index.php?o=paypal&t=notify&id='.$id.'&cat_id='.$category['id'];
      $tpl_adds_array['{directory}'] = CATALOG_ADDRESS;      
      
?>  
