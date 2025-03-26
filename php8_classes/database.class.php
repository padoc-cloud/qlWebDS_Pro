<?php

// This file performs database management operations for the application. It includes methods for connecting to the database, executing queries, managing transactions, and retrieving or manipulating data.

class DataBase
{
    private static $oInstance = false;

    public $m_dbHost = DB_HOST;

    public $m_dbName    = DB_NAME;
    public $m_dbUser    = DB_USER;
    public $m_dbPass    = DB_PASS;
    public $tablePrefix = DB_TABLE_PREFIX;

    private $m_isDebug       = false;
    private $m_isConnected   = false;
    private $m_isTransaction = false;
    public $m_conn;
    private $m_lastQueryTime;

    private $m_qStart = 0;
    public $m_qTime   = [];

    public function __construct()
    {

    }

    public static function getInstance()
    {
        if (self::$oInstance == false) {
            self::$oInstance = new Database();
        }

        return self::$oInstance;
    }

    public function Config($host, $db, $user, $pass)
    {
        // global $g_debug;
        // $this->m_isDebug = $g_debug;
        $this->m_dbHost = $host;
        $this->m_dbName = $db;
        $this->m_dbUser = $user;
        $this->m_dbPass = $pass;
    }

    public function Open()
    {

        if (! ($this->m_conn and $this->m_isConnected)) {
            $this->Tic();
            $this->m_conn = mysqli_connect($this->m_dbHost, $this->m_dbUser, $this->m_dbPass);
            if ($this->m_conn) {
                if (mysqli_select_db($this->m_conn, $this->m_dbName)) {
                    if (defined('CHARACTER_SET')) {
                        $odp = $this->query("SET CHARACTER SET " . CHARACTER_SET);
                    }
                    $this->m_isConnected = true;
                } else {
                    trigger_error('Couldn`t connect to database: ' . $this->m_dbName . '<br>' . mysqli_error($this->m_conn), E_USER_ERROR);

                    $this->m_isConnected = false;
                }

            } else {
                echo mysqli_error($this->m_conn);
                $this->m_isConnected = false;
            }
            $this->Toc('start');
        }

        return $this->m_isConnected;
    }

    public function getConnection() {
        return $this->m_conn;
    }

    public function BeginTransaction()
    {
        $res = false;
        if ($this->m_isConnected) {
            $res = $this->query('START TRANSACTION');
            if ($res) {
                $this->m_isTransaction = true;
            }
        } else {
            throw new Exception("You must be connected to begin transaction\r\n");
        }
        return $res;
    }
    public function CommitTransaction()
    {
        $res = $this->query('COMMIT');
        if ($res) {
            $this->m_isTransaction = false;
        }
        return $res;
    }
    public function RollbackTransaction()
    {
        $res = $this->query('ROLLBACK');
        if ($res) {
            $this->m_isTransaction = false;
        }
        return $res;
    }

    public function query($query)
    {
        $this->Tic();
        if ($this->m_conn) {
            $res = mysqli_query($this->m_conn, $query);
        } else {
            $res = false;
            echo 'Database connection is not established.';
        }
        $this->Toc($query);

        if (! $res) {
            echo '<br>****************************<br>' . $query . '<br>' . mysqli_error($this->m_conn) . '<br>****************************<br>';
            // throw new Exception('Wrong query: '.$query."\r\n");
        }
        return $res;
    }

    public function Count($res)
    {

        return mysqli_num_rows($res);
    }

    public function GetTable($query)
    {

        $res = $this->query($query);

        if ($res != false) {
            $table = [];
            while ($row = mysqli_fetch_assoc($res)) {
                $table[] = $row;
            }
            return $table;
        }
        return false;

    }

    public function GetRow($query)
    {
        $res = $this->query($query);
        if ($res) {
            $row = mysqli_fetch_assoc($res);
            return $row;
        }
        return false;
    }

    public function GetLastId()
    {
        $lastId = mysqli_insert_id($this->m_conn);
        return $lastId;

    }

    public function Affected()
    {
        $hmany = mysqli_affected_rows($this->m_conn);
        return $hmany;
    }

    public function InsertQuery($table, $values)
    {
        $a_values = [];
        $a_keys   = [];

        $conn = $this->m_conn;
        $values = array_map(function($value) use ($conn) {
          return mysqli_real_escape_string($conn, $value);
        }, $values);

        foreach ($values as $key => $value) {
            $a_keys[]   = $key;
            $a_values[] = $value;
        }

        $keys   = implode(',', $a_keys);
        $values = implode("','", $a_values);

        $query = 'INSERT INTO ' . $table . " ( $keys ) VALUES ( '$values')";
        $res   = $this->query($query);

        if ($res) {
            return $this->GetLastId();
        }

        return false;
    }

    public function UpdateQuery($table, $values, $id)
    {
        $atmp   = [];
        $values = array_map([$this->m_conn, "mysqli_real_escape_string"], $values);

        foreach ($values as $key => $value) {
            $atmp[] = "$key = '$value'";
        }

        $sets = implode(', ', $atmp);

        $id    = mysqli_real_escape_string($this->m_conn, $id);
        $query = "UPDATE $table SET $sets WHERE id='$id'";
        $ret   = $this->query($query);
        if ($ret) {
            $ret = $this->Affected();
        }
        return $ret;

    }

    public function SelectQuery($table, $id)
    {

        $id = mysqli_real_escape_string($this->m_conn, $id);

        $query = "SELECT * FROM " . $table . " WHERE id='$id'";
        $tRet  = $this->GetTable($query);
        return $tRet;
    }

    public function Close()
    {
        if ($this->m_isConnected) {
            $this->m_isConnected = false;
            mysqli_close($this->m_conn);
        }

    }

    public function __destruct()
    {
        if ($this->m_isConnected) {
            if ($this->m_isTransaction == true) {
                // throw new Exception('Transaction is pending');
            }

            $this->m_isConnected = false;
            mysqli_close($this->m_conn);
        }

    }

    private function Tic()
    {
        $mtime          = microtime();
        $mtime          = explode(" ", $mtime);
        $mtime          = $mtime[1] + $mtime[0];
        $this->m_qStart = $mtime;
    }
    private function Toc($query)
    {
        $mtime           = microtime();
        $mtime           = explode(" ", $mtime);
        $tEnd            = $mtime[1] + $mtime[0];
        $all             = $tEnd - $this->m_qStart;
        $all             = round($all, 6);
        $this->m_qTime[] = '[' . $all . '][' . $query . ']';

    }
}
