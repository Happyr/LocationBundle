/**
 * Init the location bundle
 */
function locationBundle_initialize() {
    var elements=document.getElementsByClassName("google-autocomplete");

    //for every element with that class
    Array.prototype.forEach.call(elements, function(element) {
        var autocomplete = new google.maps.places.Autocomplete(element);
        autocomplete.setComponentRestrictions({'country': 'se'});

        //make sure we dont have any placeholder
        element.setAttribute("placeholder","");

        //disable 'enter' in the input. We dont want the form to be submitted when the user is choosing form the dropdown
        element.onkeydown = locationBundle_checkEnter;
    });

}

//google.maps.event.addDomListener(window, 'load', locationBundle_initialize);

/**
 * Disable enter on a specific input
 * @param e
 * @returns {boolean}
 */
function locationBundle_checkEnter(e){
    e = e || event;
    return (e.keyCode || e.which || e.charCode || 0) !== 13;
}

