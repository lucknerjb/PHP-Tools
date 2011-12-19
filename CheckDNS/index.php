<?php
function myCheckDNSRR($hostName, $recType = '')
{
 if(!empty($hostName)) {
   if( $recType == '' ) $recType = "MX";
   exec("nslookup -type=$recType $hostName", $result);
   // check each line to find the one that starts with the host
   // name. If it exists then the function succeeded.
   foreach ($result as $line) {
     if(eregi("^$hostName",$line)) {
       return true;
     }
   }
   // otherwise there was no mail handler for the domain
   return false;
 }
 return false;
}
?>
