<?php

// This file performs grid management operations for the application. It includes methods for creating, configuring, and displaying dynamic HTML tables with features like pagination, checkboxes, links, and delete actions.

/*
  v 1.2
  Added:
   - CheckBox column   
  to cerate table do it this way:
  - MakeQuery or SetQuery
  - Bind
  or
  - SetTable  
  - SetClasses
  - AddCheckbox
  - AddLink (array $columnID,  $linkTitle,  $addr) // $addr = index.php5?id1={id}&id2={id}
  - AddDelete
  - AddFCColumn ($columnID, $columnValue, $columnType, $idPrfx, $colWidth)
  - AddHeader (array $titles)
  - AddFooter
  - AddPagging // link: index.php5?costam=costam&amp;page={nr}  <- most important {nr}
  - GetContent(array $columns)
*/

class GridClass
{
    public $m_tableName;
    public $m_table;
    public $m_DB;
    public $m_query;
    public $m_tablePrefix;

    public $eText = '';
    public $eNo   = 0;
    public $eFunc = '';

    // classes
    public $m_class;
    public $m_tdClass;
    public $m_trClass;
    public $m_thClass;
    public $m_thPaggingClass;

    public $m_header       = '';
    public $m_pureQuery    = '';
    public $m_page         = 0;
    public $m_perPage      = 0;
    public $m_footer       = '';
    public $m_columnsCount = 0;

    // check box
    public $m_chBoxColumnID   = 'id';
    public $m_chBoxIDcolumn   = 'id';
    public $m_chBoxColumnName = '';
    public $m_isChbox         = false;
    public $m_selectedChbox   = [];

    // delete link
    public $m_delColumnID   = 'id';
    public $m_delIDcolumn   = 'id';
    public $m_delColumnName = '';
    public $m_isDel         = false;
    public $m_delAddr;

    // links
    public $m_links        = [];
    public $m_linksHeaders = [];
    public $m_isLink       = false;

    // form column
    public $m_isFColumn = false;
    public $m_FCIds     = []; // id column name
    public $m_FCValues  = []; // value column name
    public $m_FCColumns = [];
    public $m_FCHeaders = [];

    // footer
    public function GridClass()
    {

        if (IS_PHP5) {
            $this->m_DB = DataBase::getInstance();
        } else {
            $this->m_DB = &DataBase::getInstance();
        }

        if (! $this->m_DB->Open()) {
            $this->eText = 'DB connection failed';
            $this->eNo   = 300001;
            $this->eFunc = '__construct';
        } else {
            $this->m_tablePrefix = $this->m_DB->tablePrefix;
        }
        $this->DefaultClasses();
    }

    public function Connect()
    {
        $this->m_DB = DataBase::getInstance();
        if (! $this->m_DB->Open()) {
            $this->eText = 'DB connection failed';
            $this->eNo   = 300001;
            $this->eFunc = '__construct';
        } else {
            $this->m_tablePrefix = $this->m_DB->tablePrefix;
        }
        $this->DefaultClasses();
    }
    public function DefaultClasses()
    {
        $this->m_class          = 'grid_class';
        $this->m_thClass        = '';
        $this->m_trClass        = ['tr_grid_a', 'tr_grid_b'];
        $this->m_tdClass        = '';
        $this->m_thPaggingClass = 'th_grid_pagging';
    }
    public function NewTable()
    {
        $this->m_isChbox   = false;
        $this->m_isLink    = false;
        $this->m_isDel     = false;
        $this->m_isFColumn = false;
        $this->DefaultClasses();
    }
    public function SetClasses($tableC, $thC, $trC, $tdC)
    {
        $this->m_class   = $tableC;
        $this->m_thClass = $thC;
        $this->m_trClass = $trC;
        $this->m_tdClass = $tdC;
    }
    public function Config()
    {

    }

    public function SetTable($table)
    {
        $this->m_table = $table;
    }

    public function Bind()
    {
        $this->m_table = false;
        if (strlen($this->m_query) > 0) {
            $this->m_table = $this->m_DB->GetTable($this->m_query);
            if ($this->m_table != false) {

                return true;
            }
        }
        return false;
    }

