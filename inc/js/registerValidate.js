/*
 * Author: Blake McGillis
 * Date: March 21, 2015
 * Phase: 7
 */

/*
 * Validates the register form. If an input is not correctly filled out, a message will be displayed
 * near the input and the form will not be processed.
 */
function validateForm()
{

    /** UID Text Field - Make sure it's formatted like a valid UID */

    regex = /[uU][0-9]{7}/;
    uidVal = $('#uid').val();


    if (!regex.test(uidVal))
    {
        $('#uid').after("<p class='red whiteBox'> Use UID format: \"u1234567\" </p>");
        return false;
    }


    /** Password Text Fields - Make sure passwords meet security requirements as well as match */

    pass1Val = $('#pass1').val();
    pass2Val = $('#pass2').val();
    passRegex1 = /[0-9]/;
    passRegex2 = /[A-Z]/;
    passRequirements = "Passwords must be at least 8 characters long, contain at least one number, and contain at least" +
    " one uppercase letter";

    if(pass1Val.length < 8)
    {
        $('#pass2').after("<p class='red whiteBox'>" + passRequirements + "</p>");
        return false;
    }
    else if(!passRegex1.test(pass1Val))
    {
        $('#pass2').after("<p class='red whiteBox'>" + passRequirements + "</p>");
        return false;
    }
    else if(!passRegex2.test(pass1Val))
    {
        $('#pass2').after("<p class='red whiteBox'>" + passRequirements + "</p>");
        return false;
    }
    else if (pass1Val != pass2Val)
    {
        $('#pass2').after("<p class='red whiteBox'> Passwords must match </p>");
        return false;
    }

    return true;
}