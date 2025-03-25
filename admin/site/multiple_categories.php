<?php
       
    $cCateg = new CategoriesClass();
    $aCateg = array();

    if (isset( $_GET['id_up']) and $_GET['id_up']>0) {
      $id_up = (int) $_GET['id_up'];
      $cur_cat_row = $cCateg->GetCategory($id_up);
      $is_up = true;
    } else {
      $id_up = 0;
      $cur_cat_row = array();
      $is_up = false;
    } 
            
    // top of category
    $parent_categ_link = '<a href="index.php?mod=site&amp;inc=categories" >Top</a> ';
    $currrent = $cur_cat_row;

    $parent_categ = '';
    $categ_link = '';
    
    while ($is_up) {
      if ($currrent['id_up']>0) {
        $parent_categ = $g_categ->GetCategory($currrent['id_up']);
      } else {
        $is_up = false;
      } 
      
      $categ_link = ' / <a href="index.php?mod=site&amp;inc=categories&amp;id_up='.$currrent['id'].'" >'.$currrent['name'].'</a>' . $categ_link;
 
      $currrent = $parent_categ; 
    }
    
    $tmp_tpl['{top}'] = $parent_categ_link.$categ_link;
    $tmp_tpl['{category}'] = $cur_cat_row['name'];
    $tmp_tpl['{id_up}'] = $cur_cat_row['id'];
  
     // get categories and show       
     $table = $cCateg->GetCategories($id_up);
     $i=0;
     foreach ($table as $row) {
       $aCateg[$i]['{name}'] = $row['name'];
       $aCateg[$i]['{id}'] = $row['id'];
       $i++;
     }

     $g_template->SetTemplate(ADMIN_TEMPLATE_DIR.'site_multiple_categories.html');
     $g_template->ReplaceIn($tmp_tpl);
     $tpl_main['{content}'] = $g_template->Get();
     
?>
