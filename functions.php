<?php
include "db.php";

$photoUrl = "http://46.101.221.106/images/";
// Return random boats
function getRandomBoats()
{
//    $photoUrl = "http://" . $_SERVER['HTTP_HOST'] . "/images/";

    $conn = connection();

    $queryStr = "
        SELECT `sequalize`.`boats`.`id` AS id, 
            `sequalize`.`boats`.`title` AS title, 
            `sequalize`.`types`.`type` AS type,
            `sequalize`.`photos`.`location_filename` AS photo_url,
            `sequalize`.`prices`.`value` AS price,
            `sequalize`.`builders`.`name` AS builder,
            `sequalize`.`boats`.`year` AS year,
            `sequalize`.`boat_locations`.`country` AS country
        FROM boats
            LEFT JOIN `sequalize`.`boat_types` ON `sequalize`.`boats`.`id`=`sequalize`.`boat_types`.`boat_id`
            LEFT JOIN `sequalize`.`types` ON `sequalize`.`boat_types`.`type_id`=`sequalize`.`types`.`id`
            INNER JOIN `sequalize`.`photos` ON `sequalize`.`boats`.`id`=`sequalize`.`photos`.`boat_id`
            INNER JOIN `sequalize`.`prices` ON `sequalize`.`boats`.`id`=`sequalize`.`prices`.`boat_id`
            INNER JOIN `sequalize`.`builders` ON `sequalize`.`boats`.`builders_id`=`sequalize`.`builders`.`id`
            INNER JOIN `sequalize`.`boat_locations` ON `sequalize`.`boats`.`id`=`sequalize`.`boat_locations`.`boat_id`
        WHERE `sequalize`.`prices`.`current` = 1 AND `sequalize`.`photos`.`primaryPhoto` = 1
        LIMIT 100";

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
        SELECT `sequalize`.`boats`.`id` AS id, 
            `sequalize`.`boats`.`title` AS title, 
            `sequalize`.`types`.`type` AS type,
            `sequalize`.`photos`.`location_filename` AS photo_url,
            `sequalize`.`prices`.`value` AS price,
            `sequalize`.`builders`.`name` AS builder,
            `sequalize`.`boats`.`year` AS year,
            `sequalize`.`boat_locations`.`country` AS country";

    $queryFromStr = $queryFromStr . " 
        FROM boats        
            LEFT JOIN `sequalize`.`boat_types` ON `sequalize`.`boats`.`id`=`sequalize`.`boat_types`.`boat_id`
            LEFT JOIN `sequalize`.`types` ON `sequalize`.`boat_types`.`type_id`=`sequalize`.`types`.`id`
            INNER JOIN `sequalize`.`photos` ON `sequalize`.`boats`.`id`=`sequalize`.`photos`.`boat_id`
            INNER JOIN `sequalize`.`prices` ON `sequalize`.`boats`.`id`=`sequalize`.`prices`.`boat_id`
            INNER JOIN `sequalize`.`builders` ON `sequalize`.`boats`.`builders_id`=`sequalize`.`builders`.`id`
            INNER JOIN `sequalize`.`boat_locations` ON `sequalize`.`boats`.`id`=`sequalize`.`boat_locations`.`boat_id` ";

    $queryWhereStr = $queryWhereStr . " WHERE `sequalize`.`prices`.`current` = 1 AND `sequalize`.`photos`.`primaryPhoto` = 1 ";

    // Filter by TYPE
    if (isset($_POST['boat-type']) || isset($_SESSION['boat-type'])) {
        $boatType = isset($_POST['boat-type']) ? $_POST['boat-type'] : $_SESSION['boat-type'];
        if ($boatType != 'all') {
//        echo "|" . $_POST['boat-type'] . "|";
            $queryWhereStr = $queryWhereStr . " AND `sequalize`.`types`.`id`=" . $boatType . " ";
        }
    }


    $hasShortcode = false;
    $GLOBALS['hasShortcode'] = false;
    $GLOBALS['queryShortcode'] = "";
    $GLOBALS['numShortcode'] = 0;
    function addShorcodeQueryStr($relationalOperator, $itemId, $itemValue)
    {
        if ($GLOBALS['numShortcode'] > 0) $GLOBALS['queryShortcode'] = $GLOBALS['queryShortcode'] . " UNION ALL ";
        $GLOBALS['queryShortcode'] = $GLOBALS['queryShortcode'] . " SELECT `sequalize`.`boat_standard_items`.`boat_id` FROM `sequalize`.`boat_standard_items` WHERE `sequalize`.`boat_standard_items`.`standard_item_id`=" . $itemId . " ";
        $GLOBALS['queryShortcode'] = $GLOBALS['queryShortcode'] . " AND `sequalize`.`boat_standard_items`.`value`" . $relationalOperator . " " . $itemValue;
        $GLOBALS['numShortcode']++;
        $GLOBALS['hasShortcode'] = true;
    }

    // Filter by shortcode standard-item
    // MIN Length siID 68
    if (isset($_POST['minLength']) || isset($_SESSION['minLength'])) {
        $boatMinLength = isset($_POST['minLength']) ? $_POST['minLength'] : $_SESSION['minLength'];
        if ($boatMinLength != '') {
//        echo "|" . $_POST['minLength'] . "|";
            addShorcodeQueryStr(">=", 68, $boatMinLength);
        }
    }


    // MAX Length siID 68
    if (isset($_POST['maxLength']) || isset($_SESSION['maxLength'])) {
        $boatMaxLength = isset($_POST['maxLength']) ? $_POST['maxLength'] : $_SESSION['maxLength'];
        if ($boatMaxLength != '') {
//        echo "|" . $_POST['maxLength'] . "|";
            addShorcodeQueryStr("<=", 68, $boatMaxLength);
        }
    }


    // MIN Head room siID 136
    if (isset($_POST['minHeadRoom']) || isset($_SESSION['minHeadRoom'])) {
        $boatMinHeadRoom = isset($_POST['minHeadRoom']) ? $_POST['minHeadRoom'] : $_SESSION['minHeadRoom'];
        if ($boatMinHeadRoom != '') {
//        echo "|" . $_POST['minHeadRoom'] . "|";
            addShorcodeQueryStr(">=", 136, $boatMinHeadRoom);
        }
    }
    // MAX Head room siID 136
    if (isset($_POST['maxHeadRoom']) || isset($_SESSION['maxHeadRoom'])) {
        $boatMaxHeadRoom = isset($_POST['maxHeadRoom']) ? $_POST['maxHeadRoom'] : $_SESSION['maxHeadRoom'];
        if ($boatMaxHeadRoom != '') {
//        echo "|" . $_POST['maxLength'] . "|";
            addShorcodeQueryStr("<=", 136, $boatMaxHeadRoom);
        }
    }

    // MIN Voltage siID 238
    if (isset($_POST['minVoltage']) || isset($_SESSION['minVoltage'])) {
        $boatMinVoltage = isset($_POST['minVoltage']) ? $_POST['minVoltage'] : $_SESSION['minVoltage'];
        if ($boatMinVoltage != '') {
//        echo "|" . $_POST['minVoltage'] . "|";
            addShorcodeQueryStr(">=", 238, $boatMinVoltage);
        }
    }
    // MAX Voltage siID 238
    if (isset($_POST['maxVoltage']) || isset($_SESSION['maxVoltage'])) {
        $boatMaxVoltage = isset($_POST['maxVoltage']) ? $_POST['maxVoltage'] : $_SESSION['maxVoltage'];
        if ($boatMaxVoltage != '') {
//        echo "|" . $_POST['maxLength'] . "|";
            addShorcodeQueryStr("<=", 238, $boatMaxVoltage);
        }
    }

    // MIN Motor siID 310
    if (isset($_POST['minMotor']) || isset($_SESSION['minMotor'])) {
        $boatMinMotor = isset($_POST['minMotor']) ? $_POST['minMotor'] : $_SESSION['minMotor'];
        if ($boatMinMotor != '') {
//        echo "|" . $_POST['minMotor'] . "|";
            addShorcodeQueryStr(">=", 310, $boatMinMotor);
        }
    }
    // MAX Motor siID 310
    if (isset($_POST['maxMotor']) || isset($_SESSION['maxMotor'])) {
        $boatMaxMotor = isset($_POST['maxMotor']) ? $_POST['maxMotor'] : $_SESSION['maxMotor'];
        if ($boatMaxMotor != '') {
//        echo "|" . $_POST['maxLength'] . "|";
            addShorcodeQueryStr("<=", 310, $boatMaxMotor);
        }
    }

    // MIN Kw siID 355
    if (isset($_POST['minkW']) || isset($_SESSION['minkW'])) {
        $boatMinKw = isset($_POST['minkW']) ? $_POST['minkW'] : $_SESSION['minkW'];
        if ($boatMinKw != '') {
//        echo "|" . $_POST['minMotor'] . "|";
            addShorcodeQueryStr(">=", 355, $boatMinKw);
        }
    }
    // MAX Kw siID 355
    if (isset($_POST['maxkW']) || isset($_SESSION['maxkW'])) {
        $boatMaxKw = isset($_POST['maxkW']) ? $_POST['maxkW'] : $_SESSION['maxkW'];
        if ($boatMaxKw != '') {
//        echo "|" . $_POST['maxLength'] . "|";
            addShorcodeQueryStr("<=", 355, $boatMaxKw);

        }
    }

    // MIN POWER HP siID 356
    if (isset($_POST['minHP']) || isset($_SESSION['minHP'])) {
        $boatMinHP = isset($_POST['minHP']) ? $_POST['minHP'] : $_SESSION['minHP'];
        if ($boatMinHP != '') {
//        echo "|" . $_POST['minMotor'] . "|";
            addShorcodeQueryStr(">=", 356, $boatMinHP);
        }
    }
    // MAX Kw siID 356
    if (isset($_POST['maxHP']) || isset($_SESSION['maxHP'])) {
        $boatMaxHP = isset($_POST['maxHP']) ? $_POST['maxHP'] : $_SESSION['maxHP'];
        if ($boatMaxHP != '') {
//        echo "|" . $_POST['maxLength'] . "|";
            addShorcodeQueryStr("<=", 356, $boatMaxHP);
        }
    }

    // MIN Stabilizers siID 347
    if (isset($_POST['minStabilizers']) || isset($_SESSION['minStabilizers'])) {
        $boatMinStabilizers = isset($_POST['minStabilizers']) ? $_POST['minStabilizers'] : $_SESSION['minStabilizers'];
        if ($boatMinStabilizers != '') {
//        echo "|" . $_POST['minMotor'] . "|";
            addShorcodeQueryStr(">=", 347, $boatMinStabilizers);
        }
    }
    // MAX Stabilizers siID 347
    if (isset($_POST['maxStabilizers']) || isset($_SESSION['maxStabilizers'])) {
        $boatMaxStabilizers = isset($_POST['maxStabilizers']) ? $_POST['maxStabilizers'] : $_SESSION['maxStabilizers'];
        if ($boatMaxStabilizers != '') {
//        echo "|" . $_POST['maxLength'] . "|";
            addShorcodeQueryStr("<=", 347, $boatMaxStabilizers);
        }
    }

    // MIN Barometer siID 360
    if (isset($_POST['minBarometer']) || isset($_SESSION['minBarometer'])) {
        $boatMinBarometer = isset($_POST['minBarometer']) ? $_POST['minBarometer'] : $_SESSION['minBarometer'];
        if ($boatMinBarometer != '') {
//        echo "|" . $_POST['minMotor'] . "|";
            addShorcodeQueryStr(">=", 360, $boatMinBarometer);
        }
    }
    // MAX Barometer siID 360
    if (isset($_POST['maxBarometer']) || isset($_SESSION['maxBarometer'])) {
        $boatMaxBarometer = isset($_POST['maxBarometer']) ? $_POST['maxBarometer'] : $_SESSION['maxBarometer'];
        if ($boatMaxBarometer != '') {
//        echo "|" . $_POST['maxLength'] . "|";
            addShorcodeQueryStr("<=", 360, $boatMaxBarometer);
        }
    }

    // MIN Sleeping places siID 59
    if (isset($_POST['minSleepingPlaces']) || isset($_SESSION['minSleepingPlaces'])) {
        $boatMinSleepingPlaces = isset($_POST['minSleepingPlaces']) ? $_POST['minSleepingPlaces'] : $_SESSION['minSleepingPlaces'];
        if ($boatMinSleepingPlaces != '') {
//        echo "|" . $_POST['minMotor'] . "|";
            addShorcodeQueryStr(">=", 59, $boatMinSleepingPlaces);
        }
    }
    // MAX Sleeping places siID 59
    if (isset($_POST['maxSleepingPlaces']) || isset($_SESSION['maxSleepingPlaces'])) {
        $boatMaxSleepingPlaces = isset($_POST['maxSleepingPlaces']) ? $_POST['maxSleepingPlaces'] : $_SESSION['maxSleepingPlaces'];
        if ($boatMaxSleepingPlaces != '') {
//        echo "|" . $_POST['maxLength'] . "|";
            addShorcodeQueryStr("<=", 59, $boatMaxSleepingPlaces);
        }
    }

    // MIN Cabin places siID 442
    if (isset($_POST['minCabin']) || isset($_SESSION['minCabin'])) {
        $boatMinCabin = isset($_POST['minCabin']) ? $_POST['minCabin'] : $_SESSION['minCabin'];
        if ($boatMinCabin != '') {
//        echo "|" . $_POST['minMotor'] . "|";
            addShorcodeQueryStr(">=", 442, $boatMinCabin);
        }
    }
    // MAX Cabin places siID 442
    if (isset($_POST['maxCabin']) || isset($_SESSION['maxCabin'])) {
        $boatMaxCabin = isset($_POST['maxCabin']) ? $_POST['maxCabin'] : $_SESSION['maxCabin'];
        if ($boatMaxCabin != '') {
//        echo "|" . $_POST['maxLength'] . "|";
            addShorcodeQueryStr("<=", 442, $boatMaxCabin);
        }
    }

//    echo " ++++++++ Shortcode number: " . $GLOBALS['numShortcode'];
//    echo " Shortcode query: " . $GLOBALS['queryShortcode'] . "++++++++++";

    $GLOBALS['queryStandardItem'] = "";
    $GLOBALS['numStandardItem'] = 0;

    $firstCategory = isset($_POST['category']) ? $_POST['category'][0] : $_SESSION['category'][0];
    $firstStandardItem = isset($_POST['standard-item']) ? $_POST['standard-item'][0] : $_SESSION['standard-item'][0];
    $firstDescription = isset($_POST['description']) ? $_POST['description'][0] : $_SESSION['description'][0];
    $brStandardItems = isset($_POST['standard-item']) ? count($_POST['standard-item']) : count($_SESSION['standard-item']);
    // Filter by BOAT standard item
    if (isset($_POST['standard-item']) || isset($_SESSION['standard-item'])) {
        if ($brStandardItems > 0) {
            $nullDimensions = getStandardItemsNullDimensions();

            if (($firstCategory != 'all')
                || ($firstStandardItem != 1)
                || (trim($firstDescription) != '')
                || ($brStandardItems > 1)
            ) {
                // -Q-
//                $queryWhereStr = $queryWhereStr . " AND `sequalize`.`boats`.`id` IN ( ";
//                $queryWhereStr = $queryWhereStr . " SELECT `sequalize`.`boat_standard_items`.`boat_id`";
//                $queryWhereStr = $queryWhereStr . " FROM ( ";
                for ($i = 0; $i < $brStandardItems; $i++) {
                    $categoryID = isset($_POST['category']) ? $_POST['category'][$i] : $_SESSION['category'][$i];
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
//                    $queryWhereStr = $queryWhereStr . " SELECT `sequalize`.`boat_standard_items`.`boat_id` ";
//                    $queryWhereStr = $queryWhereStr . " FROM `sequalize`.`boat_standard_items` ";
//                    $queryWhereStr = $queryWhereStr . " WHERE `sequalize`.`boat_standard_items`.`standard_item_id`=" . $standardItemID . " ";

                    $tempStanadardItemQuery = "";
                    $tempStanadardItemQuery = $tempStanadardItemQuery . " SELECT `sequalize`.`boat_standard_items`.`boat_id` ";
                    $tempStanadardItemQuery = $tempStanadardItemQuery . " FROM `sequalize`.`boat_standard_items` ";
                    $tempStanadardItemQuery = $tempStanadardItemQuery . " WHERE `sequalize`.`boat_standard_items`.`standard_item_id`=" . $standardItemID . " ";

                    // -Q-
                    if ($standardItemDescription != '') {
//                        $queryWhereStr = $queryWhereStr . " AND `sequalize`.`boat_standard_items`.`description` LIKE '%" . $standardItemDescription . "%' ";
                        $tempStanadardItemQuery = $tempStanadardItemQuery . " AND `sequalize`.`boat_standard_items`.`description` LIKE '%" . $standardItemDescription . "%' ";
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
//                        $queryWhereStr = $queryWhereStr . " AND `sequalize`.`boat_standard_items`.`value` >=" . $standardItemFromValue . " ";
//                        $queryWhereStr = $queryWhereStr . " AND `sequalize`.`boat_standard_items`.`value` <=" . $standardItemToValue . " ";

                        $tempStanadardItemQuery = $tempStanadardItemQuery . " AND `sequalize`.`boat_standard_items`.`value` >=" . $standardItemFromValue . " ";
                        $tempStanadardItemQuery = $tempStanadardItemQuery . " AND `sequalize`.`boat_standard_items`.`value` <=" . $standardItemToValue . " ";
                    }

                    if (($i + 1) < $brStandardItems) {
//                        $queryWhereStr = $queryWhereStr . " UNION ALL ";
                        $tempStanadardItemQuery = $tempStanadardItemQuery . " UNION ALL ";
                    }

                    $GLOBALS['queryStandardItem'] = $GLOBALS['queryStandardItem'] . $tempStanadardItemQuery;
                    $GLOBALS['numStandardItem']++;

                }
                // -Q-
//                $queryWhereStr = $queryWhereStr . "     ) boat_standard_items ";
//                $queryWhereStr = $queryWhereStr . " GROUP BY `sequalize`.`boat_standard_items`.`boat_id` ";
//                $queryWhereStr = $queryWhereStr . " HAVING COUNT(*)>" . ($brStandardItems - 1) . " ";
//                $queryWhereStr = $queryWhereStr . " ) ";

//        echo "|" . $_POST['size-from'] . " - " . $_POST['size-to']. "|";
            }
        }
    }

//    echo " ++++++++ Standard Item number: " . $GLOBALS['numStandardItem'];
//    echo " Standard Item query: " . $GLOBALS['queryStandardItem'] . "++++++++++";
    $numStandardItems = ($GLOBALS['numShortcode'] + $GLOBALS['numStandardItem']);
    if (($GLOBALS['numStandardItem'] > 0)
        || ($GLOBALS['numShortcode'] > 0)
    ) {
//        echo "######## OVERALL STANDARD ITEMS NUMBER: " . ($GLOBALS['numShortcode'] + $GLOBALS['numStandardItem']) . " ###############";
        $selectStandardItem = "";
        if (($GLOBALS['numStandardItem'] > 0) && ($GLOBALS['numShortcode'] > 0)) {
            $selectStandardItem = $GLOBALS['queryShortcode'] . " UNION ALL " . $GLOBALS['queryStandardItem'];
        } else {
            if ($GLOBALS['numStandardItem'] > 0) $selectStandardItem = $GLOBALS['queryStandardItem'];
            if ($GLOBALS['numShortcode'] > 0) $selectStandardItem = $GLOBALS['queryShortcode'];
        }
//        echo "######## OVERALL STANDARD ITEMS QUERY: " . $selectStandardItem . " ###############";

        $queryWhereStr = $queryWhereStr . " AND `sequalize`.`boats`.`id` IN ( ";
        $queryWhereStr = $queryWhereStr . " SELECT `sequalize`.`boat_standard_items`.`boat_id`";
        $queryWhereStr = $queryWhereStr . " FROM ( ";

        $queryWhereStr = $queryWhereStr . $selectStandardItem;

        $queryWhereStr = $queryWhereStr . "     ) boat_standard_items ";
        $queryWhereStr = $queryWhereStr . " GROUP BY `sequalize`.`boat_standard_items`.`boat_id` ";
        $queryWhereStr = $queryWhereStr . " HAVING COUNT(*)>" . ($numStandardItems - 1) . " ";
        $queryWhereStr = $queryWhereStr . " ) ";
    }

    // Filter by PRICE
    if ((isset($_POST['price-from']) || isset($_POST['price-to']))
        || (isset($_SESSION['price-from']) || isset($_SESSION['price-to']))
    ) {
        $priceFrom = isset($_POST['price-from']) ? $_POST['price-from'] : $_SESSION['price-from'];
        $priceTo = isset($_POST['price-to']) ? $_POST['price-to'] : $_SESSION['price-to'];
        if ($priceFrom != '' || $priceTo != '') {
            if ($priceFrom != '') {
                $queryWhereStr = $queryWhereStr . " AND `sequalize`.`prices`.`value` >=" . $priceFrom . " ";
            }
            if ($priceTo != '') {
                $queryWhereStr = $queryWhereStr . " AND `sequalize`.`prices`.`value` <=" . $priceTo . " ";
            }
//        echo "|" .$_POST['price-from'] . " - " . $_POST['price-to']. "|";
        }
    }

    // Filter by BUILDER
    if (isset($_POST['boat-builder']) || isset($_SESSION['boat-builder'])) {
        $boatBuilder = isset($_POST['boat-builder']) ? $_POST['boat-builder'] : $_SESSION['boat-builder'];
        if ($boatBuilder != 'all') {
//        echo "|" .$boatBuilder. "|";
            $queryWhereStr = $queryWhereStr . " AND `sequalize`.`builders`.`name`='" . $boatBuilder . "'";
        }
    }

    // Filter by COUNTRY
    if (isset($_POST['boat-country']) || isset($_SESSION['boat-country'])) {
        $boatCountry = isset($_POST['boat-country']) ? $_POST['boat-country'] : $_SESSION['boat-country'];
        if ($boatCountry != 'all') {
//        echo "|" .$boatCountry. "|";
            $queryWhereStr = $queryWhereStr . " AND `sequalize`.`boat_locations`.`country`='" . $boatCountry . "'";
        }
    }

    // Filter by built YEAR
    if (isset($_POST['boat-year']) || isset($_SESSION['boat-year'])) {
        $boatYear = isset($_POST['boat-year']) ? $_POST['boat-year'] : $_SESSION['boat-year'];
        if ($boatYear != 'all') {
//        echo "|" .$boatYear. "|";
            $queryWhereStr = $queryWhereStr . " AND `sequalize`.`boats`.`year`>='" . $boatYear . "'";
        }
    }

    // Filter by KEYWORD
    $boatKeyword = isset($_POST['boat-keyword']) ? $_POST['boat-keyword'] : $_SESSION['boat-keyword'];
    if (isset($_POST['boat-keyword']) || isset($_SESSION['boat-keyword'])) {
        if (trim($boatKeyword) != '') {
//        echo "|" .$boatKeyword. "|";
            $queryWhereStr = $queryWhereStr . " AND `sequalize`.`boat_standard_items`.`description` LIKE '%" . $boatKeyword . "%' ";
        }
    }

    if (($firstCategory != 'all')
        || ($firstStandardItem != 1)
        || (trim($firstDescription) != '')
        || ($brStandardItems > 1)
        || (trim($boatKeyword != ''))
        || ($numStandardItems > 0)
    ) {
        $querySelectStr = $querySelectStr . ",
                `sequalize`.`boat_standard_items`.`value` as boat_size,
                `sequalize`.`boat_standard_items`.`description` as description";
        $queryFromStr = $queryFromStr . " LEFT JOIN `sequalize`.`boat_standard_items` ON `sequalize`.`boats`.`id`=`sequalize`.`boat_standard_items`.`boat_id` ";
    }

    $queryWhereStr = $queryWhereStr . " GROUP BY `sequalize`.`boats`.`id` LIMIT 100";

    $queryStr = $querySelectStr . $queryFromStr . $queryWhereStr;

//    echo "---------QUERY-----------";
//    echo $queryStr;

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