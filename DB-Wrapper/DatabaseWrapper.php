<?php
/***************************************************************************
* CLASS : DatabaseWrapper
* DESCR : Database Wrapper to abstract db calls from code
* AUTHOR: Luckner Jr. Jean-Baptiste --> TekCastDesigns.ca
* DATE  : November 17, 2009
***************************************************************************/

   require_once 'SystemComponent.php';
   class DatabaseWrapper extends SystemComponent{
      //Declaration of variables.
      var $db_name;
      var $db_user;
      var $db_pass;
      var $db_host;
      var $db_link;
      var $db_query;
      var $settings;

      //Constructor
      function DatabaseWrapper(){
         $settings = SystemComponent::getSettings();

         $this->db_user = $settings['dbuser'];
         $this->db_pass = $settings['dbpass'];
         $this->db_host = $settings['dbhost'];
         $this->db_name = $settings['dbname'];

         /*$this->db_user = 'root';
         $this->db_pass = 'beefcake';
         $this->db_host = 'localhost';
         $this->db_name = 'luckydesigns';*/
      }

      //--------------------- MUTATORS ----------------------//
      function changeUser($user){
         $this->db_user = $user;
      }
      function changePass($pass){
         $this->db_pass = $pass;
      }
      function changeName($name){
         $this->db_name = $name;
      }
      function changeHost($host){
         $this->db_host = $host;
      }
      function changeAll($user, $pass, $host, $name){
         $this->db_user = $user;
         $this->db_pass = $pass;
         $this->db_host = $host;
         $this->db_name = $name;
      }

      //--------------------- CONNECTIONS ----------------------//
      //Create a new connection.
      function connect(){
         $this->db_link = mysql_connect($this->db_host, $this->db_user, $this->db_pass)
                        or die('Could not connect to database.');
         mysql_select_db($this->db_name) or die('Could not open database.');
      }

      //Simply close the mysql connection if it is active.
      function disconnect(){
         if (isset($this->db_link)){
            mysql_close($this->db_link);
         }else{
            mysql_close();
         }
      }

      //------------------------ QUERIES -----------------------------//

      //For queries that have no return values (UPDATE, DELETE, INSERT)
      function iquery($qry){
         $this->db_query = $qry;

         if (!isset($this->db_link)){
            $this->connect();
         }
         $temp = mysql_query($qry, $this->db_link) or die ('ERROR: ' . mysql_error());
      }

      //For queries that need a return value (SELECT)
      function query($qry){
         $this->db_query = $qry;
         if (!isset($this->db_link)){
            $this->connect();
         }
         $result = mysql_query($qry, $this->db_link) or die ('ERROR: ' . mysql_error());
         return $result;
      }

      //Return the number of rows from the result set.
      function num($result){
         return mysql_num_rows($result);
      }

      //Return the number of affected rows.
      function affected(){
         return mysql_affected_rows();
      }

      //Return the last inserted ID.
      function lastID(){
         return mysql_insert_id();
      }

      //Return the result array so we can go through it.
      function fetchArray($result){
         return mysql_fetch_array($result);
      }

      //For debugging purposes. Show the query sent to the Object.
      function getQuery() {
         return $this->db_query;
      }

   }//END OF database CLASS
?>

