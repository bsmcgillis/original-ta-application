/*
 * Author: Blake McGillis
 * Date: March 21, 2015
 * Phase: 7
 */

/**
 * Validates the application form. If an input is not correctly filled out, a message will be displayed
 * near the input and the form will not be processed.
 */
function validateForm()
{
    /** Semester Select */

    semesterVal = $('#semester option:selected').val();
    if (semesterVal == 'default')
    {
        $('#errorSpan').remove();
        $('#semester').after("<span id='errorSpan' class='red whiteBox'> Please select a semester </span>");
        $(window).scrollTop($('#semester').offset().top -100);
        return false;
    }

    /** Year Select */

    yearVal = $('#year option:selected').val();
    if (yearVal == 'default')
    {
        $('#errorSpan').remove();
        $('#year').after("<span id='errorSpan' class='red whiteBox'> Please select a year </span>");
        $(window).scrollTop($('#year').offset().top -100);
        return false;
    }

    /** UID Text Field*/

    regex = /[uU][0-9]{7}/;
    uidVal = $('#uid').val();

    if (uidVal == "")
    {
        $('#errorSpan').remove();
        $('#uid').after("<span id='errorSpan' class='red whiteBox'> Please enter your University ID </span>");
        $(window).scrollTop($('#uid').offset().top -100);
        return false;
    }
    else if (!regex.test(uidVal))
    {
        $('#errorSpan').remove();
        $('#uid').after("<span id='errorSpan' class='red whiteBox'> Please use following UID format \"u1234567\" </span>");
        $(window).scrollTop($('#uid').offset().top -100);
        return false;
    }


    /** Previous TA Experience Dropdown and Textfield */

    //Grab all of the prev_ta divs
    prevTAList = $("div[id^='prev_ta']");

    //Loop through each to see if they're all filled out
    for (i = 0; i < prevTAList.length; i++)
    {
        //Get the ID for this div
        divID = "#" + prevTAList.eq(i).attr("id");

        //Get the children of this div
        childEles = prevTAList.eq(i).children();


        //Make sure default dropdown option is not selected
        if(childEles.eq(0).val() == "default")
        {
            $('#errorSpan').remove();
            $(divID).after("<p id='errorSpan' class='red whiteBox inBlock'> Please select a course above </p>");
            $(window).scrollTop($(divID).offset().top -100);
            return false;
        }

        //Make sure the textarea has been filled out
        if(childEles.eq(1).val() == "")
        {
            $('#errorSpan').remove();
            $(divID).after("<p id='errorSpan' class='red whiteBox inBlock'> Please include information about the course above </p>");
            $(window).scrollTop($(divID).offset().top -100);
            return false;
        }
    }

    /** Requested TA Dropdown and Textfield */

    //Grab all of the req_ta divs
    reqTAList = $("div[id^='req_ta']");

    //Loop through each to see if they're all filled out
    for (i = 0; i < reqTAList.length; i++)
    {
        //Get the ID for this div
        divID = "#" + reqTAList.eq(i).attr("id");

        //Get the children of this div
        childEles = reqTAList.eq(i).children();


        //Make sure default dropdown option is not selected
        if(childEles.eq(0).val() == "default")
        {
            $('#errorSpan').remove();
            $(divID).after("<p id='errorSpan' class='red whiteBox inBlock'> Please select a course above </p>");
            $(window).scrollTop($(divID).offset().top -100);
            return false;
        }

        //Make sure the textarea has been filled out
        if(childEles.eq(1).val() == "")
        {
            $('#errorSpan').remove();
            $(divID).after("<p id='errorSpan' class='red whiteBox inBlock'> Please include information about the course above </p>");
            $(window).scrollTop($(divID).offset().top -100);
            return false;
        }
    }

    /** Additional Info Textarea */
    addInfoVal = $('#add_info').val();

    if (addInfoVal == "")
    {
        $('#errorSpan').remove();
        $('#add_info').before("<p id='errorSpan' class='red whiteBox inBlock'> Please include some additional details </p>");
        $(window).scrollTop($('#add_info').offset().top -100);
        return false;
    }

    /** Optional Country of Origin Textfield */

    if (!$("#intl").hasClass("dispNone")) {

        originVal = $('#origin').val();

        if (originVal == "" || originVal == "N/A") {
            $('#errorSpan').remove();
            $('#origin').after("<span id='errorSpan' class='red whiteBox'> Please enter your country of origin</span>");
            $(window).scrollTop($('#origin').offset().top - 100);
            return false;
        }
    }

    return true;
}