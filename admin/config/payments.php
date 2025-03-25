<?php

  if ($g_user->Level() == AL_ADMIN) {
  
      $template = ADMIN_TEMPLATE_DIR.'config_payments.html';        
      $cForm = new FormClass();
      $site_tpl = array();
      $notifications = '';
      $form = '';
      $tmpErrors = array();            
      $form_fields = array(        
        // page administration
        'F-60|type[1]', 'F-60|type[2]','F-60|type[3]','F-60|type[4]','F-60|type[5]','F-60|type[6]',
        'F-60|type[7]', 'F-60|type[8]', 'F-60|type[9]',
        'C|ctype[1]', 'C|ctype[2]','C|ctype[3]','C|ctype[4]','C|ctype[5]','C|ctype[6]',
        'C|ctype[7]', 'C|ctype[8]', 'C|ctype[9]',
        'C|crecip[1]', 'C|crecip[2]', 'C|crecip[3]', 'C|crecip[4]', 'C|crecip[5]', 'C|crecip[6]',
        'C|crecip[7]','C|crecip[8]', 'C|crecip[9]',
        'C|videoup[1]', 'C|videoup[2]', 'C|videoup[3]', 'C|videoup[4]', 'C|videoup[5]', 'C|videoup[6]',
        'C|videoup[7]','C|videoup[8]', 'C|videoup[9]',
        'C|logoup[1]', 'C|logoup[2]', 'C|logoup[3]', 'C|logoup[4]', 'C|logoup[5]', 'C|logoup[6]',
        'C|logoup[7]','C|logoup[8]', 'C|logoup[9]',
      	'T-330|url', 'T-330|title',
        'CBO|payment_period','CBO|currency', 'T-330|paypal_account',
      );   
         
      // payments combo
      $payment_period = array(
        array('value'=>PP_1MONTHS, 'name'=>LANG_1MONTHS),
        array('value'=>PP_3MONTHS, 'name'=>LANG_3MONTHS),
        array('value'=>PP_6MONTHS, 'name'=>LANG_6MONTHS),
        array('value'=>PP_12MONTHS, 'name'=>LANG_12MONTHS),
        array('value'=>PP_UNLIMITED, 'name'=>LANG_UNLIMITED)
      );
      
      // assign combo to form
      $cForm->SetCombo('payment_period', $payment_period, 'value', 'name');
      $cForm->SetCombo('currency', $g_currencies, 'value', 'name');
            
	  // save settings
      if (isset($_POST['submit'])) {
          
          // payment period
          $params['payment_period'] = (int) $_POST['payment_period'];
          
          // currency
          $params['currency'] =  $_POST['currency'];
          
          // paypal account
          $params['paypal_account'] =  $_POST['paypal_account'];
           
          // URL
          $params['url'] =  $_POST['url'];
          
          // title
          $params['title'] =  $_POST['title'];     
          
          // default                
          for($i=1; $i<=LINK_TYPES; $i++) {
              $params['ctype['.$i.']'] = false;
              $params['crecip['.$i.']'] = false;
              $params['videoup['.$i.']'] = false;
              $params['logoup['.$i.']'] = false;
              $params['type['.$i.']'] = 0;
          }
                                        
          // prices
          $types = $_POST['type'];
          foreach ($types as $key=>$value) {
          
            $params['type['.$key.']'] = $value;
          }
          
          // enabled?
          if (isset($_POST['ctype'])) {
            $ctypes = $_POST['ctype'];
            foreach ($ctypes as $key=>$value) {
              $params['ctype['.$key.']'] = true;
            }
          }
  
          // reciprocal needed?
          if (isset($_POST['crecip'])) {          
            $crecip = $_POST['crecip'];
            foreach ($crecip as $key=>$value) {
              $params['crecip['.$key.']'] = true;
            }
          }

          // video allowed
          if (isset($_POST['videoup'])) {          
            $videoup = $_POST['videoup'];
            foreach ($videoup as $key=>$value) {
              $params['videoup['.$key.']'] = true;
            }
          }
                                        
          // logo upload
          if (isset($_POST['logoup'])) {          
            $logoup = $_POST['logoup'];
            foreach ($logoup as $key=>$value) {
              $params['logoup['.$key.']'] = true;
            }
          }
          
          // check for errors
          if (( count($tmpErrors) === 0) and $g_params->UpdateParams('payment',$params)!==false) {
             $notifications = '<div class="info">'.LANG_SETTING_SAVED.'</div>';
          } else {
              $text = implode(', ', $tmpErrors);
              $notifications = '<div class="info">'.LANG_SETTING_SAVED_ERROR.'. <br>Errors: '.$text.'</div>';
          }      
      }  
      
   	  // make form
      $form_array = $cForm->MakeForm($form_fields, $g_params->GetParams('payment'));
      $site_tpl = array_merge($site_tpl, $form_array);      
      $site_tpl['{notifications}'] = $notifications;
      
      $site_tpl['{lang payment settings}'] = LANG_PAYMENT_SETTINGS;
      $site_tpl['{lang payment_period}'] = LANG_PAYMENT_PERIOD;
      $site_tpl['{lang paypal_account}'] = LANG_PAYPAL_ACCOUNT;
      $site_tpl['{lang reciprocal_link}'] = LANG_RECIPROCAL_LINK;
      $site_tpl['{lang title}'] = LANG_TITLE;
      $site_tpl['{lang url}'] = LANG_URL;
      $site_tpl['{lang recip_needed}'] = LANG_RECIP_NEEDED;
      $site_tpl['{lang allow_video}'] = LANG_ALLOW_VIDEO;
  	  $site_tpl['{lang allow_logo}'] = LANG_ALLOW_LOGO;
  	  $site_tpl['{lang upload_logo}'] = LANG_UPLOAD_LOGO;
      $site_tpl['{lang my_site_title}'] = LANG_MY_SITE_TITLE;
      $site_tpl['{lang my_site_url}'] = LANG_MY_SITE_URL;

      $site_tpl['{lang submit}'] = LANG_SAVE;
      $site_tpl['{lang type[1]}'] = LINK_TYPE1;
      $site_tpl['{lang type[2]}'] = LINK_TYPE2;
      $site_tpl['{lang type[3]}'] = LINK_TYPE3;
      $site_tpl['{lang type[4]}'] = LINK_TYPE4;
      $site_tpl['{lang type[5]}'] = LINK_TYPE5;
      $site_tpl['{lang type[6]}'] = LINK_TYPE6;
      $site_tpl['{lang type[7]}'] = LINK_TYPE7;
      $site_tpl['{lang type[8]}'] = LINK_TYPE8;
      $site_tpl['{lang type[9]}'] = LINK_TYPE9;
                                 
   	  // make template
      $g_template->SetTemplate($template);  
      $g_template->ReplaceIn($site_tpl);
      $tpl_main['{content}'] = $g_template->Get(); 
  
  } else {
    $tpl_main['{content}'] = ERROR_002;
  }

?>
