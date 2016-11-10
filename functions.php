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
        echo "<div class='res grow' id='$id'>";
        echo "<img src='http://46.101.221.106/images/" . $photo . "' class='img-responsive img-fix grow col-md-4' >";
        echo "<h3 id='title' class='col-md-8'>$title</h3>";
        echo "<span class='col-md-6'>Type: {$type}, <br /> Price {$price} &euro;, <br /> Builder: {$builder}, <br /> Currently lying: {$country}</span>";
        echo "</div>";
        echo "</a>";
        echo "<hr />";
    }
    $conn = null;
}

function findBoats()
{
    $conn = connection();

    $queryStr = "SELECT boats.id as id, 
              boats.title as title, 
              types.type as type,
              photos.location_filename AS photo_url,
              prices.value as price,
              builders.name AS builder,
              boats.year as year,
              boat_locations.country as country,
              boat_standard_items.value as boat_size,
              boat_standard_items.description as description
     FROM boats        
        LEFT JOIN boat_types ON boats.id=boat_types.boat_id
        LEFT JOIN types ON boat_types.type_id=types.id           
        LEFT JOIN photos ON boats.id=photos.boat_id       
        INNER JOIN prices ON boats.id=prices.boat_id        
        INNER JOIN builders ON boats.builders_id=builders.id        
        INNER JOIN boat_locations ON boats.id=boat_locations.boat_id 
        LEFT JOIN boat_standard_items ON boats.id=boat_standard_items.boat_id
     WHERE prices.current=1 
        ";


    // Filter by TYPE
    if ($_POST['boat-type'] != 'all') {
//        echo "|" . $_POST['boat-type'] . "|";
        $queryStr = $queryStr . " AND types.id=" . $_POST['boat-type'] . " ";
    }

    // Filter by BOAT standard item
    if (count($_POST['standard-item']) > 0) {

        $queryStr = $queryStr . " AND boat_standard_items.`boat_id` IN (";
        $brStandardItems = count($_POST['standard-item']);
        for ($i = 0; $i < $brStandardItems; $i++) {
            echo "------";
            echo "Category id: " . $_POST['category'][$i] . "     ";
            $standardItemID = $_POST['standard-item'][$i] . "     ";
            echo "Standard item id: " . $_POST['standard-item'][$i] . "     ";
            $standardItemFromValue = $_POST['standard-item-value-from'][$i];
            echo "Standard item values from: " . $_POST['standard-item-value-from'][$i] . "     ";
            $standardItemToValue = $_POST['standard-item-value-to'][$i];
            echo "Standard item values to: " . $_POST['standard-item-value-to'][$i] . "     ";

            $queryStr = $queryStr . " SELECT boat_standard_items.`boat_id` FROM boat_standard_items ";
            $queryStr = $queryStr . " WHERE boat_standard_items.`value` BETWEEN " . $standardItemFromValue . " AND " . $standardItemToValue;
            $queryStr = $queryStr . " AND boat_standard_items.`standard_item_id`=" . $standardItemID;

            $i < $brStandardItems - 1
                ? $queryStr = $queryStr . " UNION "
                : $queryStr = $queryStr;

        }
        $queryStr = $queryStr . " ) ";

//        echo "|" . $_POST['size-from'] . " - " . $_POST['size-to']. "|";
    }

    // Filter by PRICE
    if ($_POST['price-from'] != '' || $_POST['price-to'] != '') {
        if ($_POST['price-from'] != '') {
            $queryStr = $queryStr . " AND prices.`value` >=" . $_POST['price-from'] . " ";
        }
        if ($_POST['price-to'] != '') {
            $queryStr = $queryStr . " AND prices.`value` <=" . $_POST['price-to'] . " ";
        }
//        echo "|" .$_POST['price-from'] . " - " . $_POST['price-to']. "|";
    }

    // Filter by BUILDER
    if ($_POST['boat-builder'] != 'all') {
//        echo "|" .$_POST['boat-builder']. "|";
        $queryStr = $queryStr . " AND builders.`name`='" . $_POST['boat-builder'] . "'";
    }

    // Filter by COUNTRY
    if ($_POST['boat-country'] != 'all') {
//        echo "|" .$_POST['boat-country']. "|";
        $queryStr = $queryStr . " AND boat_locations.`country`='" . $_POST['boat-country'] . "'";
    }

    // Filter by built YEAR
    if ($_POST['boat-year'] != 'all') {
//        echo "|" .$_POST['boat-year']. "|";
        $queryStr = $queryStr . " AND boats.`year`>='" . $_POST['boat-year'] . "'";
    }

    // Filter by KEYWORD
    if ($_POST['boat-keyword'] != '') {
//        echo "|" .$_POST['boat-keyword']. "|";
        $queryStr = $queryStr . " AND boats.`title` LIKE '%" . $_POST['boat-keyword'] . "%' OR boats.title LIKE '%" . $_POST['boat-keyword'] . "%'";
    }

    $queryStr = $queryStr . " GROUP BY boats.id LIMIT 100";
    echo $queryStr;
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
        $description = $row['description'];
        $boat_size = $row['boat_size'];

        echo "<a href='http://" . $_SERVER['HTTP_HOST'] . "/boat/boat.php?id=" . $row['id'] . "'>";
        echo "<div class='res' id='$id'>";
        echo "<div class='col-md-4'><img src='" . $photoUrl . "" . $photo . "' class='image-rounded' height=\"100\" ></div>";
        echo "<h3 id='title' class='col-md-8'>Title: $title</h3>
            <h4 class='col-md-8'> Type: $type, 
            Price: $price &euro;, 
            Builder: $builder, 
            Country: $country, 
            Boat year: $year,</h4>";
        echo "<h4 class='col-md-8'>
            Boat size: $boat_size ft,
            Description: $description</h4>";
        echo "</div>";
        echo "</a>";

    }
    echo "Number of search results: " . $brBoats;
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

