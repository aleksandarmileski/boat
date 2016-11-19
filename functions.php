<?php
include "db.php";

$photoUrl = "http://46.101.221.106/images/";
// Return random boats
function getRandomBoats()
{
//    $photoUrl = "http://" . $_SERVER['HTTP_HOST'] . "/images/";

    $conn = connection();

    $queryStr = "
        SELECT boats.id as id, 
            boats.title as title, 
            types.type as type,
            photos.location_filename AS photo_url,
            prices.value as price,
            builders.name AS builder,
            boats.year as year,
            boat_locations.country as country
        FROM boats
            LEFT JOIN boat_types ON boats.id=boat_types.boat_id
            LEFT JOIN types ON boat_types.type_id=types.id
            INNER JOIN photos ON boats.id=photos.boat_id
            INNER JOIN prices ON boats.id=prices.boat_id
            INNER JOIN builders ON boats.builders_id=builders.id
            INNER JOIN boat_locations ON boats.id=boat_locations.boat_id
        WHERE prices.current = 1 AND photos.primaryPhoto = 1
        LIMIT 100";

//    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
//        $id = $row['id'];
//        $title = $row['title'];
//        $type = $row['type'];
//        $photo = $row['photo_url'];
//        $price = $row['price'];
//        $builder = $row['builder'];
//        $country = $row['country'];
//        $year = $row['year'];
//
//        echo "<a href='http://" . $_SERVER['HTTP_HOST'] . "/boat/boat.php?id=" . $id . "'>";
//        echo "<div class='res grow' id='$id'>";
//        echo "<img src='http://46.101.221.106/images/" . $photo . "' class='img-responsive img-fix grow col-md-4' >";
//        echo "<h3 id='title' class='col-md-8'>$title</h3>";
//        echo "<span class='col-md-6'>Type: {$type}, <br /> Price {$price} &euro;, <br /> Builder: {$builder}, <br /> Currently lying: {$country}</span>";
//        echo "</div>";
//        echo "</a>";
//        echo "<hr />";
//    }

    $query = $conn->prepare($queryStr);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    $conn = null;

    return $result;

}

