<?php
include "db.php";

// Return random boats
function getRandomBoats()
{

    $photoUrl = "http://46.101.221.106/images/";
//    $photoUrl = "http://" . $_SERVER['HTTP_HOST'] . "/images/";

    $conn = connection();

    $result = $conn->query("SELECT  boats.id as id, boats.title as title, 

            types.type as type, 
            
            photos.location_filename as photo_url, 
            
            prices.value as price, 
            
            builders.name as builder, 
            
            boat_locations.country as country,
            
            boats.year as year

        FROM boats

        LEFT JOIN boat_types ON boats.id=boat_types.boat_id
        LEFT JOIN types ON boat_types.type_id=types.id

        LEFT JOIN photos ON boats.id=photos.boat_id
        
        LEFT JOIN prices ON boats.id=prices.boat_id
        
        LEFT JOIN builders ON boats.builders_id=builders.id
        
        LEFT JOIN boat_locations ON boats.id=boat_locations.boat_id


        WHERE prices.current = 1 AND photos.primaryPhoto = 1
        LIMIT 50 OFFSET 178");

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['id'];
        $title = $row['title'];
        $type = $row['type'];
        $photo = $row['photo_url'];
        $price = $row['price'];
        $builder = $row['builder'];
        $country = $row['country'];
        $year = $row['year'];

        echo "<a href='http://" . $_SERVER['HTTP_HOST'] . "/boat/boat.php?id=" . $id . "'>";
        echo "<div class='res' id='$id'>";
        echo "<img src='http://46.101.221.106/images/" . $photo . "' class='image-rounded col-md-4' >";
        echo "<h3 id='title' class='col-md-8'>$title</h3>";
        echo "<p class='col-md-6'>Type: {$type}, Price {$price} &euro;, Builder: {$builder}, Currently lying: {$country}</p>";
        echo "</div>";
        echo "</a>";
    }
    $conn = null;
}

