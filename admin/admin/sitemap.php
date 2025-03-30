<?php 
  
    $site_tpl['{notifications}'] = ''; 
    $map_file = MAIN_CATALOG.'sitemap.xml.gz';
            
    if (isset($_POST['submit']) ) {
    
        // sites sitemap
        $map = '<?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.google.com/schemas/sitemap/0.84">' . "\n";
            
        $last = substr(SITE_ADDRESS, -1,1);
        if ($last == '/') {
          $site_addr = SITE_ADDRESS;
        } else {  $site_addr = SITE_ADDRESS.'/'; }
        
        $query = "SELECT id, mod_rewrite FROM  $g_site->m_table WHERE status=".SITE_VIEW." ORDER BY id DESC ";
        $res = $DB->query($query);
        $iw=0;
        if ($res) {
            
            while($row = mysqli_fetch_array($res)) {

              if (MOD_REWRITE) {
                $more_address = str_replace(',',WORD_SEPARATOR,$row['mod_rewrite']).WORD_SEPARATOR.MOD_SITE.'-'.$row['id'].PAGE_EXTENSION;
              } else {
                $more_address = 'index.php?'.$row['mod_rewrite'].'&amp;site='.$row['id'];
              }
                
              $map .= '<url><loc>'.$site_addr.$more_address.'</loc></url>'. "\n";
              $iw = $iw+1;

            }
        }

        $query = "SELECT id, mod_rewrite FROM  $g_categ->m_table ORDER BY id DESC ";
        $res = $DB->query($query);
        $iw=0;
        if ($res) {
            
            while($row = mysqli_fetch_array($res)) {

              if (MOD_REWRITE) {
                $categ_address = str_replace(',',WORD_SEPARATOR,$row['mod_rewrite']).WORD_SEPARATOR.MOD_CATEG.'-'.$row['id'].PAGE_EXTENSION;
              } else {
                $categ_address = 'index.php?'.$row['mod_rewrite'].'&amp;categ='.$row['id'];
              }
                
              $map .= '<url><loc>'.$site_addr.$categ_address.'</loc></url>'. "\n";
              $iw = $iw+1;

            }
        }

        $map .= '</urlset>';
        
        if (file_exists($map_file)) {
            unlink($map_file);
        }
        
        $fp=gzopen($map_file,'w');
        if ($fp) {
          fwrite($fp,$map);
          fclose($fp);
          
          $notifications = '<div class="info">'.LANG_SITEMAP_SAVED.'</div>'; 
        } else {
          $notifications = '<div class="info">'.LANG_SITEMAP_NOT_SAVED.'</div>';
        
        } 
                     
    }
     
    $template = ADMIN_TEMPLATE_DIR.'admin_main.html';
    
    // check sitemap date
    if (file_exists($map_file)) {
      $sitemap_date =  date(DATETIME_FORMAT, filemtime($map_file));      
    }
    $site_tpl['{sitemap_date}'] = $sitemap_date;    
    $site_tpl['{sitemap_address}'] = CATALOG_ADDRESS.'sitemap.xml.gz';
          
    $site_tpl['{lang generate_sitemap}'] = LANG_GENERATE_SITEMAP;
    $site_tpl['{lang sitemap_address}'] = LANG_SITEMAP_ADDRESS;
    $site_tpl['{lang sitemap_date}'] = LANG_SITEMAP_GENERATION_TIME;
     
    $g_template->SetTemplate($template);  
    $g_template->ReplaceIn($site_tpl);
    $tpl_main['{content}'] = $g_template->Get();    
    
?>
