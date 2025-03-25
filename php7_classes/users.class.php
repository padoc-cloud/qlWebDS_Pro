<?php
  
/*
Table Fields:
	id;  user;  pass;  access_level;  last_active;  last_ip
  
Errors:
	100 002 - 'Login and password ok, couldn`t set login time';
*/

class UsersClass
{
    var $m_sessionTime = 500;
    var $m_sessionId;
    var $m_userId;
    var $m_userName;
    var $m_userPassword;
    var $m_user = false;
    var $m_isLogged = false;
    var $m_DB;
    var $m_table;
    var $m_tSites;
    var $m_accessLevel;
    var $m_userIp;

    var $eText = '';
    var $eNo = 0;
    var $eFunc = '';

    function __construct()
    {
        $this->UsersClass();
    }

    function UsersClass()
    {
        if (IS_PHP5) {
            $this->m_DB = DataBase::getInstance();
        } else {
            $this->m_DB =& DataBase::getInstance();
        }

        if ($this->m_DB->Open()) {
            $this->m_table = $this->m_DB->tablePrefix . 'users';
            $this->m_tSites = $this->m_DB->tablePrefix . 'sites';
        } else {
            $this->eText = 'DB connection failed';
            $this->eNo = 100001;
            $this->eFunc = '__construct';
        }
    }

    function GetUserByLogin($login)
    {
        $user = false;
        $query = "SELECT * FROM " . $this->m_table . " WHERE `user`='$login'";
        $res = $this->m_DB->query($query);

        if ($res != false) {
            $user = $this->m_DB->GetRow($res);
        }
        return $user;
    }

    function GetUserByEmail($email, $access_level = 2)
    {
        $user = false;
        $query = "SELECT * FROM " . $this->m_table . " WHERE `email`='" . $email . "' AND `access_level`='" . $access_level . "'";
        $res = $this->m_DB->GetRow($query);
        if ($res) {
            return $res;
        }
        return false;
    }

    function GetUser($id)
    {
        $user = false;
        $query = "SELECT * FROM " . $this->m_table . " WHERE `id`='$id'";
        $res = $this->m_DB->GetRow($query);
        if ($res) {
            return $res;
        }
        return false;
    }

    function UpdateUser($a_values, $id)
    {

        $conn = $this->m_DB->m_conn;
        $a_values = array_map(function($value) use ($conn) {
          return mysqli_real_escape_string($conn, $value);
        }, $a_values);
        
        foreach ($a_values as $key => $value) {
            $values[] = "`$key`='$value'";
        }
        $values = implode(',', $values);

        $query = 'UPDATE ' . $this->m_table . " SET $values WHERE id=$id";

        $res = $this->m_DB->query($query);

        if ($res) {
            return true;
        }
        return false;
    }

    function GetAllUsers()
    {
        $query = 'SELECT * FROM ' . $this->m_table;
        $res = $this->m_DB->GetTable($query);
        return $res;
    }

    function DelUser($user_id, $delete_listings = false)
    {
        if ($this->m_DB->BeginTransaction()) {
            $query = "DELETE FROM " . $this->m_table . " WHERE id=$user_id";
            $res = $this->m_DB->query($query);
            if ($res) {
                if ($delete_listings) {
                    $query = "DELETE FROM " . $this->m_tSites . " WHERE user_id=$user_id";
                    $res = $this->m_DB->query($query);
                }
                if ($res) {
                    $this->m_DB->CommitTransaction();
                    return true;
                }
            }
        }
        $this->m_DB->RollbackTransaction();
        return false;
    }

    function RegisterUserV2($a_keys, $a_values)
    {
      $conn = $this->m_DB->m_conn;
      $b_values = array_map(function($value) use ($conn) {
        return mysqli_real_escape_string($conn, $value);
      }, $a_values);

        $keys = implode(',', $a_keys);
        $values = implode("','", $b_values);

        $query = 'INSERT INTO ' . $this->m_table . " ( $keys ) VALUES ( '$values')";
        $res = $this->m_DB->query($query);
        $id = $this->m_DB->GetLastId();

        if ($res) {
            return true;
        }

        return false;
    }

    function RegisterUser($a_values)
    {
      if ($this->m_DB && $this->m_DB->m_conn) {
        $conn = $this->m_DB->m_conn;
        $a_values = array_map(function($value) use ($conn) {
          return mysqli_real_escape_string($conn, $value);
        }, $a_values);

        $id = $this->m_DB->InsertQuery($this->m_table, $a_values);
        return $id;
      }
      return false;
    }


    function CheckEmail($email)
    {
        $ret = false;
        $ret = preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,4}$/', $email);
        return $ret;
    }

    function CheckName($name)
    {
        $ret = false;
        $ret = preg_match('/^[a-zA-Z0-9\._-]{4,20}$/', $name);
        return $ret;
    }

    function NameExists($name)
    {
        $ret = false;
        $query = "SELECT * FROM " . $this->m_table . " WHERE `user`='$name'";
        $ret = $this->m_DB->GetRow($query);
        return $ret;
    }

    function CheckRealName($name)
    {
        $ret = false;
        $ret = preg_match('/^[a-zA-Z0-9\s\._-]{2,255}$/', $name);
        return $ret;
    }

    function SendEmail($from_email, $to_email, $title, $mail)
    {
        $header = 'From: <' . $from_email . '>' . "\r\n";
        $header .= 'Reply-To: <' . $from_email . '>' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
        $header .= 'MIME-Version: 1.0' . "\r\n";
        $header .= 'Content-type: text/html; charset=' . DEFAULT_CHARSET . "\r\n";

        return mail($to_email, $title, $mail, $header);
    }
      
      /*
      ///////////////////// POSITIONS ////////////////////
      function GetPositionsT() {
        $query = "SELECT * FROM stanowiska";
        $table = $this->m_DB->GetTable($query);
        return $table;
      }
              
      function GetPositions() {
        $combo = false;
        
        $query = "SELECT * FROM stanowiska";
        $table = $this->m_DB->GetTable($query);

        if ($table) {
          $combo = array();
          
          foreach ($table as $row) {
            $combo[$row['id']] = $row['nazwa'];
          }
        }
        return $combo;      
      }
      
      function AddPosition($name) {
        $id = false;
        $query = "INSERT INTO stanowiska (`nazwa`) VALUES ('$name')";
        $res = $this->m_DB->query($query);
        if ($res) {
         $id = $this->m_DB->GetLastId();
        } 
        return $id;       
      }

      function DeletePosition($id) {
        $ret = false;
  
        $query = "SELECT COUNT(*) as hmany FROM ".$this->m_table." WHERE stanowisko=$id";
        $res = $this->m_DB->query($query);
        if ($res) {
          $row = $this->m_DB->GetRow($res);
          $hmany = (int) $row['hmany'];
          if ($hmany === 0) {
            $query = "DELETE FROM stanowiska WHERE id=$id LIMIT 1";
            $res = $this->m_DB->query($query);
            if ($res) {
              $ret = $this->m_DB->Affected();
            }
          } 
          
        }

        $res = $this->m_DB->query($query);      
        
        return $ret;
    
      }
        
      function UpdatePosition($name,$id){
        $query = "UPDATE stanowiska SET `nazwa`='$name' WHERE id=$id";
        $res = $this->m_DB->query($query);
        if ($res) {
          $res = $this->m_DB->Affected();
        }
        return $res;      
      }
      */

}

?>
