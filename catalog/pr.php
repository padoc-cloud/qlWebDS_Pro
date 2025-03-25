<?php

// PHP Google PageRank Calculator Script: 
// -------------------------- April 2005 
// Contact Author: pagerankscript@googlecommunity.com 

// for updates, visit: 
// http://www.googlecommunity.com/scripts/google-pagerank.php 

// provided by www.GoogleCommunity.com 
// an unofficial community of Google fans 
// --------------------------------------- 

// Instructions: 
// Upload pagerank.php to your server 
// Call it like this: http://www.example.com/pagerank.php?url=http://www.yahoo.com/ 
// example.com is yourwebsite.yahoo.com is the website to get the PR of 
// The code below displays the PR for $url 

/* 
   This code is released unto the public domain 
*/

// header("Content-Type: text/plain; charset=utf-8"); 
define('GOOGLE_MAGIC', 0xE6359A60); 

function zeroFill($a, $b) 
{ 
    $z = hexdec(80000000); 
        if ($z & $a) 
        { 
            $a = ($a>>1); 
            $a &= (~$z); 
            $a |= 0x40000000; 
            $a = ($a>>($b-1)); 
        } 
        else 
        { 
            $a = ($a>>$b); 
        } 
        return $a; 
} 

function toInt32(& $x){
    $z = hexdec(80000000);
        $y = (int)$x;
	    // on 64bit OSs if $x is double, negative, will return -$z in $y
	    // which means 32th bit set (the sign bit)
	          if($y==-$z&&$x<-$z){
		           $y = (int)((-1)*$x); // this is the hack, make it positive before
			       $y = (-1)*$y; // switch back the sign

			  }
			  $x = $y;
}  

// $a -= $b; $a -= $c; toInt32($a); $a = (int)($a ^ (zeroFill($c,13)));
function mix($a,$b,$c) {
  $a -= $b; $a -= $c; toInt32($a); $a = (int)($a ^ (zeroFill($c,13)));
    $b -= $c; $b -= $a; toInt32($b); $b = (int)($b ^ ($a<<8));
      $c -= $a; $c -= $b; toInt32($c); $c = (int)($c ^ (zeroFill($b,13)));
        $a -= $b; $a -= $c; toInt32($a); $a = (int)($a ^ (zeroFill($c,12)));
	  $b -= $c; $b -= $a; toInt32($b); $b = (int)($b ^ ($a<<16));
	    $c -= $a; $c -= $b; toInt32($c); $c = (int)($c ^ (zeroFill($b,5)));
	      $a -= $b; $a -= $c; toInt32($a); $a = (int)($a ^ (zeroFill($c,3)));
	        $b -= $c; $b -= $a; toInt32($b); $b = (int)($b ^ ($a<<10));
		  $c -= $a; $c -= $b; toInt32($c); $c = (int)($c ^ (zeroFill($b,15)));
		    return array($a,$b,$c);
}      

function GoogleCH($url, $length=null, $init=GOOGLE_MAGIC) { 
    if(is_null($length)) { 
        $length = sizeof($url); 
    } 
    $a = $b = 0x9E3779B9; 
    $c = $init; 
    $k = 0; 
    $len = $length; 
    while($len >= 12) { 
        $a += ($url[$k+0] +($url[$k+1]<<8) +($url[$k+2]<<16) +($url[$k+3]<<24)); 
        $b += ($url[$k+4] +($url[$k+5]<<8) +($url[$k+6]<<16) +($url[$k+7]<<24)); 
        $c += ($url[$k+8] +($url[$k+9]<<8) +($url[$k+10]<<16)+($url[$k+11]<<24)); 
        $mix = mix($a,$b,$c); 
        $a = $mix[0]; $b = $mix[1]; $c = $mix[2]; 
        $k += 12; 
        $len -= 12; 
    } 

    $c += $length; 
    switch($len) // all the case statements fall through
    { 
        case 11: $c+=($url[$k+10]<<24); 
        case 10: $c+=($url[$k+9]<<16); 
        case 9 : $c+=($url[$k+8]<<8); 
        // the first byte of c is reserved for the length
        case 8 : $b+=($url[$k+7]<<24); 
        case 7 : $b+=($url[$k+6]<<16); 
        case 6 : $b+=($url[$k+5]<<8); 
        case 5 : $b+=($url[$k+4]); 
        case 4 : $a+=($url[$k+3]<<24); 
        case 3 : $a+=($url[$k+2]<<16); 
        case 2 : $a+=($url[$k+1]<<8); 
        case 1 : $a+=($url[$k+0]); 
        // case 0: nothing left to add
    } 
    $mix = mix($a,$b,$c); 
    // report the result
    return $mix[2]; 
} 

// converts a string into an array of integers containing the numeric value of the char 
function strord($string) { 
    for($i=0;$i<strlen($string);$i++) { 
        $result[$i] = ord($string{$i}); 
    } 
    return $result; 
} 

