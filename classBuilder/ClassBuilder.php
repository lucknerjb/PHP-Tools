<?php
   /***********************************************************
    *
    * File        : classBuilder.php
    * Programmer  : Luckner Jr. Jean-Baptiste (TekCast Designs)
    * Description : Build a class template in seconds
    * Date        : 29 June 2010
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
   $get_set_data = '';                 //The class variables data.
   $get_set_array = array();           //The  array containing each class var.
   $getters = '';                      //String containing the final Getter functions.
   $setters = '';                      //String containing the final Setter functions.
   $tab = '&nbsp;&nbsp;&nbsp;&nbsp;';  //The number of tab spaces is optional. I work with 4 spaces.
   $vars = '';                         //String containing the final class vars.
   $class_name = '';                   //The name of the class
   $class_code = '';                   //Complete class code. This can be copy pasted and used as is.
   $err_string = '';
   $err_prefix = '<span style="color:red;">';
   $err_suffix = '</span>';

   //Function purpose: return the "human readable" name of the empty field.
   function getFieldName($field){
      //Get the field name (as it is in the html code), and return a human readable field name.
      switch($field){
         case 'get_set_data':
            return 'Getters and Setters';
            break;
         case 'class_name':
            return 'Class Name';
            break;
         default:
      }
   }


   if (isset($_POST['submit_data'])){
      //Check to see if there was empty data submitted.
      foreach($_POST as $elem=>$value){
         //Check to see if there was an empty value received.
         if ($value == '' || $value == null){
            //If we have an empty field, build the error string.
            $err_string .= $err_prefix.'Please fill in the <strong>'.getFieldName($elem).'</strong>'.$err_suffix.'<br />';
         }
      }

      if ($err_string == ''){
         //Get the user submitted data.
         $class_name = $_POST['class_name'];
         $get_set_data = $_POST['get_set_data'];

         //Clean up the data. Trim the white spaces.
         $get_set_data = str_replace(" ","", $get_set_data);
         $class_name = trim($class_name);

         //Get the var names in an array.
         $get_set_array = explode(',', $get_set_data);

         //Set up final class vars text.
         foreach($get_set_array as $key=>$value){
            $vars .= $tab."var \$$value;<br />";
         }

         //Set up getter functions.
         foreach($get_set_array as $key=>$value){
            //Capitalize the first letter.
            $value_upper = ucwords($value);
            //Build the function
            $getters .= "<br />";
            $getters .= $tab."//Return the $value value<br />";   //Add commenting
            $getters .= $tab."function get$value_upper(){<br />";
            $getters .= $tab.$tab."return \$this->$value;<br />";
            $getters .= $tab."}";
            $getters .= "<br />";
         }

         //Set up setter functions.
         foreach($get_set_array as $key=>$value){
            //Capitalize the first letter.
            $value_upper = ucwords($value);
            //Build the function
            $setters .= "<br />";
            $setters .= $tab."//Set the $value value<br />";   //Add commenting
            $setters .= $tab."function set$value_upper(\$value){<br />";
            $setters .= $tab.$tab."\$this->$value = \$value;<br />";
            $setters .= $tab."}";
            $setters .= "<br />";
         }

         //Build the complete class code.
         $class_code .= "class $class_name {<br /><br />";
         $class_code .= $tab."//Class variables<br />";
         $class_code .= $vars;
         $class_code .= '<br />'.$tab.'------------------------------- GETTER FUNCTIONS -------------------------<br />';
         $class_code .= $getters;
         $class_code .= '<br />'.$tab.'------------------------------- SETTER FUNCTIONS -------------------------<br />';
         $class_code .= $setters.'<br />';
         $class_code .= "} //End $class_name class";
      }
   }
?>

<html>
<body>
   Enter the name of the GETTERS and SETTERS needed, separated by a comma.<br />
   If there is more than one word in the variable, follow standards and capitalize each new word, starting with the
   second word.<br /><br />
   <strong>Ex: name, phone, postalCode</strong><br /><br />
   <?php
      if (isset($err_string) && $err_string !== ''){
         echo "<fieldset>$err_string</fieldset>";
      }
   ?>
   <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <table>
         <tr>
            <td>Class Name</td>
            <td>
               <input type="text" id="class_name" name="class_name"
                  value="<?php echo $class_name ;?>" size="100" />
            </td>
         </tr>
         <tr>
            <td>Getters and setters</td>
            <td>
               <input type="text" id="get_set_data" name="get_set_data"
                  value="<?php echo $get_set_data ;?>" size="100" />
            </td>
            <td><input type="submit" id="submit_data" name="submit_data" value="Get Code" /></td>
         </tr>
      </table>
   </form>

   <h2>Complete Class Code</h2>
   <fieldset>
      <?php echo (isset($class_code)) ? $class_code : ''; ?>
   </fieldset>
   <h2>Variables</h2>
   <fieldset>
      <?php echo (isset($vars)) ? $vars : ''; ?>
   </fieldset>

   <h2>Getters</h2>
   <fieldset>
      <?php echo (isset($getters)) ? $getters : ''; ?>
   </fieldset>

   <h2>Setters</h2>
   <fieldset>
      <?php echo (isset($setters)) ? $setters : ''; ?>
   </fieldset>
</body>
</html>
