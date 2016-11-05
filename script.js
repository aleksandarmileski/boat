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

// --- MAPS ---
var map;
var latitude = Number(latitude);
var longitude = Number(longitude);

function initMap() {
    var location = {lat: latitude, lng: longitude};

    map = new google.maps.Map(document.getElementById('map'), {
        center: location,
        zoom: 4
    });
    var marker = new google.maps.Marker({
        position: location,
        map: map
    });
}

// Send contact form data
$("#getContactInfo").on('submit', function (e) {


    $name = $("#name").val();
    $email = $("#email").val();
    $phone = $("#phone").val();
    $options = $('input[name=options]:checked', '#getContactInfo').val();
    $notes = $("#notes").val();
    console.log($name + " " + typeof $email + " " + typeof $phone + " " + typeof $options + " " + $notes + " " + brokers_id + " " + boat_id);

    $.ajax({
        url: "boat.php?id=" + boat_id,
        type: "POST",
        data: {
            "submitInfo": "",
            "boat_id": boat_id,
            "broker_id": brokers_id,
            "name": $name,
            "email": $email,
            "phone": $phone,
            "options": $options,
            "notes": $notes
        },
        success: function (data, status) {
            console.log("Success");
        },
        error: function (xhr, desc, err) {
            console.log("error");

        }
    });
    e.preventDefault();


});