    public function MakeQuery($view, $tables, $condition, $order, $page, $perpage)
    {
        $select = ''; // 'SELECT '.$view.' FROM ';
        foreach ($tables as $table) {
            $select .= $this->m_tablePrefix . $table . ', ';
        }

        $len    = strlen($select) - 2;
        $select = substr($select, 0, $len);
        if (strlen($condition) > 0) {
            $select .= ' WHERE ';
            $select .= $condition;
        }

        $this->m_pureQuery = 'SELECT COUNT(*) as cnt FROM ' . $select;
        $select            = 'SELECT ' . $view . ' FROM ' . $select;

        if (count($order) > 0) {
            $orderby = '';
            foreach ($order as $column => $param) {
                $orderby .= ' ' . $column . ' ' . $param . ',';
            }
            $len     = strlen($orderby) - 1;
            $orderby = substr($orderby, 0, $len);
            $select .= ' ORDER BY' . $orderby;
        }

        if ($page > 0 and $perpage > 0) {
            $start = ($page - 1) * $perpage;
            $select .= ' LIMIT ' . $start . ', ' . $perpage;
            $this->m_page    = $page;
            $this->m_perPage = $perpage;
        }

        $this->m_query = $select;
    }

    public function SetQuery($query)
    {
        $this->m_query = $query;
    }

    public function Limits($start, $end)
    {

    }

    public function AddHeader($titles)
    {
        $thNr   = 0;
        $header = '';
        // $this->m_columnsCount = 0;
        if ($this->m_isChbox) {
            $header .= '<th>' . $this->m_chBoxColumnName . '</th>';
        }
        foreach ($titles as $title) {

            // commented th class selection
            /*
             if (isset($this->m_thClass[$thNr])) {
                $thC = 'class="'.$this->m_thClass[$thNr].'"';
             } else {$thC = '';}
             $header .= '<th '.$thC.'>'.$title.'</th>'."\r\n";
			 */

            $header .= '<th>' . $title . '</th>';
            $thNr = 1 - $thNr;
            $this->m_columnsCount++;
        }

        if ($this->m_isDel) {
            $header .= '<th>' . $this->m_delColumnName . '</th>';
        }
        $this->m_header = $header . "\r\n";
    }

    public function AddCheckbox($columnID, $columnName, $selected)
    {
        $this->m_chBoxIDcolumn   = $columnID;
        $this->m_chBoxColumnName = $columnName;
        // $this->m_columnsCount++;
        $this->m_selectedChbox = $selected;
        $this->m_isChbox       = true;
    }

    public function AddDelete($columnID, $columnName, $addr)
    {
        $this->m_delIDcolumn   = $columnID;
        $this->m_delColumnName = $columnName;
        // $this->m_columnsCount++;
        $addrAr             = explode('{id}', $addr);
        $this->m_delAddr[0] = $addrAr[0];
        $this->m_delAddr[1] = $addrAr[1];
        $this->m_isDel      = true;
    }

    public function AddLink($columnID, $linkTitle, $addr)
    {

        // $this->m_columnsCount++;
        $addrAr          = explode('{id}', $addr);
        $this->m_isLink  = true;
        $this->m_links[] = ['ids' => $columnID, 'title' => $linkTitle, 'addr' => $addrAr];
        // $this->m_links[] = array(  'ids'=>'m', 'title'=>'m', 'addr'=>'m');
        // array_push($this->m_linksHeaders ,$columnName);
    }

    public function AddFCColumn($columnID, $columnValue, $columnType, $idPrfx, $colWidth)
    {
        $this->m_isFColumn = true;
        $this->m_columnsCount++;
        $this->m_FCIds[]     = $columnID;
        $this->m_FCValues[]  = $columnValue;
        $this->m_FCColumns[] = ['id' => $columnID, 'value' => $columnValue, 'type' => $columnType, 'idpfx' => $idPrfx, 'width' => $colWidth];
        // array_push($this->m_FCHeaders ,$columnName);
    }

    public function AddFormColumn($columnID, $columnValue, $columnType, $idPrfx, $colWidth)
    {
        $this->m_isFColumn = true;
        $this->m_columnsCount++;
        $this->m_FCIds[]     = $columnID;
        $this->m_FCValues[]  = $columnValue;
        $this->m_FCColumns[] = ['id' => $columnID, 'value' => $columnValue, 'type' => $columnType, 'idpfx' => $idPrfx, 'width' => $colWidth];
        // array_push($this->m_FCHeaders ,$columnName);
    }

    public function AddPagging($addr)
    { // example: index.php5?id=1&amp;page={nr}
        $tpl = '';
        if (strlen($this->m_pureQuery) and $this->m_page > 0) {
            $res = $this->m_DB->query($this->m_pureQuery);
            if ($res) {
                $row    = $this->m_DB->GetRow($res);
                $count  = $row['cnt'];
                $ileStr = ceil($count / $this->m_perPage);

                $addrAr = explode('{nr}', $addr);
                $a1     = $addrAr[0];
                $a2     = $addrAr[1];
                for ($i = 1; $i <= $ileStr; $i++) {
                    if ($this->m_page != $i) {
                        $tpl .= ' <a href="' . $a1 . $i . $a2 . '" >' . $i . '</a> ';
                    } else {
                        $tpl .= $i;
                    }
                }
                $this->m_footer .= '<tr><th class="' . $this->m_thPaggingClass . '" colspan="' . $this->m_columnsCount . '">[' . $tpl . ']</th></tr>';
            }
        }
    }

