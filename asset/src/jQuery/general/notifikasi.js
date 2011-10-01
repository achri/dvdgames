var focusInMe = true;

/*
function getTime()
{
        var currentTime = new Date()
        var hours = currentTime.getHours()
        var minutes = currentTime.getMinutes()
        var seconds = currentTime.getSeconds()

        var suffix = "AM";
        if (hours >= 12) {
        suffix = "PM";
        hours = hours - 12;
        }
        if (hours == 0) {
        hours = 12;
        }

        if (minutes < 10)
        minutes = "0" + minutes
        return hours + ":" + minutes + ":" + seconds + " " + suffix;
}
*/

function onBlur() {
        focusInMe = false;
};

function onFocus(){
        focusInMe = true;
};

if (/*@cc_on!@*/false) { // check for Internet Explorer
        document.onfocusin = onFocus;
        document.onfocusout = onBlur;
} else {
        window.onfocus = onFocus;
        window.onblur = onBlur;
}

var orgTitle = document.title;
var myTitle = "ADA ORDER BARU !!!";
var isShown = false;
function changeTitle()
{
        //alert(!focusInMe && !isShown);
        if(!focusInMe && !isShown)
        {
                document.title = myTitle;
                isShown = true;
        }
        else
        {
                document.title = orgTitle;
                isShown = false;
        }
		//window.setTimeout(changeTitle,1000);
}