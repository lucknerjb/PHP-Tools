<?php
   /***********************************************************
    *
    * File        : md5.php
    * Programmer  : Luckner Jr. Jean-Baptiste (TekCast Designs)
    * Description : Get the MD5 equivalent of a password.
    * Date        : 3 July 2010
    **********************************************************/

   /*******************************************************************
    * USING THIS SCRIPT
    *
    * This script is free to use personally and commercially. My goal is
    * to help others who code like I do and not to bother people with
    * licences and stuff. If you use it and you like it, let me know! If
    * not, please let me know what suggestions / feedback you may have!
    * Cheers and happy TekCast'ing!
   ********************************************************************/


   //-----Uncomment the following lines for error reporting.
   //error_reporting(E_ALL);
   //ini_set('display_errors', '1');

   //Initialize script vars.
   $err_string = '';
   $err_prefix = '<span style="color:red;">';
   $err_suffix = '</span>';
   $pass = '';
   $md5_pass = '';
   $results = '';

   if (isset($_POST['submit_data'])){
      $pass = str_replace(" ", "", $_POST['password']);

      if ($pass == '' || $pass == null){
         $err_string .= $err_prefix.'Please fill in the <strong>password</strong> field'.$err_suffix;
      }else{
         $md5_pass = md5($pass);
         $results = "
            Your clear text password : $pass<br /><br />
            Your MD5 equivalent : $md5_pass<br /><br />
            Copy, paste, and enjoy!
         ";
      }
   }
?>

<html>
<body>
   <strong>Enter your plain text password in the box below to get its md5 equivalent.</strong><br /><br />
   <?php
      if (isset($err_string) && $err_string !== ''){
         echo "<fieldset>$err_string</fieldset>";
      }
   ?>
   <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <table>
         <tr>
            <td>Password</td>
            <td>
               <input type="text" id="password" name="password"
                  value="<?php echo $password ;?>" size="100" />
            </td>
            <td><input type="submit" id="submit_data" name="submit_data" value="Get MD5 Equivalent" /></td>
         </tr>
      </table>
   </form>

   <h2>MD5 password</h2>
   <fieldset>
      <?php echo (isset($results)) ? $results : ''; ?>
   </fieldset>
</body>
</html>

