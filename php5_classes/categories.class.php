<?php

  class CategoriesClass {
  
    var $m_DB;
    var $m_table;    
    var $m_tablePrefix;
    var $m_connection;
    
      var $eText = '';
      var $eNo = 0;
      var $eFunc = '';
            
    var $m_mainCategs = array();
    
    function CategoriesClass() {

        if (IS_PHP5) {
          $this->m_DB = DataBase::getInstance();  
        } else {
          $this->m_DB =& DataBase::getInstance();
        }   

        if (!$this->m_DB->Open()) {
          $this->eText = 'DB connection failed';
          $this->eNo = 300001;
          $this->eFunc = '__construct';
        } else {
          $this->m_tablePrefix = $this->m_DB->tablePrefix;
          $this->m_table = $this->m_tablePrefix.'categories';
          $this->m_sites = $this->m_tablePrefix.'sites';
          $this->m_connection = $this->m_tablePrefix.'connection';
        }
 
    }
    
    function GetCategories($id_up, $limit=0) {
      if (isset($this->m_categs[$id_up])) {
        return $this->m_categs[$id_up];
      }
      
      $id_up = (int) $id_up;
      
      if ($limit==0) {
        $query = "SELECT * FROM $this->m_table WHERE id_up=$id_up  ORDER BY name ASC";
      } else {
        $query = "SELECT * FROM $this->m_table WHERE id_up=$id_up ORDER BY name ASC LIMIT $limit";
      } 
      $ret = $this->m_DB->GetTable($query);
      
      if ($ret===false) {
        $this->eText = 'Couldn`t get categories';
        $this->eNo = 1;
        $this->eFunc = 'GetCategories';      
      } else {
        if ($limit === 0) {
          $this->m_categs[$id_up] = $ret; // cache categories
        }  
      }
      return $ret;
    }
    
    function GetSiteCategories($site_id) {
      
      $site_id = (int) $site_id;

      $query = "SELECT * FROM $this->m_connection AS cn LEFT JOIN $this->m_table ct ON ct.id=cn.sub_id WHERE cn.site_id=$site_id ORDER BY name ASC";

      $ret = $this->m_DB->GetTable($query);
      
      if ($ret===false) {
        $this->eText = 'Couldn`t get categories';
        $this->eNo = 1;
        $this->eFunc = 'GetSiteCategories';      
      } 
      
      return $ret;
    }    
        
    function GetCategory($id) {
      
      $id = (int) $id;
      $query = "SELECT * FROM $this->m_table WHERE id=$id";
      $ret = $this->m_DB->GetRow($query);
      
      if ($ret===false) {
        $this->eText = 'There in no such category';
        $this->eNo = 1;
        $this->eFunc = 'GetCategory';      
      } 
      return $ret;
    }
    
    function GetAllCategories() {
          
          $newTable = array();
          
          $query = "SELECT * FROM $this->m_table ORDER BY `name`";
          
          $table = $this->m_DB->GetTable($query);
          
          foreach ($table as $row) {
            if ($row['id_up'] == 0) {
              $parents[] = $row;
              
            } else {
              $children[$row['id_up']][] = $row; 
            }
          }
          
          foreach ($parents as $parent) {
            $newTable[] = $parent;
            
            if (isset($children[$parent['id']])) {
            
              foreach ($children[$parent['id']] as $child) {
                $newTable[] = $child;
                
                if (isset($children[$child['id']])) {
                  foreach ($children[$child['id']] as $child2) {
                    $newTable[] = $child2;
                  }
                }    
                
              }
                
            }
          
          }

          return $newTable;        
    } 
        
    function GetCategoryByName($id_up, $name) {
     
      $query = "SELECT * FROM $this->m_table WHERE name='$name' and id_up=$id_up";
      $ret = $this->m_DB->GetRow($query);
      
      if ($ret===false) {
        $this->eText = 'There in no such category';
        $this->eNo = 1;
        $this->eFunc = 'GetCategory';      
      } 
      return $ret;
    }

    function GetCategoryByMod_Rewrite($id_up, $name) {
     
      $query = "SELECT * FROM $this->m_table WHERE mod_rewrite='$name' and id_up=$id_up";
      $ret = $this->m_DB->GetRow($query);
      
      if ($ret===false) {
        $this->eText = 'There in no such category';
        $this->eNo = 1;
        $this->eFunc = 'GetCategory';      
      } 
      return $ret;
    }
    
    function GetCountsSites($cat_id) {
      $query = "SELECT COUNT( cn.sub_id ) as hmany, cn.sub_id FROM `$this->m_table` AS ct LEFT JOIN `$this->m_connection` AS cn ON cn.sub_id = ct.id WHERE ct.id_up =$cat_id GROUP BY cn.sub_id";

      $query = "
        SELECT COUNT( cn.sub_id ) as hmany, cn.sub_id 
        FROM `$this->m_table` AS ct 
        LEFT JOIN `$this->m_connection` AS cn ON cn.sub_id = ct.id
        LEFT JOIN `$this->m_sites` AS st ON st.id=cn.site_id
        WHERE ct.id_up =$cat_id AND st.status=".SITE_VIEW."
        GROUP BY cn.sub_id";         

      $ret = $this->m_DB->GetTable($query);
      $table = array();              
      foreach ($ret as $row) {
        $table[$row['sub_id']] = $row['hmany'];
      }
      return $table;
    }

    function GetCountsSites_2($cat_id,$site_status) {
      $query = "
        SELECT COUNT( cn.site_id ) as hmany 
        FROM `$this->m_connection` AS cn 
        LEFT JOIN `$this->m_sites` AS st ON st.id=cn.site_id
        WHERE cn.sub_id=$cat_id AND st.status=".$site_status;

      $ret = $this->m_DB->GetRow($query);
      
      return $ret['hmany'];
    }
    
    function GetSitesIDs($cat_id) {
      $query = "
        SELECT cn.site_id AS conn_id, st.id AS sites_id
        FROM `$this->m_connection` AS cn 
        LEFT JOIN `$this->m_sites` AS st ON st.id=cn.site_id
        WHERE cn.sub_id=".$cat_id;

      $ret = $this->m_DB->GetTable($query);
      
      return $ret;
    }
    
    function GetCountsCategs($cat_id) {
      $query = "SELECT ct.id as sub_id, COUNT(ct2.id) as hmany FROM `$this->m_table` AS ct LEFT JOIN `$this->m_table` AS ct2 ON ct.id = ct2.id_up WHERE ct.id_up =$cat_id GROUP BY ct.id";         
      $ret = $this->m_DB->GetTable($query);
      $table = array();              
      foreach ($ret as $row) {
        $table[$row['sub_id']] = $row['hmany'];
      }
      return $table;    
    }
        
    function AddCategory($values) {
      
      // to do: Check category name
      $values['name'] = trim($values['name']);
      if (strlen($values['name'])<1) {
        return false;
      }

      $tmp_name = mysql_real_escape_string($values['name']);
      $id_up = (int) $values['id_up'];
      $query = "SELECT * FROM $this->m_table WHERE name='$tmp_name' AND id_up=$id_up";
      $row = $this->m_DB->GetRow($query);
      
      if (is_array($row) and count($row)>0) {
        return $row['id'];
      } 
      
      // mod rewrite
      $values['mod_rewrite'] = $this->FilterMod($values['name']);      
      
      if ($id_up==0) {
        $level = 1;
      } else {
        $level = $this->GetLevel($id_up)+1;
      }
      
      $values['level'] = $level;
      $ret = $this->m_DB->InsertQuery($this->m_table, $values );
      
      if ($ret === false) {
        $this->eText = 'Couldn`t add category';
        $this->eNo = 2;
        $this->eFunc = 'AddCateg';        
      } else {
        if ($id_up>0) {
          $this->DeleteCategoryCache($id_up);
        }
      }
       
      return $ret;  

    }

    function UpdateCategory($values, $id) {
      
      if (isset($values['name']) ) {

        // mod rewrite
        $values['mod_rewrite'] = $this->FilterMod($values['name']);  
      }
      $ret = $this->m_DB->UpdateQuery($this->m_table, $values, $id);
      
      if ($ret ===false) {
        $this->eText = 'Couldn`t update category';
        $this->eNo = 3;
        $this->eFunc = 'UpdateCategory';        
      } else {
        $this->DeleteCategoryCache($id);
      }
      return $ret;      
    }
    
    function DeleteCategory($id) {
      $ret = false;
      
  	  $tCountsSitesActive = $this->GetCountsSites_2($id,SITE_VIEW);
  	  $tCountsSitesPending = $this->GetCountsSites_2($id,SITE_WAITING);
  	  $tCountsCategs = count($this->GetCountsCategs($id));
	  $totalCount = $tCountsSitesActive + $tCountsSitesPending + $tCountsCategs;  
  	  if ($totalCount > 0) {
  	  	
        $ret = 'There are ';
        if ($tCountsSitesActive == 1) {
          $ret .= $tCountsSitesActive.' active listing, ';
        }        
        if ($tCountsSitesActive <> 1) {
          $ret .= $tCountsSitesActive.' active listings, ';
        }        
        if ($tCountsSitesPending == 1) {
          $ret .= $tCountsSitesPending.' pending listing, ';
        }        
        if ($tCountsSitesPending <> 1) {
          $ret .= $tCountsSitesPending.' pending listings, ';
        }        
        if ($tCountsCategs == 1) {
          $ret .= $tCountsCategs.' subcategory ';
        }        
        if ($tCountsCategs <> 1) {
          $ret .= $tCountsCategs.' subcategories ';
        }
        
        $it_them = 'them'; 
        if ($totalCount == 1) {
        	$it_them = 'it';
        }
        $cur_cat = $this->GetCategory($id);
                
        $ret .= 'in "'.$cur_cat['name'].'" category you are trying to delete. Please delete or move '.$it_them.' before deleting the category.';
      }
      
      if (!$ret) {
        $query = "DELETE FROM $this->m_table WHERE id=$id";
        $res = $this->m_DB->query($query);
        
        $this->DeleteCategoryCache($id);
        
        if ($res) {
          $ret = $this->m_DB->Affected($res);
          $ret = 'OK';
        }
      }
      return $ret;
    }

    function TestCache() {
          global  $g_cache;
          $g_cache->EmptyCache('../');
    
    } 
       
    function DeleteCategories($ids) {

      $ret = false;
      $values_id = implode(", ", $ids);
      
      // check if category has subcategories
      $query = "SELECT DISTINCT id_up FROM $this->m_table WHERE id_up IN ($values_id)";
      $tSub = $this->m_DB->GetTable($query);
      
      foreach ($tSub as $row) {
      
        $fid = $row['id_up'];
        $key = array_search($fid, $ids);
        
        if ($key!==false) {
          unset($ids[$key]);
        }
      }
      
      if (count($ids)==0) return 0;
      
      $values_id = implode(", ", $ids);
      
      $query = "DELETE FROM $this->m_table WHERE id IN ($values_id)";
      
      $res = $this->m_DB->query($query);
      
      if ($res) {
        $ret = $this->m_DB->Affected($res);
        foreach ($ids as $id) {
          $this->DeleteCategoryCache($id);
        }
      }
      return $ret;
    }
    
    function GetLevel($id) {
      $query = "SELECT level FROM $this->m_table WHERE id=$id";
      $row = $this->m_DB->GetRow($query);
      if (isset($row['level']) ) {
        return $row['level'];
      } else {
        return false;
      }
    }
  
    function DeleteCategoryCache($categ_id) {
      global $g_site, $g_cache;
      
      $category = $this->GetCategory($categ_id);
      
      $addr = 'index';
      $g_cache->Delete($addr);
      
      $addr = 'categ='.$categ_id;
      $g_cache->Delete($addr);
            
      $hmany = $g_site->CountSitesInCateg($categ_id);
      $hmany =  ceil($hmany / SITES_PER_PAGE);
      
      for ($i=2; $i<$hmany; $i++) {
        $addr = 'categ='.$categ_id.'&amp;pg='.$i;
        $g_cache->Delete($addr);
      }
      
      if ($category['id_up']>0) {
        $this->DeleteCategoryCache($category['id_up']);
      }
    }
    
    function Count() {
    
      $query = "SELECT COUNT(id) as hmany FROM $this->m_table";
      
      $row = $this->m_DB->GetRow($query);
      if ($row) {
        return $row['hmany'];
      }
      
      return 0;      
    
    }
    
    function Mod_Rewrite_url($id) {
    	$url = '';
    	$row = $this->GetCategory($id);
    	if ($row) {
    		$url .= str_replace(',','-',$row['mod_rewrite']).'/';
    		if ($row['level'] <> 1) {
    			$url = $this->Mod_Rewrite_url($row['id_up']).$url;
    		}
    	}
    	return $url;
    }
    
    function FilterMod($name) {

      $hmany = strlen($name);  
      $new_name = '';

      if (DEFAULT_CHARSET === 'utf-8') {

        $ascii_table['ą'] = 'a';
        $ascii_table['Ą'] = 'a';
        $ascii_table['ś'] = 's';
        $ascii_table['Ś'] = 's';      
        $ascii_table['ó'] = 'o';
        $ascii_table['Ó'] = 'o';      
        $ascii_table['ł'] = 'l';
        $ascii_table['Ł'] = 'l';
        $ascii_table['ń'] = 'n';
        $ascii_table['Ń'] = 'n';
        
        $ascii_table['ż'] = 'z';
        $ascii_table['Ż'] = 'z';
        $ascii_table['ź'] = 'z';
        $ascii_table['Ź'] = 'z';
                        
        $ascii_table['ć'] = 'c';
        $ascii_table['Ć'] = 'c';
                                                              
        $ascii_table['ę'] = 'e';
        $ascii_table['Ę'] = 'e';
  
        $ascii_table['Ö'] = 'o';
        $ascii_table['õ'] = 'o';   
        $ascii_table['Ü'] = 'u';
        $ascii_table['ü'] = 'u';  
        $ascii_table['ä'] = 'a';
        $ascii_table['Ä'] = 'a';  
        $ascii_table['ß'] = 'ss';
        
        $keys = array_keys($ascii_table);
        $name = str_replace($keys, $ascii_table, $name);
 
      } else if (DEFAULT_CHARSET === 'iso-8859-2') {  
      
        $ascii_table[177] = 'a';
        $ascii_table[161] = 'a';
        $ascii_table[182] = 's';
        $ascii_table[166] = 's';      
        $ascii_table[243] = 'o';
        $ascii_table[211] = 'o';      
        $ascii_table[179] = 'l';
        $ascii_table[163] = 'l';
        $ascii_table[241] = 'n';
        $ascii_table[209] = 'n';
        
        $ascii_table[188] = 'z';
        $ascii_table[172] = 'z';
        $ascii_table[191] = 'z';
        $ascii_table[172] = 'z';
                        
        $ascii_table[230] = 'c';
        $ascii_table[198] = 'c';
                                                              
        $ascii_table[234] = 'e';
        $ascii_table[202] = 'e';
  
        $ascii_table[246] = 'o';   
        $ascii_table[252] = 'u';  
        $ascii_table[196] = 'a';  
        $ascii_table[214] = 'o';
        $ascii_table[220] = 'u';
        $ascii_table[223] = 'ss';
                              
        for ($i=0; $i<$hmany; $i++) {
          
          $ascii = ord( substr($name,$i,1) );
          
          if (isset($ascii_table[$ascii])) {
            $new_name .= $ascii_table[$ascii];
          } else {
            $new_name .= chr($ascii);
          }
        }
        $name = $new_name;   
        
      } else {
      
      }     
      
      $name = str_replace(". ",",",$name);
      $name = str_replace(".",",",$name);
      $name = str_replace(" / ",",",$name);
      $name = str_replace("/",",",$name);
      $name = str_replace("'",",",$name);
      $name = str_replace(" - "," ",$name);
      $name = str_replace("_",",",$name);
      $name = str_replace( "&", ",", $name);
      $name = str_replace( " ", ",", $name);
      $name = str_replace( "%", "", $name);
      $name = ereg_replace("[^A-Za-z0-9,-]", ",", $name);
      $name = str_replace("amp","",$name);
      $name = str_replace("-",",",$name);
      $name = str_replace(",,,,",",",$name);  
      $name = str_replace(",,,",",",$name);
      $name = str_replace(",,",",",$name);
            
      // return strtolower($name);
      return $name;
    }

    function SearchCategories($search) {
      $search = strip_tags($search);
      $search = str_replace(",", " ", $search);
      $search = str_replace(";", " ", $search);        
 		  $search = preg_replace( "/\s{2,}/", " ", $search );
      $s_table = explode(" ", $search) ;    
    
      $ile = count($s_table);

      // build search query to search
      $select = "SELECT ct.name, ct.id_up FROM $this->m_table ct  ";
      $middle = ' WHERE ';  
      for ($i=0; $i<$ile; $i++) {
         $middle .= " (ct.`name` LIKE '%$s_table[$i]%') AND ";               
      }
      $middle .= " ct.`level` > 0";
      $query = $select.$middle." ORDER BY ct.name";

      $ret = $this->m_DB->GetTable($query);
      
      return $ret;
    }
    
  }

?>
