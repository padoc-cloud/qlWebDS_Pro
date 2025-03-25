<?php

 function GetCategoryPath($category_object,$category_id) {
 	$category_path = '';
 	$category = $category_object->GetCategory($category_id);
 	$category_path .= $category['name'];
 	if ($category['id_up'] == 0) {
 		// top category reached
 		// do nothing
 	} else {
 		$categ_path_tmp = GetCategoryPath($category_object,$category['id_up']);
 		$category_path = $categ_path_tmp.'/'.$category_path;
 	}
 	return $category_path;
 }
 if (ALLOW_ADD_CATEG or ($g_user->Level()===AL_ADMIN)) {

  $eMsg = '<font color="#CC3300"><u>Please CORRECT the following Errors and Re-submit:</u></font>';
  $tpl_addc_array = array();
  $tpl_addc_array['{error}'] = '';
  $tpl_addc_array['{name value}'] = '';
  $tpl_addc_array['{description value}'] = '';
  $tpl_addc_array['{keywords value}'] = '';
  
  $isError = false;
  
  // add category
  if (isset($_POST['submit']) and  isset($_GET['addc']) ) {
    
    // in case of error add values to template
    $tpl_addc_array['{name value}'] = $_POST['name'];
    $tpl_addc_array['{description value}'] = $_POST['description'];
    $tpl_addc_array['{keywords value}'] = $_POST['keywords'];
    
    // get parent category id
    $id_up = (int) $_GET['addc'];
    
    // check category name
    $hmany = strlen($_POST['name']);
    
    if ($id_up<1) {
      $eMsg .= '<p>Error.</p>';
      $isError = true;
    }
    if ($hmany < 2 or $hmany>MAX_CATEG_LENGHT) {
      $eMsg .= '<p>'.LANG_TOO_LONG_CATEGORY.'</p>';
      $isError = true;
    } else {
    
    }
    
    // check captcha
    if (USE_CAPTCHA) {
       session_start();
       $sid = session_id();
       $captcha = new CaptchaClass();
       if ($captcha->CheckCaptcha($sid,$_POST['captcha'])) {
        
       } else {
          $eMsg .= '<p>'.LANG_INVALID_CODE.'</p>';
          $isError = true;
       }
       
    }
    
    // no errors, add new category
    if (!$isError) {
      
      // if no description and keywords insert name as description and keywords
      if (strlen($_POST['description']) <1) {
        $_POST['description'] = $_POST['name'];
      }
      if (strlen($_POST['keywords']) <1) {
        $_POST['keywords'] = $_POST['name'];
      }
      
      // check if that category already exists
      $_POST['name'] = trim($_POST['name']);
      $categ_row = $g_categ->GetCategoryByName($id_up, $_POST['name']);

      if ($categ_row) {
        $categ_id = $categ_row['id'];    
      
      } else {

        // save category
        $values = array('name'=>$_POST['name'],'description'=>$_POST['description'],'keywords'=>$_POST['keywords'],'id_up'=>$id_up);
        $categ_id = $g_categ->AddCategory($values);
      }
      
      if ($categ_id>0) {

      	// only send e-mail if category added by user not admin
      	if (!($g_user->Level()===AL_ADMIN)) {

 			$categ_path = GetCategoryPath($g_categ,$categ_id);
      		$categ_url = '<a href="'.SITE_ADDRESS.'index.php?categ='.$categ_id.'">'.SITE_NAME.'/'.$categ_path.'</a>';
      		$email_tpl['title'] = 'New Sub-Category Added in {my_site_url}';        
      		$email_tpl['email_body'] = 'New '.$categ_url.' New Sub-category was added to: {my_site_url} . '."\r\n";
	        $adminEmail = $values;
	        $adminEmail['email'] = ADMIN_EMAIL;
	        $adminEmail['id'] = 0;
	        $g_site->SendEmail($email_tpl, $adminEmail );
      	}
      	
        header("location: index.php?adds=$categ_id");
      }
      
    } else {
      $tpl_addc_array['{error}'] = '<div class="info">'.$eMsg.'</div>';
    }
    
  }

  // captcha refresh
  if (isset($_POST['captcha_refresh'])) {
    $tpl_addc_array['{name value}'] = $_POST['name'];
    $tpl_addc_array['{description value}'] = $_POST['description'];
    $tpl_addc_array['{keywords value}'] = $_POST['keywords'];
  }
  
  // template
  $tmp_tpl = new TemplateClass(); 
  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'add_categ.tpl.php');
  if (USE_CAPTCHA) {
    $tpl_addc_array['{captcha text}'] = LANG_CAPTCHA;
    $tpl_addc_array['{captcha text2}'] = LANG_CAPTCHA2;
    $tpl_addc_array['{captcha input}'] = '<input name="captcha" maxlength="15" size="15" type="text">';
    $tpl_addc_array['{captcha img}'] = '<img src="captcha.php" style="border: 1px solid red;" alt="Captcha">';
    $aIfRegion['iscaptcha'] = 1 ; 
    
  } else {
    $tpl_addc_array['{captcha text}'] = '';
    $tpl_addc_array['{captcha text2}'] = '';
    $tpl_addc_array['{captcha input}'] = '';
    $tpl_addc_array['{captcha img}'] = '';  
    $aIfRegion['iscaptcha'] = 0 ; 
  }
  
  $tpl_addc_array['{name}'] = LANG_CATEG_NAME;
  $tpl_addc_array['{lang required}'] = LANG_USER_REQUIRED;
  $tpl_addc_array['{description}'] = LANG_DESCRIPTION;
  $tpl_addc_array['{keywords}'] = LANG_KEYW;
  $tpl_addc_array['{add_category}'] = LANG_ADD_CATEGORY;
  $tpl_addc_array['{add}'] = LANG_ADD_BTN;
  $tpl_addc_array['{back}'] = LANG_BACK;
  $tpl_addc_array['{address}'] = $g_addr;
 
  if (($g_params->Get('for_user','allow_users') and $g_params->Get('for_user','user_logged_to_add') and
  	!($g_user->Level() == AL_USER)) or ($g_user->Level()===AL_ADMIN)) {
	  $tpl_adds_array['{error}'] = USER_NOT_LOGGED_ADD_ERROR;
	  $tpl_adds_array['{error}'] = str_replace('{user login link}','<a href="?page=login"><u>log in</u></a>',$tpl_adds_array['{error}']);
	  $tpl_adds_array['{error}'] = str_replace('{user register link}','<a href="?page=registration"><u>register</u></a>',$tpl_adds_array['{error}']);
	  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'user_error.tpl.php');      
	  $tmp_tpl->ReplaceIn($tpl_adds_array);
  } else {   
	  $tmp_tpl->ReplaceIn($tpl_addc_array);
  }
  $tmp_tpl->IfRegion($aIfRegion);
  
  $tpl_main = $tmp_tpl->Get();
 } else {
  exit;
 }

?>
