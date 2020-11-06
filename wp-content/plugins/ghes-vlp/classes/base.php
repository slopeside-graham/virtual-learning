<?php

namespace GHES\VLP {

  /**
   * Base Class for GHES VLP Objects
   */
  class ghes_vlp_base
  {

    protected static $_User;  // WP User
    protected static $_UserIsParent;
    protected static $_UserIsVLPParent;
    protected static $_UserIsDirector;
    protected static $_UserEmail;
    protected static $_UserID;


    private static $didInit = false;  //<- To make sure static init only runs once.

    public static function User()
    {
      ghes_vlp_base::init();
      return ghes_vlp_base::$_User;
    }

    public static function UserIsParent()
    {
      ghes_vlp_base::init();
      return ghes_vlp_base::$_UserIsParent;
    }

    public static function UserIsVLPParent()
    {
      ghes_vlp_base::init();
      return ghes_vlp_base::$_UserIsVLPParent;
    }

    public static function UserIsDirector()
    {
      ghes_vlp_base::init();
      return ghes_vlp_base::$_UserIsDirector || \current_user_can('Reg-Admin') || \current_user_can('administrator');
    }

    public static function UserEmail()
    {
      ghes_vlp_base::init();
      return ghes_vlp_base::$_UserEmail;
    }

    public static function UserID()
    {
      ghes_vlp_base::init();
      return ghes_vlp_base::$_UserID;
    }

    private static function init()
    {
      if (!ghes_vlp_base::$didInit) {
        ghes_vlp_base::$didInit = true;
        // one-time init code.
        // Get the current user
        ghes_vlp_base::$_User = \wp_get_current_user();
        // Is Parent
        ghes_vlp_base::$_UserIsParent = \current_user_can('Parent');
        ghes_vlp_base::$_UserIsDirector = \current_user_can('Director');
        ghes_vlp_base::$_UserIsVLPParent = \current_user_can('VLP Parent');
        ghes_vlp_base::$_UserEmail = ghes_vlp_base::$_User->user_email;
        ghes_vlp_base::$_UserID = ghes_vlp_base::$_User->ID;
      }
    }

    public function __construct()
    {
    }

    function __set($name, $value)
    {
      if (method_exists($this, $name)) {
        $this->$name($value);
      } else {
        // Getter/Setter not defined so set as property of object
        $this->$name = $value;
      }
    }

    function __get($name)
    {
      if (method_exists($this, $name)) {
        return $this->$name();
      } elseif (property_exists($this, $name)) {
        // Getter/Setter not defined so return property if it exists
        return $this->$name;
      }
      return null;
    }

    // Remove trailing dash -> 12312-
    function fixZip($zipcode)
    {
      if (substr($zipcode, -5) == '-    ') {
        return substr($zipcode, 0, -5);
      }
      if (substr($zipcode, -5) == '-____') {
        return substr($zipcode, 0, -5);
      }
      if (substr($zipcode, -1) == '-') {
        return substr($zipcode, 0, -1);
      }
      return $zipcode;
    }
  }
}
