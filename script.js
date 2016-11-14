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
var latitude = Number(latitude), longitude = Number(longitude);

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

$('#value-from').hide();
$('#value-to').hide();

// Auto fill dropdowns
$('#category').append("<option id='all' value='all'>All categories</option>");
categories.forEach(function (category) {
    // console.log(category['name']);
    $('#category').append("<option id=" + category['id'] + " value=" + category['id'] + " >" + category['name'] + "</option>");
});
standardItems.forEach(function (standardItem) {
    $('#standard-item').append("<option id=" + standardItem['id'] + " value=" + standardItem['id'] + " >" + standardItem['name'] + "</option>");
});

// Print Div id
$(document).on('click', 'div[id^="mainStandardItem"]', function (e) {
    // console.log($(this).attr('id'));
});

// Select category an fill with Standard items options
$(document).on('click', 'select[id^="category"]', function (e) {
    $categoryID = $(this).attr('id');
    $selectedCategoryID = $(this).children(":selected").attr("id");

    // console.log('Select ID: ' + $categoryID);
    // console.log('Selected category id: ' + $selectedCategoryID);

    if ($categoryID == 'category') {
        $('#standard-item').empty();
        if ($selectedCategoryID == 'all') {
            standardItems.forEach(function (standardItem) {
                $('#standard-item').append("<option id=" + standardItem['id'] + " value=" + standardItem['id'] + ">" + standardItem['name'] + "</option>");
            });
        } else {
            standardItems.forEach(function (standardItem) {
                if (standardItem['category_id'] == $selectedCategoryID) {
                    $('#standard-item').append("<option id=" + standardItem['id'] + " value=" + standardItem['id'] + ">" + standardItem['name'] + "</option>");
                }
            });
        }
    } else {
        $categoryIdIndex = parseInt($categoryID.match(/\d+/g), 10);
        // console.log($categoryIdIndex);
        $('#standard-item' + $categoryIdIndex).empty();
        if ($selectedCategoryID == 'all') {
            standardItems.forEach(function (standardItem) {
                $('#standard-item' + $categoryIdIndex).append("<option id=" + standardItem['id'] + " value=" + standardItem['id'] + ">" + standardItem['name'] + "</option>");
            });
        } else {
            standardItems.forEach(function (standardItem) {
                if (standardItem['category_id'] == $selectedCategoryID) {
                    $('#standard-item' + $categoryIdIndex).append("<option id=" + standardItem['id'] + " value=" + standardItem['id'] + ">" + standardItem['name'] + "</option>");
                }
            });
        }
    }

    $parentID = $(this).parent().attr('id');
    // console.log('parent id: ' + $parentID);
    $parentIdIndex = parseInt($parentID.match(/\d+/g), 10);
    // console.log('parent id: ' + $parentIdIndex);

    toggleStandardItemsValues($parentIdIndex);

});

$(document).on('click', 'select[id^="standard-item"]', function (e) {
    $selectedCategoryID = $(this).children(":selected").attr("id");
    // console.log($selectedCategoryID);

    $parentID = $(this).parent().attr('id');
    // console.log('parent id: ' + $parentID);
    $parentIdIndex = parseInt($parentID.match(/\d+/g), 10);
    // console.log('parent id: ' + $parentIdIndex);

    toggleStandardItemsValues($parentIdIndex);

});

function toggleStandardItemsValues($parentIdIndex) {
    $isFirst = isNaN($parentIdIndex);
    if ($isFirst) {
        $selectedStandardItemID = $('#standard-item :selected').attr('id');
    } else {
        $selectedStandardItemID = $('#standard-item' + $parentIdIndex + ' :selected').attr('id');
    }
    // console.log($selectedStandardItemID);
    $imaValueInterval = false;
    standardItemsNullDimension.forEach(function (i) {
        if (i['id'] == $selectedStandardItemID) {
            $imaValueInterval = true;
        }
    })
    if ($imaValueInterval) {
        // console.log("ima interval na vrednosti");
        if ($isFirst) {
            $('#value-from').show();
            $('#value-to').show();
        } else {
            $('#value-from' + $parentIdIndex).show();
            $('#value-to' + $parentIdIndex).show();
        }
    }
    else {
        // console.log("NEma interval na vrednosti");
        if ($isFirst) {
            // console.log("---------" + $parentIdIndex);
            $('#value-from').hide();
            $('#value-to').hide();
        } else {
            $('#value-from' + $parentIdIndex).hide();
            $('#value-to' + $parentIdIndex).hide();
        }
    }
}

// Add more categories
$cloneCounter = 1;
$(".addCategory").on('click', function (e) {

    e.preventDefault();

    var clone = $('div[id^="mainStandardItem"]:last')
        .clone(false);

    // change all id values to a new unique value by adding number X to the end
    // where X is a number that increases last div number at the end
    $("*", clone).add(clone).each(function () {
        if (~this.id.indexOf("mainStandardItem")) {
            this.id = "mainStandardItem" + $cloneCounter;
        }
        if (~this.id.indexOf("category")) {
            this.id = "category" + $cloneCounter;
        }
        if (~this.id.indexOf("standard-item")) {
            this.id = "standard-item" + $cloneCounter;
        }
        if (~this.id.indexOf("description")) {
            this.id = "description" + $cloneCounter;
        }
        if (~this.id.indexOf("value-from")) {
            this.id = "value-from" + $cloneCounter;
        }
        if (~this.id.indexOf("value-to")) {
            this.id = "value-to" + $cloneCounter;
        }
    });
    if (clone.find('button').length == 0) {
        $("<button class='removeCategory btn btn-danger'>Remove category</button>").appendTo(clone);
    }
    // console.log(clone);
    $(clone).insertAfter('div[id^="mainStandardItem"]:last');
    $('#category' + $cloneCounter).empty();
    $('#standard-item' + $cloneCounter).empty();
    // Auto fill dropdowns
    $('#category' + $cloneCounter).append("<option id='all' value='all'>All categories</option>");
    categories.forEach(function (category) {
        // console.log(category['name']);
        $('#category' + $cloneCounter).append("<option id=" + category['id'] + " value=" + category['id'] + " >" + category['name'] + "</option>");
    });
    standardItems.forEach(function (standardItem) {
        $('#standard-item' + $cloneCounter).append("<option id=" + standardItem['id'] + " value=" + standardItem['id'] + " >" + standardItem['name'] + "</option>");
    });
    $('#value-from' + $cloneCounter).hide();
    $('#value-to' + $cloneCounter).hide();
    $cloneCounter++;
});

$(document).on('click', '.removeCategory', function (e) {
    e.preventDefault();
    $divId = $(this).closest("div").prop("id");
    $("#" + $divId).remove();
});