function ReadPR($link)
{
  $fp = fsockopen ("www.google.com", 80, $errno, $errstr, 30);
  // $ip = gethostbyname("www.google.com");
  // $fp = fsockopen ($ip, 80, $errno, $errstr, 30);

  if (!$fp) 
  {
    return -1;
  }
  else
  {
    // $out = "GET $link HTTP/1.1\r\n";
    $out = "GET $link HTTP/1.0\r\n";
    $out .= "Host: toolbarqueries.google.com\r\n";
    $out .= "User-Agent: Mozilla/4.0 (compatible; GoogleToolbar 2.0.114.9-big; Windows 5.2)\r\n";
    $out .= "Connection: Close\r\n\r\n";
    fwrite($fp, $out);
    
    do{ 
       $line = fgets($fp, 128); 
    }while ($line !== "\r\n");
    $data = fread($fp,8192);   
    fclose ($fp);
    return $data;
  }
}  

function getrank($url) 
{
  $url ='info:'.$url;
  $ch = GoogleCH(strord($url));
  $data = ReadPR("/tbr?client=navclient-auto&ch=6$ch&features=Rank&q=$url");  

  $rankarray = explode (':', $data);
  if (isset($rankarray[2])) {
    return trim($rankarray[2]);
  } 
  return false;
  
}
 

	// PageRank Lookup v1.1 by HM2K (update: 31/01/07)
    // based on an alogoritham found here: http://pagerank.gamesaga.net/
    
    // convert a string to a 32-bit integer
    function StrToNum($Str, $Check, $Magic) {
        $Int32Unit = 4294967296;  // 2^32
    
        $length = strlen($Str);
        for ($i = 0; $i < $length; $i++) {
            $Check *= $Magic;   
            // If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31),
            // the result of converting to integer is undefined
            // refer to http://www.php.net/manual/en/language.types.integer.php
            if ($Check >= $Int32Unit) {
                $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
                // if the check less than -2^31
                $Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
            }
            $Check += ord($Str{$i}); 
        }
        return $Check;
    }
    
    // genearate a hash for a URL
    function HashURL($String) {
        $Check1 = StrToNum($String, 0x1505, 0x21);
        $Check2 = StrToNum($String, 0, 0x1003F);
    
        $Check1 >>= 2;  
        $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
        $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
        $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF); 
        
        $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
        $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
        
        return ($T1 | $T2);
    }
    
    // genearate a checksum for the hash string
    function CheckHash($Hashnum) {
        $CheckByte = 0;
        $Flag = 0;
    
        $HashStr = sprintf('%u', $Hashnum) ;
        $length = strlen($HashStr);
        
        for ($i = $length - 1;  $i >= 0;  $i --) {
            $Re = $HashStr{$i};
            if (1 === ($Flag % 2)) {              
                $Re += $Re;     
                $Re = (int)($Re / 10) + ($Re % 10);
            }
            $CheckByte += $Re;
            $Flag ++;   
        }
    
        $CheckByte %= 10;
        if (0 !== $CheckByte) {
            $CheckByte = 10 - $CheckByte;
            if (1 === ($Flag % 2) ) {
                if (1 === ($CheckByte % 2)) {
                    $CheckByte += 9;
                }
                $CheckByte >>= 1;
            }
        }
    
        return '7'.$CheckByte.$HashStr;
    }
    
    // return the pagerank checksum hash
    function getch($url) { return CheckHash(HashURL($url)); }
    
    // return the pagerank figure
    function getpr($url) {
        $googlehost='toolbarqueries.google.com';
        $googleua  ='User-Agent: Mozilla/4.0 (compatible; GoogleToolbar 2.0.114-big; Windows XP 5.1)';
        $ch = getch($url);
        $fp = fsockopen($googlehost, 80, $errno, $errstr, 30);
        if ($fp) {
           $out = "GET /tbr?client=navclient-auto&ch=$ch&features=Rank&q=info:$url HTTP/1.1\r\n";
           // echo "<pre>$out</pre>\n"; // debug only
           $out .= "User-Agent: $googleua\r\n";
           $out .= "Host: $googlehost\r\n";
           $out .= "Connection: Close\r\n\r\n";
        
           fwrite($fp, $out);
           
           // $pagerank = substr(fgets($fp, 128), 4); // debug only
           // echo $pagerank; // debug only
           while (!feof($fp)) {
                $data = fgets($fp, 128);
                // echo $data;
                $pos = strpos($data, "Rank_");
                if($pos === false){} else{
                    $pr=substr($data, $pos + 9);
                    $pr=trim($pr);
                    $pr=str_replace("\n",'',$pr);
                    return $pr;
                }
           }
           // else { echo "$errstr ($errno)<br />\n"; } // debug only
           fclose($fp);
        }
    }
    
    // generate the numeric pagerank
    function pagerank($url,$width=40,$method='style') {
        if (!preg_match('/^(http:\/\/)?([^\/]+)/i', $url)) { $url='http://'.$url; }
        $pr=getpr($url);
		return $pr;    
    }

?>