// Get boat country
function getBoatCountry($id = null)
{
    $conn = connection();
    $id == null ?
        $queryStr = "SELECT DISTINCT country FROM boat_locations" :
        $queryStr = "SELECT country,name,address FROM boat_locations WHERE boat_id=" . $id;
    $query = $conn->prepare($queryStr);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;

    return $result;
}

// Get boat category
function getBoatCategories()
{
    $conn = connection();
    $queryStr = "SELECT id,name FROM categories";
    $query = $conn->prepare($queryStr);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;

    return $result;
}

// Get Boat type informations
function getStandardItems()
{
    $conn = connection();

    $query = $conn->prepare("SELECT id,name,category_id FROM standard_items");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;

    return $result;
}

// Get boat standard item
function getBoatStandardItem($categoryId)
{
    $conn = connection();
    $queryStr = "SELECT id,name FROM standard_items WHERE category_id=" . $categoryId;
    $query = $conn->prepare($queryStr);
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

// Get Boat type informations
function getBoatStandardItemsCategories()
{
    $conn = connection();
    $arr = array();
    foreach ($conn->query("SELECT id,name FROM categories") as $row) {
        $arr[$row['id']] = $row['name'];
    }
    $conn = null;
    return $arr;
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

//// Get boat standard items details
//function getBoatStandardItems($boatInfoObject)
//{
//    $boatStandarItemsObject = get_object_vars($boatInfoObject)['standardItems'];
//    $boatStandardItems = array();
//    foreach ($boatStandarItemsObject as $bsd) {
//        foreach ($bsd as $intoBsd) {
//            array_push(
//                $boatStandardItems,
//                array('name' => get_object_vars(get_object_vars($intoBsd)['standard_item'])['name'],
//                    'value' => get_object_vars($intoBsd)['value'],
//                    'description' => get_object_vars($intoBsd)['description']));
//        }
//    }
//    return $boatStandardItems;
//}

// Get boat standard items details by category
function getBoatStandardItems($boatInfoObject)
{
    $boatStandardItemsObject = get_object_vars($boatInfoObject)['standardItems'];
    $boatStandardItemsFullData = array();
    foreach ($boatStandardItemsObject as $boatCategoryId => $boatCategoryObjects) {
        $boatCategoryObjects = get_object_vars($boatCategoryObjects);
        $boatStandardItems = array();
        foreach ($boatCategoryObjects as $boatStandardItemID => $boatStandardItemData) {
            $boatStandardItemValue = get_object_vars($boatStandardItemData)['value'];
            $boatStandardItemName = get_object_vars(get_object_vars($boatStandardItemData)['standard_item'])['name'];
            $boatStandardItems[$boatStandardItemID] = array("name" => $boatStandardItemName, "value" => $boatStandardItemValue);
        }
        $boatStandardItemsFullData[$boatCategoryId] = $boatStandardItems;
    }
    return $boatStandardItemsFullData;
}

function getBoatDataObject($id)
{
    $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6ImRlbmtvbWFuY2Vza2kxMjNAZ21haWwuY29tIiwiaWQiOjE4NywiaWF0IjoxNDc4MDEyNDMxfQ.snQ9PvwVTrsJlNIfi69ZP5flsZe3lntaPCsszAakU9U';
    $ip = $_SERVER['REMOTE_ADDR'];
    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'http://46.101.221.106/api/boat/' . $id . '?token=' . $token . '&ip=' . $ip,
        CURLOPT_USERAGENT => 'Sample cURL Boat Request'
    ));
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);
    //    echo gettype(json_decode($resp));

    return (object)json_decode($resp);
}

function getInfo($postParam, $boat_id, $brokers_id)
{
    $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6ImRlbmtvbWFuY2Vza2kxMjNAZ21haWwuY29tIiwiaWQiOjE4NywiaWF0IjoxNDc4MDEyNDMxfQ.snQ9PvwVTrsJlNIfi69ZP5flsZe3lntaPCsszAakU9U';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://46.101.221.106/api/inquiry');
    curl_setopt($ch, CURLOPT_POST, 1);
    $vars = json_encode(array(
        "token" => $token,
        "boat_id" => $boat_id,
        "broker_id" => $brokers_id,
        "name" => $postParam['name'],
        "email" => $postParam['email'],
        "contactNumber" => $postParam['phone'],
//        "contactNumber" => $postParam['phone'],
        "preferredMethod" => $postParam['options'],
//        "preferredMethod" => $postParam['options'],
        "notes" => $postParam['notes']
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);  //Post Fields
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    $server_output = curl_exec($ch);
    curl_close($ch);

//    var_dump($server_output);
}