    public function AddFooter($foot)
    {
        $this->m_footer .= '<tr>';
        for ($i = 1; $i <= $this->m_columnsCount; $i++) {

            if (isset($foot['foot_' . $i])) {
                $this->m_footer .= '<th>' . $foot['foot_' . $i] . '</th>';
            } else {
                $this->m_footer .= '<th>&nbsp;</th>';
            }
        }
        $this->m_footer .= '</tr>';

    }

    public function GetContent($columns)
    {
        $table = false;
        if ($this->m_table != false) {
            $rows = '';
            $trNr = 0;
            $cCnt = count($columns);

            foreach ($this->m_table as $row) {

                $column = '';
                $tdNr   = 0;

                // checkbox column
                $postValues = false;
                if (isset($_POST['grid_cbx_v'])) {
                    $postValues = $_POST['grid_cbx_v'];
                }
                if ($this->m_isChbox) {
                    $checkboxId = $row[$this->m_chBoxIDcolumn];
                    if (isset($postValues[$checkboxId]) || in_array($checkboxId, $this->m_selectedChbox)) {
                        $selected = 'checked';
                    } else {
                        $selected = '';
                    }

                    $column .= '<td style="text-align:center; width:40px;"><input type="checkbox" name="grid_cbx_v[' . $checkboxId . ']" ' . $selected . '></td>' . $column;
                }

                // main content table
                for ($i = 0; $i < $cCnt; $i++) {
                    $idC = $columns[$i];

                    // commented td class selection
                    /*
               if (isset($this->m_tdClass[$tdNr]) and strlen($this->m_tdClass[$tdNr])>0) {
                  $tdC = 'class="'.$this->m_tdClass[$tdNr].'"';
               } else {$tdC = '';}
               $column .= '<td '.$tdC.'>'.$row[$idC].'</td> ';
			   */

                    $column .= '<td>' . $row[$idC] . '</td> ';
                    $tdNr++;
                }

                // form column
                if ($this->m_isFColumn) {

                    foreach ($this->m_FCColumns as $fcColumn) {
                        if (strcmp($fcColumn['type'], 'submit') != 0) {
                            $column .= '<td><input type="' . $fcColumn['type'] . '" value="' . $row[$fcColumn['value']] . '" id="' . $fcColumn['idpfx'] . $row[$fcColumn['id']] . '" name="' . $fcColumn['idpfx'] . $row[$fcColumn['id']] . '" style="width:' . $fcColumn['width'] . 'px;" ></td>'; // onBlur="parseAmount(this)"
                        } else {
                            $column .= '<td><input type="' . $fcColumn['type'] . '" value="' . $fcColumn['value'] . '" id="' . $fcColumn['idpfx'] . $row[$fcColumn['id']] . '" name="' . $fcColumn['idpfx'] . $row[$fcColumn['id']] . '" style="width:' . $fcColumn['width'] . 'px;" ></td>'; // onBlur="parseAmount(this)"
                        }
                    }
                }

                // link column
                if ($this->m_isLink) {

                    foreach ($this->m_links as $linkParams) {
                        $link = '';
                        $i    = 0;
                        foreach ($linkParams['addr'] as $addr) {
                            if (isset($linkParams['ids'][$i])) {
                                $tmpID = $row[$linkParams['ids'][$i]];
                            } else { $tmpID = '';}
                            $link .= $addr . $tmpID;
                            $i++;
                        }
                        $column .= '<td><a href="' . $link . '" >' . $linkParams['title'] . '</a></td>';

                    }
                }

                // delete column
                if ($this->m_isDel) {
                    $delId = $row[$this->m_delIDcolumn];
                    $column .= '<td><a href="' . $this->m_delAddr[0] . $delId . $this->m_delAddr[1] . '" onclick="return confirmDelete();">[usuñ]</a></td>';
                }

                if (isset($this->m_trClass[$trNr])) {
                    $trC = 'class="' . $this->m_trClass[$trNr] . '"';
                } else { $trC = '';}
                $rows .= '<tr ' . $trC . '>' . $column . '</tr>' . "\r\n";
                $trNr = 1 - $trNr;
            }
            $table = '<table class="' . $this->m_class . '">' . $this->m_header . $rows . $this->m_footer . '</table>';

        }
        return $table;
    }

    public function Display($columns)
    {
        echo $this->GetContent($columns);
    }
}
