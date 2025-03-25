<?php
  
  class DataBase {
        private static $oInstance = false;
        
        var $m_dbHost = DB_HOST;        
            
        var $m_dbName = DB_NAME;
        var $m_dbUser = DB_USER;
        var $m_dbPass = DB_PASS;
        var $tablePrefix = DB_TABLE_PREFIX;
        
        private $m_isDebug = false;
        private $m_isConnected = false;
        private $m_isTransaction = false;
        public $m_conn;
        private $m_lastQueryTime;
        
        private $m_qStart = 0;
        public $m_qTime = array();
        
        public function __construct() {
         
        }
        
        public static function getInstance ( )
        {
          if ( self::$oInstance == false ) {
            self::$oInstance = new Database( );
          }
           
          return self::$oInstance;
        }

        public function Config($host, $db, $user, $pass) {
          // global $g_debug;
          // $this->m_isDebug = $g_debug;
          $this->m_dbHost = $host;
          $this->m_dbName = $db;
          $this->m_dbUser = $user;
          $this->m_dbPass = $pass;
        }
        
        public function Open() {

          if (!($this->m_conn and $this->m_isConnected)) {
           $this->Tic();
           $this->m_conn = mysql_connect($this->m_dbHost,$this->m_dbUser,$this->m_dbPass);
           if ($this->m_conn) {
              if (mysql_select_db($this->m_dbName, $this->m_conn)) {
                if (defined('CHARACTER_SET')) {
                 $odp = $this->query("SET CHARACTER SET ".CHARACTER_SET);
                } 
                $this->m_isConnected = true;
              } else {
                 trigger_error('Couldn`t connect to database: '.$this->m_dbName.'<br>'.mysql_error(),E_USER_ERROR);

                 $this->m_isConnected = false;
             }

           } else {
             echo mysql_error();
             $this->m_isConnected = false;
           }
           $this->Toc('start');
          }

          return $this->m_isConnected;
        }
        
        public function BeginTransaction() {
           $res = false;
           if ($this->m_isConnected) {
              $res = $this->query('START TRANSACTION');
              if ($res) {
                 $this->m_isTransaction = true;
              }
           }else {
             throw new Exception("You must be connected to begin transaction\r\n");
           }
           return $res;
        }
        public function CommitTransaction() {
             $res = $this->query('COMMIT');
             if ($res) {
                $this->m_isTransaction = false;
             }
             return $res;
        }
        public function RollbackTransaction() {
             $res = $this->query('ROLLBACK');
             if ($res) {
                $this->m_isTransaction = false;
             }
             return $res;
        }
        
        public function query($query){
           $this->Tic();
           $res = mysql_query($query, $this->m_conn);
           $this->Toc($query);

           if (!$res) {

              echo '<br>****************************<br>'.$query.'<br>'.mysql_error($this->m_conn).'<br>****************************<br>';
              // throw new Exception('Wrong query: '.$query."\r\n");
           }
           return $res;
        }
        
        public function Count($res){
           return mysql_num_rows($res);
        }
        
        public function GetTable($query) {

           $res = $this->query($query);

           if ($res != false) {
              $table = array();
              while ($row = mysql_fetch_assoc($res)) {
                    $table[] = $row;
              }
              return $table;
           }
           return false;

        }
        
        function GetRow($query) {
          $res = $this->query($query);        
          if ($res and is_resource($res)) {
             $row = mysql_fetch_assoc($res);
             return $row;
          }  
          return false;
        }
        
        public function GetLastId() {
           $lastId = mysql_insert_id($this->m_conn);
           return $lastId;

        }
        
        public function Affected() {
          $hmany = mysql_affected_rows();
          return $hmany;
        }

        public function InsertQuery($table, $values) {
         $a_values = array();
         $a_keys = array();
        
         $values = array_map("mysql_real_escape_string", $values);
         foreach ($values as $key=>$value) {
            $a_keys[] = $key;
            $a_values[] = $value; 
         }
         
         $keys = implode($a_keys, ',');
         $values = implode($a_values, "','");

         $query = 'INSERT INTO '.$table." ( $keys ) VALUES ( '$values')";
         $res = $this->query($query);

         if ($res) {
               return $this->GetLastId();
         }

         return false;
        }
        
        public function UpdateQuery($table, $values, $id) {
         $atmp = array();
         $values = array_map("mysql_real_escape_string", $values);  
    
          foreach ($values as $key=>$value) {
            $atmp[] = "$key = '$value'"; 
          }

          $sets = implode($atmp,', ');
          
          $query = "UPDATE $table SET $sets WHERE id='$id'";
          $ret = $this->query($query);
          if ($ret) {
            $ret = $this->Affected();
          }
          return $ret;
          
        }
        
        function SelectQuery($table, $id) {
           
          $id = mysql_real_escape_string($id);  

          $query = "SELECT * FROM ".$table." WHERE id='$id'";  
          $tRet = $this->GetTable($query);
          return $tRet;
        }
        
        function Close() {
           if($this->m_isConnected ) {
            $this->m_isConnected = false;
            mysql_close($this->m_conn);
           }

        }
                        
        public function __destruct() {
           if($this->m_isConnected ) {
            if ($this->m_isTransaction == true) {
            // throw new Exception('Transaction is pending');
            }

            $this->m_isConnected = false;
            mysql_close($this->m_conn);
           }

        }
        
        private function Tic() {
           $mtime=microtime();
           $mtime=explode(" ",$mtime);
           $mtime=$mtime[1] + $mtime[0];
           $this->m_qStart =$mtime;
        }
        private function Toc($query) {
           $mtime=microtime();
           $mtime=explode(" ",$mtime);
           $tEnd =$mtime[1] + $mtime[0];
           $all = $tEnd - $this->m_qStart;
           $all = round($all, 6);           
           $this->m_qTime[] = '['.$all.']['.$query.']';
           
        }
  }

?>