function findBoats()
{
    $conn = connection();

    $querySelect = "SELECT boats.id as id, 
              boats.title as title, 
              types.type as type,
              photos.location_filename AS photo_url,
              prices.value as price,
              builders.name AS builder,
              boats.year as year,
              boat_locations.country as country";
    $queryFrom = " FROM boats        
        INNER JOIN boat_types ON boats.id=boat_types.boat_id
        INNER JOIN types ON boat_types.type_id=types.id           
        LEFT JOIN photos ON boats.id=photos.boat_id       
        INNER JOIN prices ON boats.id=prices.boat_id        
        INNER JOIN builders ON boats.builders_id=builders.id        
        INNER JOIN boat_locations ON boats.id=boat_locations.boat_id ";
    $queryWhere = " WHERE prices.current=1 ";

    // Filter by TYPE
    if ($_POST['boat-type'] != 'all') {
        echo "|" . $_POST['boat-type'] . "|";
        $queryWhere = $queryWhere . " AND types.id=" . $_POST['boat-type'] . " ";
    }

    // Filter by BOAT SIZE
    if ($_POST['size-from'] != '' && $_POST['size-to'] != '') {
        if ($_POST['size-from'] != '') {
            $queryWhere = $queryWhere . " AND boat_standard_items.`value` >=" . $_POST['size-from'] . " ";
        }
        if ($_POST['size-to'] != '') {
            $queryWhere = $queryWhere . " AND boat_standard_items.`value` <=" . $_POST['size-to'] . " ";
        }
        echo $_POST['size-from'] . " - " . $_POST['size-to'];
    }

    // Filter by PRICE
    if ($_POST['price-from'] != '' || $_POST['price-to'] != '') {
        if ($_POST['price-from'] != '') {
            $queryWhere = $queryWhere . " AND prices.`value` >=" . $_POST['price-from'] . " ";
        }
        if ($_POST['price-to'] != '') {
            $queryWhere = $queryWhere . " AND prices.`value` <=" . $_POST['price-to'] . " ";
        }
        echo $_POST['price-from'] . " - " . $_POST['price-to'];
    }

    // Filter by BUILDER
    if ($_POST['boat-builder'] != 'all') {
        echo $_POST['boat-builder'];
        $queryWhere = $queryWhere . " AND builders.`name`='" . $_POST['boat-builder'] . "'";
    }

    // Filter by COUNTRY
    if ($_POST['boat-country'] != 'all') {
        echo $_POST['boat-country'];
        $queryWhere = $queryWhere . " AND boat_locations.`country`='" . $_POST['boat-country'] . "'";
    }

    // Filter by built YEAR
    if ($_POST['boat-year'] != 'all') {
        echo $_POST['boat-year'];
        $queryWhere = $queryWhere . " AND boats.`year`>='" . $_POST['boat-year'] . "'";
    }

    // Filter by KEYWORD
    if ($_POST['boat-keyword'] != '') {
        echo $_POST['boat-keyword'];
        $queryWhere = $queryWhere . " AND boat_standard_items.`description` LIKE '%" . $_POST['boat-keyword'] . "%'";
    }

    $isSet = false;
    if (($_POST['boat-keyword'] != '') || ($_POST['size-from'] != '') || ($_POST['size-to'] != '')) {
        $querySelect = ",
              boat_standard_items.value as boat_size,            
              boat_standard_items.description as description ";
        $queryFrom = " LEFT JOIN boat_standard_items ON boats.id=boat_standard_items.boat_id ";
        $isSet = true;
    }


    $queryStr = $querySelect . $queryFrom . $queryWhere;

    $queryStr = $queryStr . " GROUP BY boats.id LIMIT 100";

    $result = $conn->query($queryStr);
    $brBoats = 0;
    $photoUrl = "http://46.101.221.106/images/";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $brBoats++;
        $id = $row['id'];
        $title = $row['title'];
        $type = $row['type'];
        $photo = $row['photo_url'];
        $price = $row['price'];
        $builder = $row['builder'];
        $year = $row['year'];
        $country = $row['country'];

        if ($isSet) {
            $description = $row['description'];
            $boat_size = $row['boat_size'];
        }

        echo "<a href='http://" . $_SERVER['HTTP_HOST'] . "/boat/boat.php?id=" . $row['id'] . "'>";
        echo "<div class='res' id='$id'>";
        echo "<div class='col-md-4'><img src='" . $photoUrl . "" . $photo . "' class='image-rounded' height=\"100\" ></div>";
        echo "<h3 id='title' class='col-md-8'>Title: $title</h3>
            <h4 class='col-md-8'> Type: $type, 
            Price: $price &euro;, 
            Builder: $builder, 
            Country: $country, 
            Boat year: $year,</h4>";
        if ($isSet) {
            echo "<h4 class='col-md-8'>            
            Boat size: $boat_size ft, 
            Description: $description</h4>";
        }
        echo "</div>";
        echo "</a>";

    }
    echo "Br. boats: " + $brBoats;
    $conn = null;

}

// Check if boat exists
function checkBoatExist($checkID)
{
    $conn = connection();
    foreach ($conn->query("SELECT * FROM boats") as $row) {
        if ($row['id'] == $checkID) {
            $conn = null;
            return $row;
        }
    }
    $conn = null;
    return false;
}

// Get Boat type informations
function getBoatTypes()
{
    $conn = connection();

    $query = $conn->prepare("SELECT id,type FROM types");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;

    return $result;
}

// Get Boat type informations
function getBoatBuilders()
{
    $conn = connection();

    $query = $conn->prepare("SELECT name FROM builders");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;

    return $result;
}

// Get all boat laying countries
function getBoatCountry()
{
    $conn = connection();

    $query = $conn->prepare("SELECT DISTINCT country FROM boat_locations");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;

    return $result;
}

// Get all boat years
function getBoatYears()
{
    $conn = connection();

    $query = $conn->prepare("SELECT DISTINCT year FROM boats ORDER BY year DESC");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;

    return $result;
}

