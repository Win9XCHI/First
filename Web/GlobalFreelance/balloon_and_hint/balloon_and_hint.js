var map;
		
function initialize() {
    var mapOptions = {
        zoom: 6,
        center: new google.maps.LatLng(48.45, 35.02)
    };

    map = new google.maps.Map(document.getElementById("map"), mapOptions);
}


google.maps.event.addDomListener(window, "load", initialize);


function SetMarker(location, title) {
    var marker = new google.maps.Marker({
        position: location,
        map: map,
        title: title
    });
}


/**
 * First example. Using "setTimeout" function.
 */
var location1 = new google.maps.LatLng(48.5, 35.2);
var title1 = "Dnipropetrovsk";

// Will NOT work because google map has not load yet.
SetMarker();

// Will work after 3 seconds delay.
setTimeout(SetMarker, 3000, location1, title1);


/**
 * Second example. Using click event of DIV element.
 */
var location2 = { lat: 50.43, lng: 30.52 };
var title2 = "Kyiv";

var elementToClick = document.getElementById("click_to_view_a_marker");
elementToClick.onclick = function() {
    SetMarker(location2, title2);
};
