<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php

/*
 * Holds information related to a user of the website. Includes an array of roles as well as an array of
 * App_Status objects.
 */
class User
{
    public $uid;
    public $first_name;
    public $last_name;
    public $class_request;
    public $recommend;
    public $assigned;

    public $roles;
    public $app_status_array;

    /*
    * Constructor
    */
    public function __construct()
    {
        $this->roles = array();
        $this->app_status_array = array();
    }

    function set_uid( $uid )
    {
        if (strlen($uid) > 0)
        {
            $this->uid = $uid;
        }
    }

    function get_uid()
    {
        return $this->uid;
    }

    function set_first_name( $first_name )
    {
        if (strlen($first_name) > 0)
        {
            $this->first_name = $first_name;
        }
    }

    function get_first_name()
    {
        return $this->first_name;
    }

    function set_last_name( $last_name )
    {
        if (strlen($last_name))
        {
            $this->last_name = $last_name;
        }
    }

    function get_last_name()
    {
        return $this->last_name;
    }

    function add_role( $role )
    {
        $this->roles[] = $role;
    }

    function get_role_array()
    {
        return $this->roles;
    }

    function set_class_request( $class_request )
    {
        if (strlen($class_request) > 0)
        {
            $this->class_request = $class_request;
        }
    }

    function get_class_request()
    {
        return $this->class_request;
    }

    function set_recommend( $recommend )
    {
        if (strlen($recommend) > 0)
        {
            $this->recommend = $recommend;
        }
    }

    function get_recommend()
    {
        return $this->recommend;
    }

    function set_assigned( $assigned )
    {
        if (strlen($assigned) > 0)
        {
            $this->assigned = $assigned;
        }
    }

    function get_assigned()
    {
        return $this->assigned;
    }

    function get_full_name()
    {
        return $this->first_name . " " . $this->last_name;
    }

    function add_app_status( $course, $recommend )
    {
        $this->app_status_array[] = new App_Status($course, $recommend);
    }

    function get_app_status_array()
    {
        //remove any duplicate App_Status objects before returning the array
        return array_unique($this->app_status_array, SORT_REGULAR);
    }

    function clear_app_status_array()
    {
        $this->app_status_array = array();
    }

}

/*
 * Holds information regarding the status of a TA position request
 */
class App_Status
{
    public $course;
    public $recommend;

    /*
     * Constructor
     */
    public function __construct( $course, $recommend )
    {
        $this->course = $course;
        $this->recommend = $recommend;
    }

    function get_course()
    {
        return $this->course;
    }

    function get_recommend()
    {
        return $this->recommend;
    }
}
?>