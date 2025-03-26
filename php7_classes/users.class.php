<?php

/*
  This file performs user management operations for the application. It includes methods for retrieving, updating, deleting, and registering users, as well as validating user data and managing user sessions.

  Table Fields:
    id;  user;  pass;  access_level;  last_active;  last_ip
    
*/

class UsersClass
{
    public $m_sessionTime = 500;
    public $m_sessionId;
    public $m_userId;
    public $m_userName;
    public $m_userPassword;
    public $m_user     = false;
    public $m_isLogged = false;
    public $m_DB;
    public $m_table;
    public $m_tSites;
    public $m_accessLevel;
    public $m_userIp;

    public $eText = '';
    public $eNo   = 0;
    public $eFunc = '';

    public function __construct()
    {
        $this->UsersClass();
    }

    public function UsersClass()
    {
        if (IS_PHP5) {
            $this->m_DB = DataBase::getInstance();
        } else {
            $this->m_DB = &DataBase::getInstance();
        }

        if ($this->m_DB->Open()) {
            $this->m_table  = $this->m_DB->tablePrefix . 'users';
            $this->m_tSites = $this->m_DB->tablePrefix . 'sites';
        } else {
            $this->eText = 'DB connection failed';
            $this->eNo   = 100001;
            $this->eFunc = '__construct';
        }
    }

    public function GetUserByLogin($login)
    {
        $user  = false;
        $query = "SELECT * FROM " . $this->m_table . " WHERE `user`='$login'";
        $res   = $this->m_DB->query($query);

        if ($res != false) {
            $user = $this->m_DB->GetRow($res);
        }
        return $user;
    }

    public function GetUserByEmail($email, $access_level = 2)
    {
        $user  = false;
        $query = "SELECT * FROM " . $this->m_table . " WHERE `email`='" . $email . "' AND `access_level`='" . $access_level . "'";
        $res   = $this->m_DB->GetRow($query);
        if ($res) {
            return $res;
        }
        return false;
    }

    public function GetUser($id)
    {
        $user  = false;
        $query = "SELECT * FROM " . $this->m_table . " WHERE `id`='$id'";
        $res   = $this->m_DB->GetRow($query);
        if ($res) {
            return $res;
        }
        return false;
    }

    public function UpdateUser($a_values, $id)
    {

        $conn     = $this->m_DB->m_conn;
        $a_values = array_map(function ($value) use ($conn) {
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

    public function GetAllUsers()
    {
        $query = 'SELECT * FROM ' . $this->m_table;
        $res   = $this->m_DB->GetTable($query);
        return $res;
    }

    public function DelUser($user_id, $delete_listings = false)
    {
        if ($this->m_DB->BeginTransaction()) {
            $query = "DELETE FROM " . $this->m_table . " WHERE id=$user_id";
            $res   = $this->m_DB->query($query);
            if ($res) {
                if ($delete_listings) {
                    $query = "DELETE FROM " . $this->m_tSites . " WHERE user_id=$user_id";
                    $res   = $this->m_DB->query($query);
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

    public function RegisterUserV2($a_keys, $a_values)
    {
        $conn     = $this->m_DB->m_conn;
        $b_values = array_map(function ($value) use ($conn) {
            return mysqli_real_escape_string($conn, $value);
        }, $a_values);

        $keys   = implode(',', $a_keys);
        $values = implode("','", $b_values);

        $query = 'INSERT INTO ' . $this->m_table . " ( $keys ) VALUES ( '$values')";
        $res   = $this->m_DB->query($query);
        $id    = $this->m_DB->GetLastId();

        if ($res) {
            return true;
        }

        return false;
    }

    public function RegisterUser($a_values)
    {
        if ($this->m_DB && $this->m_DB->m_conn) {
            $conn     = $this->m_DB->m_conn;
            $a_values = array_map(function ($value) use ($conn) {
                return mysqli_real_escape_string($conn, $value);
            }, $a_values);

            $id = $this->m_DB->InsertQuery($this->m_table, $a_values);
            return $id;
        }
        return false;
    }

    public function CheckEmail($email)
    {
        $ret = false;
        $ret = preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,4}$/', $email);
        return $ret;
    }

    public function CheckName($name)
    {
        $ret = false;
        $ret = preg_match('/^[a-zA-Z0-9\._-]{4,20}$/', $name);
        return $ret;
    }

    public function NameExists($name)
    {
        $ret   = false;
        $query = "SELECT * FROM " . $this->m_table . " WHERE `user`='$name'";
        $ret   = $this->m_DB->GetRow($query);
        return $ret;
    }

    public function CheckRealName($name)
    {
        $ret = false;
        $ret = preg_match('/^[a-zA-Z0-9\s\._-]{2,255}$/', $name);
        return $ret;
    }

    public function SendEmail($from_email, $to_email, $title, $mail)
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