function findBoats()
{
    $conn = connection();

    $querySelectStr = " ";
    $queryFromStr = " ";
    $queryWhereStr = " ";

    $querySelectStr = $querySelectStr . "
        SELECT boats.id as id, 
            boats.title as title, 
            types.type as type,
            photos.location_filename AS photo_url,
            prices.value as price,
            builders.name AS builder,
            boats.year as year,
            boat_locations.country as country";

    $queryFromStr = $queryFromStr . " 
        FROM boats        
            LEFT JOIN boat_types ON boats.id=boat_types.boat_id
            LEFT JOIN types ON boat_types.type_id=types.id           
            INNER JOIN photos ON boats.id=photos.boat_id       
            INNER JOIN prices ON boats.id=prices.boat_id        
            INNER JOIN builders ON boats.builders_id=builders.id        
            INNER JOIN boat_locations ON boats.id=boat_locations.boat_id ";

    $queryWhereStr = $queryWhereStr . " WHERE prices.current=1 AND photos.`primaryPhoto`=1 ";

    // Filter by TYPE
    if (isset($_POST['boat-type']) || isset($_SESSION['boat-type'])) {
        $boatType = isset($_POST['boat-type']) ? $_POST['boat-type'] : $_SESSION['boat-type'];
        if ($boatType != 'all') {
//        echo "|" . $_POST['boat-type'] . "|";
            $queryWhereStr = $queryWhereStr . " AND types.id=" . $boatType . " ";
        }
    }

//    $firstCategory = isset($_POST['category']) ? $_POST['category'][0] : $_SESSION['category'][0];
    $firstStandardItem = isset($_POST['standard-item']) ? $_POST['standard-item'][0] : $_SESSION['standard-item'][0];
    $firstDescription = isset($_POST['description']) ? $_POST['description'][0] : $_SESSION['description'][0];
    $brStandardItems = isset($_POST['standard-item']) ? count($_POST['standard-item']) : count($_SESSION['standard-item']);
    // Filter by BOAT standard item
    if (isset($_POST['standard-item']) || isset($_SESSION['standard-item'])) {
        if ($brStandardItems > 0) {
            $nullDimensions = getStandardItemsNullDimensions();

            if (($firstStandardItem != 1)
                || (trim($firstDescription) != '')
                || ($brStandardItems > 1)
            ) {
                // -Q-
                $queryWhereStr = $queryWhereStr . " AND `sequalize`.`boats`.`id` IN ( ";
                $queryWhereStr = $queryWhereStr . " SELECT `sequalize`.`boat_standard_items`.`boat_id`";
                $queryWhereStr = $queryWhereStr . " FROM ( ";
                for ($i = 0; $i < $brStandardItems; $i++) {
//                    $categoryID = isset($_POST['category']) ? $_POST['category'][$i] : $_SESSION['category'][$i];
//            echo "Category id: |" . $categoryID . "|     ";
                    $standardItemID = isset($_POST['standard-item']) ? $_POST['standard-item'][$i] : $_SESSION['standard-item'][$i];;
//            echo "Standard item id: |" . $standardItemID . "|     ";
                    $standardItemDescription = trim(isset($_POST['description']) ? $_POST['description'][$i] : $_SESSION['description'][$i]);
//            echo "Standard item description: |" . $standardItemDescription . "|     ";
                    $standardItemFromValue = isset($_POST['value-from']) ? $_POST['value-from'][$i] : $_SESSION['value-from'][$i];;
//            echo "Standard item values from: " . $standardItemFromValue . "     ";
                    $standardItemToValue = isset($_POST['value-to']) ? $_POST['value-to'][$i] : $_SESSION['value-to'][$i];;
//            echo "Standard item values to: " . $standardItemToValue . "     ";

                    // -Q-
                    $queryWhereStr = $queryWhereStr . " SELECT `sequalize`.`boat_standard_items`.`boat_id` ";
                    $queryWhereStr = $queryWhereStr . " FROM `sequalize`.`boat_standard_items` ";
                    $queryWhereStr = $queryWhereStr . " WHERE `sequalize`.`boat_standard_items`.`standard_item_id`=" . $standardItemID . " ";

                    // -Q-
                    if ($standardItemDescription != '') {
                        $queryWhereStr = $queryWhereStr . " AND `sequalize`.`boat_standard_items`.`description` LIKE '%" . $standardItemDescription . "%' ";
                    }
                    $hasDimension = false;

                    // !!!!!!!! Added " " at the and of the string
                    $standardItemID = trim($standardItemID);
                    foreach ($nullDimensions as $dim) {
//                echo "||".gettype($standardItemID)."==".gettype($dim['id']);
//                echo $standardItemID."==".$dim['id']."||";
                        if ($dim['id'] == $standardItemID) $hasDimension = true;
                    }

                    if ($hasDimension) {
                        // -Q-
                        $queryWhereStr = $queryWhereStr . " AND `sequalize`.`boat_standard_items`.`value` >=" . $standardItemFromValue . " ";
                        $queryWhereStr = $queryWhereStr . " AND `sequalize`.`boat_standard_items`.`value` <=" . $standardItemToValue . " ";
                    }

                    if (($i + 1) < $brStandardItems) $queryWhereStr = $queryWhereStr . " UNION ALL ";

                }
                // -Q-
                $queryWhereStr = $queryWhereStr . "     ) boat_standard_items ";
                $queryWhereStr = $queryWhereStr . " GROUP BY `sequalize`.`boat_standard_items`.`boat_id` ";
                $queryWhereStr = $queryWhereStr . " HAVING COUNT(*)>" . ($brStandardItems - 1) . " ";
                $queryWhereStr = $queryWhereStr . " ) ";

//        echo "|" . $_POST['size-from'] . " - " . $_POST['size-to']. "|";
            }
        }
    }

    // Filter by PRICE
    if ((isset($_POST['price-from']) || isset($_POST['price-to']))
        || (isset($_SESSION['price-from']) || isset($_SESSION['price-to']))
    ) {
        $priceFrom = isset($_POST['price-from']) ? $_POST['price-from'] : $_SESSION['price-from'];
        $priceTo = isset($_POST['price-to']) ? $_POST['price-to'] : $_SESSION['price-to'];
        if ($priceFrom != '' || $priceTo != '') {
            if ($priceFrom != '') {
                $queryWhereStr = $queryWhereStr . " AND prices.`value` >=" . $priceFrom . " ";
            }
            if ($priceTo != '') {
                $queryWhereStr = $queryWhereStr . " AND prices.`value` <=" . $priceTo . " ";
            }
//        echo "|" .$_POST['price-from'] . " - " . $_POST['price-to']. "|";
        }
    }

    // Filter by BUILDER
    if (isset($_POST['boat-builder']) || isset($_SESSION['boat-builder'])) {
        $boatBuilder = isset($_POST['boat-builder']) ? $_POST['boat-builder'] : $_SESSION['boat-builder'];
        if ($boatBuilder != 'all') {
//        echo "|" .$boatBuilder. "|";
            $queryWhereStr = $queryWhereStr . " AND builders.`name`='" . $boatBuilder . "'";
        }
    }

    // Filter by COUNTRY
    if (isset($_POST['boat-country']) || isset($_SESSION['boat-country'])) {
        $boatCountry = isset($_POST['boat-country']) ? $_POST['boat-country'] : $_SESSION['boat-country'];
        if ($boatCountry != 'all') {
//        echo "|" .$boatCountry. "|";
            $queryWhereStr = $queryWhereStr . " AND boat_locations.`country`='" . $boatCountry . "'";
        }
    }

    // Filter by built YEAR
    if (isset($_POST['boat-year']) || isset($_SESSION['boat-year'])) {
        $boatYear = isset($_POST['boat-year']) ? $_POST['boat-year'] : $_SESSION['boat-year'];
        if ($boatYear != 'all') {
//        echo "|" .$boatYear. "|";
            $queryWhereStr = $queryWhereStr . " AND boats.`year`>='" . $boatYear . "'";
        }
    }

    // Filter by KEYWORD
    $boatKeyword = isset($_POST['boat-keyword']) ? $_POST['boat-keyword'] : $_SESSION['boat-keyword'];
    if (isset($_POST['boat-keyword']) || isset($_SESSION['boat-keyword'])) {
        if ($boatKeyword != '') {
//        echo "|" .$boatKeyword. "|";
            $queryWhereStr = $queryWhereStr . " AND `sequalize`.`boat_standard_items`.`description` LIKE '%" . $boatKeyword . " ";
        }
    }

    if (($firstStandardItem != 1)
        || (trim($firstDescription) != '')
        || ($brStandardItems > 1)
        || (trim($boatKeyword != ''))
    ) {
        $querySelectStr = $querySelectStr . ",
                boat_standard_items.value as boat_size,
                boat_standard_items.description as description";
        $queryFromStr = $queryFromStr . " LEFT JOIN boat_standard_items ON boats.id=boat_standard_items.boat_id ";
    }

    $queryWhereStr = $queryWhereStr . " GROUP BY boats.id LIMIT 100";

    $queryStr = $querySelectStr . $queryFromStr . $queryWhereStr;

    echo "------ Query -----";
    echo $queryStr;

//    $result = $conn->query($queryStr);
//    $brBoats = 0;
//    $photoUrl = "http://46.101.221.106/images/";
//    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
//        $brBoats++;
//        $id = $row['id'];
//        $title = $row['title'];
//        $type = $row['type'];
//        $photo = $row['photo_url'];
//        $price = $row['price'];
//        $builder = $row['builder'];
//        $year = $row['year'];
//        $country = $row['country'];
////        $description = $row['description'];
////        $boat_size = $row['boat_size'];
//
//        echo "<a href='http://" . $_SERVER['HTTP_HOST'] . "/boat/boat.php?id=" . $row['id'] . "'>";
//        echo "<div class='res' id='$id'>";
//        echo "<div class='col-md-4'><img src='" . $photoUrl . "" . $photo . "' class='image-rounded' height=\"100\" ></div>";
//        echo "<h3 id='title' class='col-md-8'>Title: $title</h3>
//            <h4 class='col-md-8'> Type: $type,
//            Price: $price &euro;,
//            Builder: $builder,
//            Country: $country,
//            Boat year: $year,</h4>";
////        echo "<h4 class='col-md-8'>
////            Boat size: $boat_size ft,
////            Description: $description</h4>";
//        echo "</div>";
//        echo "</a>";
//
//    }
//    echo "Number of search results: " . $brBoats;

    $query = $conn->prepare($queryStr);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    $conn = null;

    return $result;

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

    $query = $conn->prepare("SELECT id,name,category_id FROM standard_items WHERE category_id IS NOT NULL");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;

    return $result;
}

