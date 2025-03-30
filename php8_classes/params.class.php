<?php

/*
  This file performs parameter management operations for the application. It includes methods for initializing, retrieving, updating, and storing serialized parameters in a database.
  
  Parameters table name: prefix_params
*/

class ParamsClass
{
    public $m_table;
    public $m_DB;
    public $m_tablePrefix;
    public $m_params = [];

    public $m_paramsTable;
    public $pError = false;

    // errors
    public $eText;
    public $eNo;
    public $eFunc;

    public function __construct()
    {
        $this->initialize();
    }

    private function initialize()
    {
        $this->m_DB = DataBase::getInstance();
        if (!$this->m_DB->Open()) {
            $this->eText = 'DB connection failed';
            $this->eNo   = 300001;
            $this->eFunc = '__construct';
        } else {
            $this->m_tablePrefix = $this->m_DB->tablePrefix;
            $this->m_table       = $this->m_tablePrefix . 'params';

            $this->InitParams();
        }
    }

    public function InitParams()
    {
        $query = "SELECT * FROM $this->m_table";
        $table = $this->m_DB->GetTable($query);

        if (count($table) > 0) {
            foreach ($table as $row) {
                $aParams = $this->safeUnserialize($row['params']);
                $this->m_params[$row['id']] = $aParams;

                $this->m_paramsTable[$row['id']] = $row['params'];
            }
        } else {
            return false;
        }
        return true;
    }

    public function UpdateParams($type, $aParams, $preserve = true)
    {
        if ($preserve && isset($this->m_paramsTable[$type])) {
            $oldParams = $this->safeUnserialize($this->m_paramsTable[$type]);
            if (is_array($oldParams)) {
                $aParams = array_merge($oldParams, $aParams);
            }
        }

        $sParams = serialize($aParams);
        $ret     = $this->m_DB->UpdateQuery($this->m_table, ['params' => $sParams], $type);

        if ($ret === 0) {
            $ret = $this->m_DB->SelectQuery($this->m_table, $type);
            if (is_array($ret) && count($ret) === 0) {
                $ret = $this->m_DB->InsertQuery($this->m_table, ['params' => $sParams, 'id' => $type]);
            }
        }

        $this->InitParams();
        return $ret;
    }

    public function GetParams($type)
    {
        $pTable = $this->m_paramsTable;

        if (isset($pTable[$type])) {
            $aParams = $this->safeUnserialize($pTable[$type]);
            return $aParams;
        } else {
            return false;
        }
    }

    public function Get($type, $name)
    {
        $this->pError = false;
        if (isset($this->m_params[$type]) && isset($this->m_params[$type][$name])) {
            return $this->m_params[$type][$name];
        } else {
            $this->pError = true;
            return false;
        }
    }

    /**
     * Safely unserialize a string.
     *
     * @param string $data The serialized string.
     * @return mixed The unserialized data, or false if the string is invalid.
     */
    private function safeUnserialize($data)
    {
        if (empty($data) || !is_string($data)) {
            return false;
        }
        // Validate the serialized string format
        if (preg_match('/^[aOs]:/', $data) && @unserialize($data) !== false) {
            try {
                return unserialize($data, ['allowed_classes' => false]);
            } catch (\Exception $e) {
                $this->eText = 'Unserialization failed: ' . $e->getMessage();
                return false;
            }
        } else {
            $this->eText = 'Invalid serialized string format.';
            return false;
        }
    }
}
