$(document).ready(function () {
    $("#sh").on('click', function () {
        if ($("#sh").text() == "Show aditional search properties") {
            $("#sh").text("Hide aditional search properties");
        } else {
            $("#sh").text("Show aditional search properties");
        }
        $("#additional").toggleClass("invisible");
    });

    // $.ajax({
    //     url: "http://46.101.221.106/api/categories?token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6ImRlbmtvbWFuY2Vza2kxMjNAZ21haWwuY29tIiwiaWQiOjE4NywiaWF0IjoxNDc4MDEyNDMxfQ.snQ9PvwVTrsJlNIfi69ZP5flsZe3lntaPCsszAakU9U",
    //     type: "GET",
    //     success: function (msg) {
    //         msg.forEach(function (category) {
    //             // $('#category').append("<option id="+category['id']+">"+category['name']+"</option>");
    //             console.log(category['id']);
    //             // console.log(standardItems);
    //         })
    //     }
    // });


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

$("#standard-item").hide();
$(".standardItemsValues").hide();

var selectedCategory = "";
categories.forEach(function(category){
    // console.log(category['name']);
    $('#category').append("<option id="+category['id']+">"+category['name']+"</option>");
});

$("#category").on('click', function (e) {
    selectedCategory=$('#category option:selected').attr('id');
    $('#standard-item').empty();
    standardItems.forEach(function(standardItem){
        if (standardItem['category_id']==selectedCategory){
            $('#standard-item').append("<option id="+standardItem['id']+">"+standardItem['name']+"</option>");
        }
    });
    $("#standard-item").show();
});

$("#standard-item").on('click', function (e) {
    $(".standardItemsValues").show();
});

$(".addStandardItem").on('click', function (e) {
    e.preventDefault();
    $(".mainStandardItem").clone().appendTo(".standardItems");
});