// Get boat main details
function getBoatMainDetails($boatInfoObject)
{
    $boatMainDetails = get_object_vars($boatInfoObject);
    return array(
        'id' => $boatMainDetails['id'],
        'title' => $boatMainDetails['title'],
        'clients_id' => $boatMainDetails['clients_id'],
        'brokers_id' => $boatMainDetails['brokers_id'],
        'offices_id' => $boatMainDetails['offices_id'],
        'referer_id' => $boatMainDetails['referer_id'],
        'boat_name' => $boatMainDetails['boat_name'],
        'boat_model' => $boatMainDetails['boat_model'],
        'year' => $boatMainDetails['year'],
        'notes' => $boatMainDetails['notes'],
        'notes_nl' => $boatMainDetails['notes_nl'],
        'builders_id' => $boatMainDetails['builders_id']
    );
}

// Get boat types
function getBoatType($boatInfoObject)
{
    $boatMainDetails = get_object_vars($boatInfoObject)['types'];
    $boatTypes = array();
    foreach ($boatMainDetails as $typeDetails) {
        array_push(
            $boatTypes,
            array(
                'id' => get_object_vars($typeDetails)['id'],
                'type' => get_object_vars($typeDetails)['type'])
        );
    }
    return $boatTypes;
}

// Get boat Builder
function getBoatBuilder($boatInfoObject)
{
    $boatMainDetails = get_object_vars($boatInfoObject);
    return get_object_vars($boatMainDetails['builder'])['name'];
}

// Get boat Status
function getBoatStatus($boatInfoObject)
{
    $boatMainDetails = get_object_vars($boatInfoObject);
    return array(
        'id' => get_object_vars($boatMainDetails['status'])['id'],
        'description' => get_object_vars($boatMainDetails['status'])['description']
    );
}

// Get boat Photo urls
function getBoatPhotos($boatInfoObject)
{
    $boatMainPhotoDetails = get_object_vars($boatInfoObject)['photos'];
    $boatPhotoUrls = array();
    foreach ($boatMainPhotoDetails as $photoDetails) {
        array_push($boatPhotoUrls, get_object_vars($photoDetails)['location_filename']);
    }
    return $boatPhotoUrls;
}

// Get boat Price
function getBoatPrice($boatInfoObject)
{
    $boatMainDetails = get_object_vars($boatInfoObject);
    return array(
        'value' => get_object_vars($boatMainDetails['price'])['value'],
        'currency' => get_object_vars($boatMainDetails['price'])['currency']
    );
}

// Get boat Latitude
function getBoatLatitude($boatInfoObject)
{
    return get_object_vars($boatInfoObject)['latitude'];
}

// Get boat Longitude
function getBoatLongitude($boatInfoObject)
{
    return get_object_vars($boatInfoObject)['longitude'];
}

// Get boat Address
function getBoatAddress($boatInfoObject)
{
    return get_object_vars($boatInfoObject)['address'];
}

// Get boat Primary Photo
function getBoatPrimaryPhoto($boatInfoObject)
{
    $boatMainDetails = get_object_vars($boatInfoObject);
    return get_object_vars($boatMainDetails['photo'])['location_filename'];
}

// Get boat standard items details
function getBoatStandardItems($boatInfoObject)
{
    $boatStandarItemsObject = get_object_vars($boatInfoObject)['standardItems'];
    $boatStandardItems = array();
    foreach ($boatStandarItemsObject as $bsd) {
        foreach ($bsd as $intoBsd) {
            array_push(
                $boatStandardItems,
                array('name' => get_object_vars(get_object_vars($intoBsd)['standard_item'])['name'],
                    'value' => get_object_vars($intoBsd)['value'],
                    'description' => get_object_vars($intoBsd)['description']));
        }
    }
    return $boatStandardItems;
}

function getBoatDataObject($id)
{
    $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6ImRlbmtvbWFuY2Vza2kxMjNAZ21haWwuY29tIiwiaWQiOjE4NywiaWF0IjoxNDc4MDEyNDMxfQ.snQ9PvwVTrsJlNIfi69ZP5flsZe3lntaPCsszAakU9U';
    $ip=$_SERVER['REMOTE_ADDR'];
    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'http://46.101.221.106/api/boat/' . $id . '?token=' . $token.'&ip='.$ip,
        CURLOPT_USERAGENT => 'Sample cURL Boat Request'
    ));
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);
    //    echo gettype(json_decode($resp));

    return (object)json_decode($resp);
}
