/*
 * Author: Blake McGillis
 * Date: March 21, 2015
 * Phase: 7
 */

/**
 * Contains functions to add additional form fields to the application form.
 */



//Start the count at two since there is already one prev_ta div
var prevTACount = 2;

/**
 * Using the counter above, the function adds a new previously TA'd course division which includes a dropdown
 * box and a textarea. The count number above is used in the IDs for the new Div and its children.
 */
function addPrevTA ()
{
    //Only allow up to 5 prev_ta sections total
    if (prevTACount > 5)
    {
        $('#errorSpan').remove();
        $('#add_prev_ta').after("<span id='errorSpan' class='red whiteBox'> Only 5 Previously TA\'d Courses Allowed </span>");
        return;
    }

    //Grab all the courses from the existing dropdown
    var prevOptions = $('#prev_ta_list1 option');

    //Start up an html string
    var htmlToAppend = "";

    //Add the new division and get the Select list started
    htmlToAppend += '<div id="prev_ta' + prevTACount + '"><select id="prev_ta_list' + prevTACount +
    '" class="p_margin" name="prev_ta_select' + prevTACount + '">' +
    '<option value="default">Select Previously TA\'d Course</option>';

    //Iterate through the option list grabbed from prev_ta_list1 and add the options
    for (i = 1; i < prevOptions.length; i++)
    {
        htmlToAppend += '<option value=' + prevOptions[i].value + '>' + prevOptions[i].text + '</option>';
    }

    //Now stick the textarea in as well
    htmlToAppend += '</select>' +
    '<textarea id="prev_ta_textarea' + prevTACount + '" class="p_margin" name="prev_ta_text' + prevTACount +
    '" rows="4"cols="75">You must select a class from the dropdown. And there must be text in this box</textarea></div>';

    //Add it after the previous prev_ta div
    $('#prev_ta' + (prevTACount - 1) + '').after(htmlToAppend);

    //Increment the counter
    prevTACount++;
}



//Set up counter variable
var reqTACount = 2;

/**
 * Using the counter above, the function adds a new requested TA course division which includes a dropdown
 * box and a textarea. The count number above is used in the IDs for the new Div and its children.
 */
function addReqTA ()
{

    //Only allow up to 5 request_ta sections total
    if (reqTACount > 5)
    {
        $('#errorSpan').remove();
        $('#add_req_ta').after("<span id='errorSpan' class='red whiteBox'> Only 5 Request TA Courses Allowed </span>");
        return;
    }

    //Grab all the courses from the existing dropdown
    var reqOptions = $('#request_ta_list1 option');

    //Start up an html string
    var htmlToAppend = "";

    //Add the new division and get the Select list started
    htmlToAppend += '<div id="req_ta' + reqTACount + '"><select id="request_ta_list' + reqTACount +
    '" class="p_margin" name="request_ta_select' + reqTACount + '">' +
    '<option value="default">Select Course You\'d Like to TA</option>';

    //Iterate through the option list grabbed from prev_ta_list1 and add the options
    for (i = 1; i < reqOptions.length; i++)
    {
        htmlToAppend += '<option value=' + reqOptions[i].value + '>' + reqOptions[i].text + '</option>';
    }

    //Now stick the textarea in as well
    htmlToAppend += '</select>' +
    '<textarea id="request_ta_textarea' + reqTACount + '" class="p_margin" name="request_ta_text' + reqTACount +
    '" rows="4"cols="75">' +
    'You must select a class from the dropdown. And there must be text in this box</textarea></div>';

    //Add it after the previous req_ta div
    $('#req_ta' + (reqTACount - 1) + '').after(htmlToAppend);

    //Increment the counter
    reqTACount++;
}

/**
 * Toggles the visibility of the International Graduate Students section
 */
$(document).ready(function() {
    $('input[type=radio][name=intl]').on("change", function() {

        if($('#intl').hasClass("dispNone"))
        {
            $('#intl').removeClass("dispNone");
            $('#origin').val("Swaziland");
        }
        else
        {
            $('#intl').addClass("dispNone");
            $('#origin').val("N/A");
        }
    });
});