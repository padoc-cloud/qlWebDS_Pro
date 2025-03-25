<?php

  if ($g_user->Level() == AL_ADMIN) {  
    
    $notifications = '';
    
    $template = ADMIN_TEMPLATE_DIR.'admin_templates.html';
    
    $dirTemplates = dirTree(MAIN_CATALOG.'templates/');
    
    if (isset($_GET['active'])) {
    
      $name = $_GET['active'];
      $keys = array_keys($dirTemplates);
      
      if (in_array($name, $keys)) {
        $params['dir_template'] = $name;
        $g_params->UpdateParams('template', $params);
        $notifications = '<div class="info">'.LANG_SETTING_SAVED.'</div>';        
      }
      
    }
    
    $tplInfo = array(
      'name'=>'Theme Name', 
      'url'=>'Theme URI', 
      'description'=>'Description', 
      'version'=>'Version',
      'author'=>'Author', 
      'author_url'=>'Author URI'
    );
    
    $tplInfoValues =array();
    
    foreach ($dirTemplates as $key=>$dir) {
      
      $tplInfoValues['dir'] = $key;
      
      // activate template
      $activate = '<a href="index.php?mod=admin&inc=templates&amp;active='.$key.'">Click to Activate</a>';
      if ($g_params->Get('template', 'dir_template') == $key) {
        $activate = 'ACTIVE TEMPLATE';  
      }

      // readme info
      $readme = $dir.'/readme.txt';
      if (file_exists($readme)) {
      
        $fp = fopen($readme, 'r');
        $info = fread($fp, filesize($readme) );
        
        foreach ($tplInfo as $value=>$name) {
            preg_match ("|$name:(.*)|i" , $info, $tmp);
            if (isset($tmp[1])) {
              $tplInfoValues[$value] = $tmp[1];
            }
        }
          
      } else { 
        $tplInfoValues['name'] = '-';
        $tplInfoValues['description'] = '-';
        $tplInfoValues['version'] = '-';
      }

      // if (isset($tplInfoValues['url'])) { $tplInfoValues['name'] = '<a href="'.$tplInfoValues['url'].'" target="_blank">'.$tplInfoValues['name'].'</a>'; }
      if (isset($tplInfoValues['author_url'])) { $tplInfoValues['author'] = '<a href="'.$tplInfoValues['author_url'].'" target="_blank">'.$tplInfoValues['author'].'</a>'; }
      
      // screenshot
      $preview = $dir.'/screenshot.png';
      if (file_exists($preview)) {
        $img = '<a href="'.$preview.'" target="_blank"><img src="'.$preview.'" border="0" width="190px;"></a>';
      } else { $img = ''; }
      
      // template params set
      $tSite[] = array (
        '{name}' => $tplInfoValues['name'],
        '{version}' => $tplInfoValues['version'],
        '{description}' => $tplInfoValues['description'],
        '{author}' => $tplInfoValues['author'],
        '{preview}' => $img,
        '{activate}' => $activate, 
      );
        
    }

    $site_tpl['{notifications}'] = $notifications;
        
    // set template
    $g_template->SetTemplate($template);
    $g_template->FillRowsV2('row templates', $tSite);
    $g_template->ReplaceIn($site_tpl);
    $tpl_main['{content}'] = $g_template->Get();
  
  }

  function dirTree($dir) {
     $d = dir($dir);
  
     while (false !== ($entry = $d->read())) {
         if($entry != '.' && $entry != '..' && is_dir($dir.$entry))
             $arDir[$entry] = $dir.$entry;
     }
     $d->close();
     return $arDir;
  } 
  
?>
