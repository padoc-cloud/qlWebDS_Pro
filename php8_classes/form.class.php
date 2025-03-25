<?php

// This file performs form generation and validation operations for the application. It includes methods for dynamically creating form fields, handling input values, and validating user input.

class FormClass
{
    public $m_errors     = [];
    public $m_combo      = [];
    public $m_comboId    = [];
    public $m_comboValue = [];

    public function __construct()
    {

    }

    public function SetCombo($name, $combo, $comboId, $comboValue = false)
    {

        if (! $comboValue) {
            $comboValue = $comboId;
        }

        $this->m_combo[$name]      = $combo;
        $this->m_comboId[$name]    = $comboId;
        $this->m_comboValue[$name] = $comboValue;
    }

    public function MakeForm($dane, $values, $errors = [])
    {

        $this->m_errors = $errors;

        foreach ($dane as $key => $value) {
            $value = rtrim($value);
            $row   = explode('|', $value);
            $row   = array_map('trim', $row);

            if (strlen($row[0]) < 1) {
                $row[0] = 'T-150';
            }
            $type = $row[0];

            $field = $row[1];

            if (! isset($row[2])) {

                if (isset($values[$field])) {$insert = $values[$field];} else { $insert = false;}

            } else { $insert = $row[2];}

            $kod = $this->GetCode($type, $field, $insert);

            if ($kod !== false) {
                if (strcmp($type, 'CK')) {
                    $replace            = '{' . $field . '}';
                    $template[$replace] = $kod;
                } else {
                    $replace            = '{' . $field . ' ' . $insert . '}';
                    $template[$replace] = $kod;
                }
            }
        }

        return $template;
    }

    // private
    public function GetCode($ntyp, $column, $insert)
    {
        $tmp = explode('-', $ntyp);
        $ile = count($tmp);
        $typ = $tmp[0];
        $kod = false;

        switch ($typ) {
            case 'T':
                if (! $this->CheckText($insert)) {$this->m_errors[$column] = '1';}
                if (isset($this->m_errors[$column])) {$fclass = ' class="form_error" ';} else { $fclass = '';}

                if ($ile > 1) {
                    $kod = '<input type="text" name=' . $column . ' style="width:' . $tmp[1] . 'px" value="' . $insert . '" ' . $fclass . '>';
                } else {
                    $kod = '<input type="text" name=' . $column . ' value="' . $insert . '" ' . $fclass . '>';
                }
                break;
            case 'P':
                if (! $this->CheckText($insert)) {$this->m_errors[$column] = '1';}
                if ($ile > 1) {
                    $kod = '<input type="password" name=' . $column . ' style="width:' . $tmp[1] . 'px" value="' . $insert . '">';
                } else {
                    $kod = '<input type="password" name=' . $column . ' value="' . $insert . '">';
                }
                break;

            case 'F':
                if (! $this->CheckNumber($insert)) {$this->m_errors[$column] = '1';}
                if ($ile > 1) {
                    $kod = '<input type="text" name=' . $column . ' style="width:' . $tmp[1] . 'px; text-align: right;" onBlur="parseAmount(this)" value="' . $insert . '">';
                } else {
                    $kod = '<input type="text" name=' . $column . ' style="text-align: right;" onBlur="parseAmount(this)" value="' . $insert . '">';
                }
                break;
            case 'I':
                if (! $this->CheckInt($insert)) {$this->m_errors[$column] = '1';}
                if ($ile > 1) {
                    $kod = '<input type="text" name=' . $column . ' style="width:' . $tmp[1] . 'px" onBlur="parseAmount(this)" value="' . $insert . '">';
                } else {
                    $kod = '<input type="text" name=' . $column . ' onBlur="parseAmount(this)" value="' . $insert . '">';
                }
                break;
            case 'TA':
                if (! $this->CheckText($insert)) {$this->m_errors[$column] = '1';}
                if ($ile > 1) {
                    $kod = '<textarea name=' . $column . ' rows="' . $tmp[2] . '" cols="' . $tmp[3] . '" style="width:' . $tmp[1] . 'px">' . $insert . '</textarea>';
                } else {
                    $kod = '<textarea name=' . $column . ' >' . $insert . '</textarea>';
                }
                break;
            case 'H':
                if (! $this->CheckText($insert)) {$this->m_errors[$column] = '1';}
                $kod = '<input type="hidden" name=' . $column . ' value="' . $insert . '">';
                break;
            case 'C':

                if (! empty($insert)) {
                    $kod = '<input type="checkbox" name=' . $column . ' checked >';
                } else {
                    $kod = '<input type="checkbox" name=' . $column . ' >';
                }
                break;
            case 'CBOM':
            case 'CBO':
                if (strcmp($typ, 'CBOM') === 0) {$multiple = ' size="12" multiple';} else { $multiple = '';}

                $kod = '<select name="' . $column . '" id="' . $column . '" ' . $multiple . '>';
                if ($this->m_combo[$column] and count($this->m_combo[$column]) > 0) {

                    foreach ($this->m_combo[$column] as $value) {
                        $cboKey = $value[$this->m_comboId[$column]];
                        if ($insert == $cboKey) {
                            $kod .= '<option value="' . $cboKey . '" selected>' . $value[$this->m_comboValue[$column]];
                        } else {
                            $kod .= '<option value="' . $cboKey . '">' . $value[$this->m_comboValue[$column]];
                        }
                    }
                }
                $kod .= '</select>';
                break;
            case 'CK':
                if (! empty($insert)) {
                    $kod = '<input type="radio" name="' . $column . '" value="' . $insert . '" >';
                } else {
                    $kod = '<input type="radio" name="' . $column . '" >';
                }
                break;
            case 'FL':
                $kod = '<input type="hidden" name="MAX_FILE_SIZE" value="10000000000" ><input name="' . $column . '" type="file" >';
                break;
        }
        return $kod;
    }

    public function CheckText($str)
    {
        if (empty($str)) {
            return false;
        } else {
            return true;
        }

    }
    public function CheckInt($int)
    {
        return is_numeric($int);
    }
    public function CheckNumber($number)
    {
        return is_numeric($number);
    }
}
