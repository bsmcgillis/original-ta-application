/*
* Author: Blake McGillis
* Date: March 21, 2015
* Phase: 7
*/

/**
 * This file contains jQuery functions for the adminCoursList.php page
 *
 */

/*
* I need to modify this so that it checks to see if there is already an extra info div that can just be shown or
* hidden. If there is not already an extra info div, then the ajax needs to be run.
*/

function extraInfo(courseNum, semester, year)
{
    var infoDiv = "#extra_info_" + courseNum;
    var courseDiv = "#course_" + courseNum;

    if (semester == "4"){
        semester = "spring";
    }
    else if (semester == "6"){
        semester = "summer";
    }
    else if (semester == "8"){
        semester = "fall";
    }


    //If the extra_info div exists, just toggle its visibility
    if ( $(infoDiv).length ) {

        if ($(infoDiv).hasClass("dispNone")) {
            $(infoDiv).removeClass("dispNone");
        }
        else {
            $(infoDiv).addClass("dispNone");
        }
    }
    //If it doesn't exist, we'll need to get one to display
    else {
        $.ajax(
        {
            type:'POST',
            url:'inc/additCourseInfo.php',
            data: {courseNumber: courseNum, courseYear: year, courseSemester: semester},
            dataType: 'html',
            success: function (response){
                $(response).insertAfter(courseDiv);
            },
            error: function(response, options, error){
                alert("Error!: " + response.statusText + " || " + "Options: " + options + " || " + "Error: " + error);
            }
        });
    }
}

function updateRecommend(semester, year, uid, courseNum){

    //Put useful information in variables to make it easy to get
    var selectBox = "#select_" + uid + "_" + courseNum;
    var newVal = $(selectBox).val();

    $.ajax(
        {
            type:'POST',
            url:'inc/updateTARec.php',
            data: {courseNumber: courseNum, courseYear: year, courseSemester: semester, studentUID: uid,
                    newRecLevel: newVal},
            dataType: 'html',
            success: function (){
                //Turn the dropdown text green to let the user know the change was recorded
                if (!$(selectBox).hasClass("green")) {
                    $(selectBox).addClass("green");
                }
            },
            error: function(response, options, error){
                alert("Error!: " + response.statusText + " || " + "Options: " + options + " || " + "Error: " + error);
            }
        });
}