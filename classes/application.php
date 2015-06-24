<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php

/*
 * Holds information related to a TA's application. Also contains two arrays, one for previously TA'd class
 * information and one for requested TA position information.
 */
class Application
{

    public $uid;
    public $time_stamp;
    public $semester;
    public $year;
    public $degree;
    public $avail_hours;
    public $addit_details;
    public $promised_aid;
    public $grad_origin;
    public $grad_ita;

    public $prev_ta_array;
    public $request_ta_array;

    /*
     * Constructor
     */
    public function __construct()
    {
        $this->prev_ta_array = array();
        $this->request_ta_array = array();
    }

    function set_uid( $uid )
    {
        if (strlen($uid) > 0){
            $this->uid = $uid;
        }
    }

    function get_uid()
    {

        return $this->uid;
    }

    function set_time_stamp( $time_stamp )
    {
        if (strlen($time_stamp) > 0){
            $this->time_stamp = $time_stamp;
        }
    }

    function get_time_stamp()
    {

        return $this->time_stamp;
    }

    function set_semester( $semester )
    {
        if (strlen($semester) > 0){
            $this->semester = $semester;
        }
    }

    function get_semester()
    {

        return $this->semester;
    }

    function set_year( $year )
    {
        if (strlen($year) > 0){
            $this->year = $year;
        }
    }

    function get_year()
    {

        return $this->year;
    }

    function set_degree( $degree )
    {
        if (strlen($degree) > 0){
            $this->degree = $degree;
        }
    }

    function get_degree()
    {

        return $this->degree;
    }

    function set_avail_hours( $avail_hours )
    {
        if (strlen($avail_hours) > 0){
            $this->avail_hours = $avail_hours;
        }
    }

    function get_avail_hours()
    {

        return $this->avail_hours;
    }

    function set_addit_details( $addit_details )
    {
        if (strlen($addit_details) > 0){
            $this->addit_details = $addit_details;
        }
    }

    function get_addit_details()
    {

        return $this->addit_details;
    }

    function set_promised_aid( $promised_aid )
    {
        if (strlen($promised_aid) > 0){
            $this->promised_aid = $promised_aid;
        }
    }

    function get_promised_aid()
    {

        return $this->promised_aid;
    }

    function set_grad_origin( $grad_origin )
    {
        if (strlen($grad_origin) > 0){
            $this->grad_origin = $grad_origin;
        }
    }

    function get_grad_origin()
    {

        return $this->grad_origin;
    }

    function set_grad_ita( $grad_ita )
    {
        if (strlen($grad_ita) > 0){
            $this->grad_ita = $grad_ita;
        }
    }

    function get_grad_ita()
    {

        return $this->grad_ita;
    }

    function add_prev_ta( $department, $number, $name, $ta_experience )
    {
        $this->prev_ta_array[] = new Prev_TA($department, $number, $name, $ta_experience);
    }

    function get_prev_ta_array()
    {
        return $this->prev_ta_array;
    }

    function add_request_ta( $number, $name, $ta_info )
    {
        $this->request_ta_array[] = new Request_TA($number, $name, $ta_info);
    }

    function get_request_ta_array()
    {
        return $this->request_ta_array;
    }

    /*
     * toString function for testing
     */
    public function __toString()
    {

        $output =
            "<br />New One
            <p>UID: {$this->uid}</p>
            <p>TimeStamp: {$this->time_stamp}</p>";

        foreach($this->prev_ta_array as $prev)
        {
            $number = $prev->get_number();
            $ta_exp = $prev->get_ta_experience();

            $output .=
                "<p>Prev Number: $number</p>
                <p>Prev TA Experience: $ta_exp</p>";
        }

        foreach($this->request_ta_array as $req)
        {
            $number = $req->get_number();
            $ta_info = $req->get_ta_info();

            $output .=
                "<p>Req Number: $number</p>
                <p>Req TA Info: $ta_info</p>";
        }

        return $output;
    }

}

/*
 * Holds information regarding a previously TA'd course.
 */
class Prev_TA
{
    public $department;
    public $number;
    public $ta_experience;

    /*
    * Constructor
    */
    public function __construct( $department, $number, $name, $ta_experience)
    {
        $this->department = $department;
        $this->number = $number;
        $this->name = $name;
        $this->ta_experience = $ta_experience;
    }

    function get_department()
    {
        return $this->department;
    }

    function get_number()
    {
        return $this->number;
    }

    function get_name()
    {
        return $this->name;
    }

    function get_ta_experience()
    {
        return $this->ta_experience;
    }

}

/*
 * Holds information regarding a course that the applicant would like to TA.
 */
class Request_TA
{
    public $number;
    public $ta_info;


    /*
    * Constructor
    */
    public function __construct( $number, $name, $ta_info)
    {
        $this->number = $number;
        $this->name = $name;
        $this->ta_info = $ta_info;
    }


    function get_number()
    {
        return $this->number;
    }

    function get_name()
    {
        return $this->name;
    }

    function get_ta_info()
    {
        return $this->ta_info;
    }
}
?>