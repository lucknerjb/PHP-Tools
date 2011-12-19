<?php
/***************************************************************************
* CLASS : SystemComponent
* DESCR : Config file for thr Database Wrapper
* AUTHOR: Luckner Jr. Jean-Baptiste --> TekCastDesigns.ca
* DATE  : November 17, 2009
***************************************************************************/

class SystemComponent{
   var $settings;

   function getSettings(){
      //System variables.
      $settings['siteDir'] = 'http://www.example.com/';

      //Database variables.
      $settings['dbhost'] = 'localhost';
      $settings['dbuser'] = 'USER';
      $settings['dbpass'] = 'PASSWORD';
      $settings['dbname'] = 'DB_NAME';

      return $settings;
   }
}
?>
