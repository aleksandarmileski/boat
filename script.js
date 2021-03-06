$(function () {
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
    // console.log($name + " " + typeof $email + " " + typeof $phone + " " + typeof $options + " " + $notes + " " + brokers_id + " " + boat_id);

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
            // console.log("Success");
            $("#submitInfo").after("<div class='a  alert-success'>  Your data has been successfully sent. </div>");
            $("#name").val('');
            $("#email").val('');
            $("#phone").val('');
            $("#notes").val('');
        },
        error: function (xhr, desc, err) {
            console.log("error");
        }
    });
    e.preventDefault();
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
            $('#dimensionValues').show();
        } else {
            $('#dimensionValues' + $parentIdIndex).show();
        }
    }
    else {
        // console.log("NEma interval na vrednosti");
        if ($isFirst) {
            // console.log("---------" + $parentIdIndex);
            $('#dimensionValues').hide();

        } else {
            $('#dimensionValues' + $parentIdIndex).hide();
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
        if (~this.id.indexOf("dimensionValues")) {
            this.id = "dimensionValues" + $cloneCounter;
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
    $('#dimensionValues' + $cloneCounter).hide();
    $cloneCounter++;
});

$(document).on('click', '.removeCategory', function (e) {
    e.preventDefault();
    $divId = $(this).closest("div").prop("id");
    $("#" + $divId).remove();
});

$(document).on('click', '#search', function (e) {
    sessionStorage.clear();
});

$(document).on('keyup mouseup', '.inlineNumberInput', function () {
    // console.log($(this).attr('id'));
    $currentValue=$(this).val();
    if ($currentValue!=''){
        $prefix = $(this).attr('id').substr(0, 3);
        // console.log($prefix);
        $siName = $(this).attr('id').substr(3);
        // console.log($siName);

        if($prefix=='min'){
            $('#max'+$siName).attr({"min" : $currentValue});
        }
        if($prefix=='max'){
            $('#min'+$siName).attr({"max" : $currentValue});
        }
        if($prefix=='pri'){
            if($(this).attr('id')=="price-from"){
                $('#price-to').attr({"min" : $currentValue});
            }
            if($(this).attr('id')=="price-to"){
                $('#price-from').attr({"max" : $currentValue});
            }
        }
    }
});
