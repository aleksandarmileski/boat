$(document).ready(function () {
    $("#sh").on('click', function () {
        if ($("#sh").text() == "Show aditional search properties") {
            $("#sh").text("Hide aditional search properties");
        } else {
            $("#sh").text("Show aditional search properties");
        }
        $("#additional").toggleClass("invisible");
    });

});

$("#getInfo").submit(function (e) {
    e.preventDefault();
});

var map;
var latitude=Number(latitude);
var longitude=Number(longitude);
// console.log(typeof Number(latitude)+" "+latitude+" "+ typeof Number(longitude)+" "+longitude);
// icon: "http://www.vertex.com.mk/img/logo_enterprise.png"

function initMap() {
    var location = {lat: latitude, lng: longitude};

    map = new google.maps.Map(document.getElementById('map'), {
        center: location,
        zoom: 8
    });
    var marker = new google.maps.Marker({
        position: location,
        map: map
    });
}

