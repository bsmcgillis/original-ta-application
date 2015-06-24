<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->


<?php

/*
 * Holds information regarding a course. Can be used to populate courses with their information
 * or to hold applicant information regarding a course.
 */
class Course
{
    public $department;
    public $number;
    public $name;
    public $blurb;
    public $instructor;
    public $TAs;

    public $catNum;
    public $component;
    public $units;
    public $time;
    public $location;
    public $title;
    public $enroll_cap;
    public $curr_enroll;
    public $seats_avail;

    //I don't think I ever use these TA variables or their accompanying getters/setters
    public $ta_experience;
    public $ta_info;


    /*
     * Constructor
     */
    public function __construct()
    {

    }

    function set_department( $department )
    {
        if (strlen($department) > 0){
            $this->department = $department;
        }
    }

    function get_department()
    {
        return $this->department;
    }

    function set_number( $number )
    {
        if (strlen($number) > 0){
            $this->number = $number;
        }
    }

    function get_number()
    {
        return $this->number;
    }

    function set_name( $name )
    {
        if (strlen($name) > 0){
            $this->name = $name;
        }
    }

    function get_name()
    {
        return $this->name;
    }

    function set_blurb( $blurb )
    {
        if (strlen($blurb) > 0){
            $this->blurb = $blurb;
        }
    }

    function get_blurb()
    {
        return $this->blurb;
    }

    function set_ta_experience( $ta_experience )
    {
        if (strlen($ta_experience) > 0){
            $this->ta_experience = $ta_experience;
        }
    }

    function get_ta_experience()
    {
        return $this->ta_experience;
    }

    function set_ta_info( $ta_info )
    {
        if (strlen($ta_info) > 0){
            $this->ta_info = $ta_info;
        }
    }

    function get_ta_info()
    {
        return $this->ta_info;
    }

    function set_instructor( $instructor )
    {
        if (strlen($instructor) > 0)
        {
            $this->instructor = $instructor;
        }
    }

    function get_instructor()
    {
        return $this->instructor;
    }

    function set_TAs( $TAs )
    {
        if (strlen($TAs) > 0)
        {
            $this->TAs = $TAs;
        }
    }

    function get_TAs()
    {
        return $this->TAs;
    }

    function set_catNum( $catNum )
    {
        if (strlen($catNum) > 0)
        {
            $this->catNum = $catNum;
        }
    }

    function get_catNum()
    {
        return $this->catNum;
    }

    function set_component( $component )
    {
        if (strlen($component) > 0)
        {
            $this->component = $component;
        }
    }

    function get_component()
    {
        return $this->component;
    }

    function set_units( $units )
    {
        if (strlen($units) > 0)
        {
            $this->units = $units;
        }
    }

    function get_units()
    {
        return $this->units;
    }

    function set_time( $time )
    {
        if (strlen($time) > 0)
        {
            $this->time = $time;
        }
    }

    function get_time()
    {
        return $this->time;
    }

    function set_location( $location )
    {
        if (strlen($location) > 0)
        {
            $this->location = $location;
        }
    }

    function get_location()
    {
        return $this->location;
    }

    function set_title( $title )
    {
        if (strlen($title) > 0)
        {
            $this->title = $title;
        }
    }

    function get_title()
    {
        return $this->title;
    }

    function set_enroll_cap( $enroll_cap )
    {
        if (strlen($enroll_cap) > 0)
        {
            $this->enroll_cap = $enroll_cap;
        }
    }

    function get_enroll_cap()
    {
        return $this->enroll_cap;
    }

    function set_curr_enroll( $curr_enroll )
    {
        if (strlen($curr_enroll) > 0)
        {
            $this->curr_enroll = $curr_enroll;
        }
    }

    function get_curr_enroll()
    {
        return $this->curr_enroll;
    }

    function set_seats_avail( $seats_avail )
    {
        if (strlen($seats_avail) > 0)
        {
            $this->seats_avail = $seats_avail;
        }
    }

    function get_seats_avail()
    {
        return $this->seats_avail;
    }

}

?>