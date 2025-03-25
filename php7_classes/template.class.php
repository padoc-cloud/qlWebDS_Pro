<?php
  
  // This file performs template management operations for the application. It includes methods for loading, processing, and dynamically populating HTML templates with data.
  
  class TemplateClass {
      var $m_path = '';
      var $m_tplt = '';
      var $m_even_class = ' class="even" ';
      var $m_columns;
      var $m_row_tpl;
      
      function TemplateClass() {

      }

      function SetTemplate($name) {
         $fileName = $this->m_path . $name;

         if (file_exists($fileName)) {

            $fp = fopen($fileName,'r');
            $ile = filesize($fileName);
            if ($ile>0) {
               $this->m_tplt = fread($fp, $ile);
            } else {
               $this->m_tplt = '';
            }
            fclose($fp);
         } else {
           $this->m_tplt = 'File '.$fileName.' doesn`t exist';
         }
      }
            
      function FillRowsV2($region, $params, $add_brace=false) {
   
        $cur_col = 0;
        $tpl_row = $this->m_tplt;
        $tpl_table = '';
        $keys = array();
        $first_row = true;
        
        $sFind = '{'.$region.'}';
        $eFind = '{/'.$region.'}';
        
        $tpl_raw_row = $this->Between($sFind, $eFind, $this->m_tplt );
        $tpl_row = $tpl_raw_row;
      
        $nr = 0;
        
        if ($tpl_row===false) {
          trigger_error("Row template '<strong>$region</strong>' not found", E_USER_NOTICE);
          return false;
        }
        
        foreach ($params as $row) {
          $cur_col++;
          
          // fill column values
          foreach ($row as $key=>$value) {
            
            // addd brace if needed
            if ($add_brace) {  $key = '{'.$key.'}';  }
            
            // check if this is 'if region'
            if (strpos($key, 'if_rows')===0) {
              $this->m_row_tpl = $tpl_row;
              $this->RowsIfRegion(array($key=>$value));
              $tpl_row = $this->m_row_tpl;

            } else {
            
              // find params count             
              if ($first_row) {
                $first_row = false;
                $i = 1;
                
                while (true) {
                  $new_key = $key.'['.$i.']';
  
                  if (strpos($tpl_row, $new_key) === false) {
                    $this->m_columns = $i-1;
                    break;
                  }
                  $i++;
                }
                
                if ($this->m_columns===0) {
                  trigger_error('In rows you must specify index, starting from 1 e.g., '.$key.'[1]', E_USER_ERROR);
                  return false;            
                }                  
              }

              // end finding params count              
              if ($cur_col==1) { $keys[] = $key;  }
              
              $new_key = $key.'['.$cur_col.']';

              $tpl_row = str_replace($new_key, $value, $tpl_row);
            }
          }
          
          // if last column filled          
          if ($cur_col == $this->m_columns) {

            // tr even class
            if ($nr) {
              $tpl_row = str_replace('{even_class}', $this->m_even_class, $tpl_row);
            } else {
              $tpl_row = str_replace('{even_class}', '', $tpl_row);
            }
            $nr = 1 - $nr;

            $cur_col = 0;
            $tpl_table .=$tpl_row;
            $tpl_row = $tpl_raw_row;
            
          }
          
        }
        
        // fill unused columns       
        if ($cur_col>0) {
        
          for ($i=$cur_col; $i<=$this->m_columns; $i++) {
            $cur_col++;
            foreach ($row as $key=>$value) {

              $new_key = $key.'['.$cur_col.']';
              $tpl_row = str_replace($new_key, '&nbsp;', $tpl_row);
          
            } 
            
            // last column filled
            if ($cur_col == $this->m_columns) {
              $cur_col = 0;
              $tpl_table .=$tpl_row;
              $tpl_row = $tpl_raw_row;
              
              // tr even class
              if ($nr) {
                str_replace('{even_class}', $this->m_even_class, $tpl_row);
              } else {
                str_replace('{even_class}', '', $tpl_row);
              }
              $nr = 1 - $nr;  
                          
            }                       
            
          }
        }

        $rep = array('{'.$region.'}'.$tpl_raw_row.'{/'.$region.'}'=>$tpl_table);
        $this->ReplaceIn($rep);
        return true;
        
      }
            
      function ReplaceIn($params ) {
         $keys = array();
         $values = array();
         
         foreach ($params as $pattern => $content) {
          $keys[] = $pattern;
          $values[] = $content;
         }  
         $this->m_tplt = str_replace($keys, $values, $this->m_tplt);
      }

      function RowsIfRegion($aRegion) {

        if (is_array($aRegion) ) {
          foreach ($aRegion as $key=>$value) {
            
            $startIf = '{if '.$key;
            $endIf = '{end '.$key.'}';
            $region = $this->Between($startIf, $endIf, $this->m_row_tpl);
            
            if ($region) {
              
              $tpl_value = $this->Between('!=', '}', $region);
              
              $clean_region = $this->Between('}', false, $region);
              
              $aV = explode('{else}', $clean_region);
              if ( count($aV) == 1 ) {
                $aV[1] = '';
              }

              if ($tpl_value === false) {
                
                $tpl_value = $this->Between('==', '}', $region);
                if ($tpl_value===false) {
                  trigger_error("Comparison must be done by '!=' or '==' in if region: <b>'$key'</b>",  E_USER_WARNING);
                  return false;                    
                }
               
                if ( strcmp($value, trim($tpl_value))===0) {

                  $this->m_row_tpl = str_replace($startIf.$region.$endIf, $aV[0], $this->m_row_tpl);
                } else {
                  $this->m_row_tpl = str_replace($startIf.$region.$endIf, $aV[1], $this->m_row_tpl);
                }
                                
              } else {
              
                if ( strcmp($value, trim($tpl_value))!==0) {
                  $this->m_row_tpl = str_replace($startIf.$region.$endIf, $aV[0], $this->m_row_tpl);
                } else {
                  $this->m_row_tpl = str_replace($startIf.$region.$endIf, $aV[1], $this->m_row_tpl);
                }
                              
              }

              $this->RowsIfRegion( array($key=>$value) );
            }
            
          }
        } else {
          trigger_error("Parameter for IfRegion function isn`t array", 	 E_USER_WARNING);
          return false;        
        }
        
        return true;
      }
      
      function IfRegion($aRegion) {

        if (is_array($aRegion) ) {
          foreach ($aRegion as $key=>$value) {
            
            $startIf = '{if '.$key;
            $endIf = '{end '.$key.'}';
            $region = $this->Between($startIf, $endIf, $this->m_tplt);
            
            if ($region) {
              
              $tpl_value = $this->Between('!=', '}', $region);
              
              $clean_region = $this->Between('}', false, $region);
              
              $aV = explode('{else}', $clean_region);
              if ( count($aV) == 1 ) {
                $aV[1] = '';
              }

              if ($tpl_value === false) {
                
                $tpl_value = $this->Between('==', '}', $region);
                if ($tpl_value===false) {
                  trigger_error("Comparision must be done by '!=' or '==' in if region: <b>'$key'</b>",  E_USER_WARNING);
                  return false;                    
                }
               
                if ( strcmp($value, trim($tpl_value))===0) {

                  $this->m_tplt = str_replace($startIf.$region.$endIf, $aV[0], $this->m_tplt);
                } else {
                  $this->m_tplt = str_replace($startIf.$region.$endIf, $aV[1], $this->m_tplt);
                }
                                
              } else {
              
                if ( strcmp($value, trim($tpl_value))!==0) {
                  $this->m_tplt = str_replace($startIf.$region.$endIf, $aV[0], $this->m_tplt);
                } else {
                  $this->m_tplt = str_replace($startIf.$region.$endIf, $aV[1], $this->m_tplt);
                }
                              
              }
              
              $this->IfRegion( array($key=>$value) );
            }
            
          }
        } else {
          trigger_error("Parameter for IfRegion function isn`t array", 	 E_USER_WARNING);
          return false;        
        }
        
        return true;
      }
            
      function ReplaceArray($pattern, $params ) {
         $tmp = '';
         foreach ($params as  $content) {
            $tmp .= str_replace($pattern, $content, $this->m_tplt);
         }
         $this->m_tplt = $tmp;
      }
            
      function Show() {
        echo $this->m_tplt;
      }
      
      function Get(){
        return $this->m_tplt;
      }
      
      function Between($sFind, $eFind, $text) {
       $extract = false;   
       
       if ($sFind === false ) {
          $istart = 0;
       } else {
          $istart = strpos($text , $sFind ); // position of intro
       }
       
       if ($eFind === false) {
          $isend = strlen($text);
       } else {
          $isend = strpos($text ,   $eFind , $istart); // position of end intro
       }
      
       if ($istart !== false and $isend !== false)   {
       
          $istart+= strlen($sFind);   
          $iend = $isend-$istart; // difference integer
      
          $extract = substr($text, $istart, $iend); 
       } else {
          return false;
       }
      
       return $extract;
      
      }      
  }

?>