// Get boat standard item by category
function getBoatStandardItem($categoryId)
{
    $conn = connection();
    $categoryId == 'all'
        ? $queryStr = "SELECT id,name,dimensions FROM standard_items WHERE category_id IS NULL"
        : $queryStr = "SELECT id,name,dimensions FROM standard_items WHERE category_id=" . $categoryId;
    $query = $conn->prepare($queryStr);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;

    return $result;
}

// Get Boat type informations
function getStandardItemsNullDimensions()
{
    $conn = connection();

    $query = $conn->prepare("SELECT `sequalize`.`standard_items`.`id` 
                            FROM `sequalize`.`standard_items` 
                            WHERE `sequalize`.`standard_items`.`dimensions` IS NOT NULL");
    $query->execute();
//    $result = $query->fetchAll(PDO::FETCH_ASSOC);
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

// Get Boat broker informations
function getBrokerInfo($brokerID)
{
    $conn = connection();

    $query = $conn->prepare("SELECT `brokers`.`contact` AS broker, `brokers`.`p_country` AS broker_country, `brokers`.`phone` AS broker_phone  
      FROM brokers 
      WHERE brokers.`id`=" . $brokerID);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;

    return $result;
}

// Get Boat office informations
function getOfficeInfo($officeID)
{
    $conn = connection();

    $query = $conn->prepare("SELECT offices.`office_name` AS office, offices.`phone` AS office_phone, `offices`.`p_country` AS office_country 
        FROM offices 
        WHERE offices.`id`=" . $officeID);
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