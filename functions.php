<?php 
include "db.php";

// var_dump($_SERVER) ;

// Return random boats
function getRandomBoats()
{

//    $photoUrl = "http://46.101.221.106/images/";
    $photoUrl = "http://".$_SERVER['HTTP_HOST']."/images/";

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

        echo "<a href='http://".$_SERVER['HTTP_HOST']."/boat/boat.php?id=".$id."'>";
        echo "<div class='res' id='$id'>";
        echo "<img src='http://46.101.221.106/images/" . $photo . "' class='image-rounded col-md-4' >";
        echo "<h3 id='title' class='col-md-8'>$title</h3>";
        echo "<p class='col-md-6'>Type: {$type}, Price {$price} &euro;, Builder: {$builder}, Currently lying: {$country}</p>";
        echo "</div>";
        echo "</a>";
    }
    $conn = null;
}

function getBoatPhotos($id){
	$conn = connection();
	    $result = $conn->query("select photos.location_filename from photos where photos.boat_id=".$id);
        echo "<div class='w3-content w3-display-container slideImages col-md-6'>";

	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

        $photo = $row['location_filename'];
        echo "<img class='mySlides' src='http://46.101.221.106/images/" . $photo . "'  class='' style='width:100%' height='400px'>";
    }
 		echo "
		<a class='w3-btn-floating w3-display-left' onclick='plusDivs(-1)'>&#10094;</a>
		<a class='w3-btn-floating w3-display-right' onclick='plusDivs(1)'>&#10095;</a>";
 		echo "</div>";

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

// Return boats
function searchBoats()
{
//    $photoUrl = "http://46.101.221.106/images/";
    $photoUrl = "http://".$_SERVER['HTTP_HOST']."/images/";
    $conn = connection();

    $queryStr = "
        SELECT boats.id as id, boats.title as title, 

            types.type as type, 
            
            photos.location_filename as photo_url, 
            
            prices.value as price, 
            
            builders.name as builder, 
            
            boat_locations.country as country,
            
            boat_standard_items.value as boat_size,            
            boat_standard_items.description as description,
            
            boats.year as year

        FROM boats

        INNER JOIN boat_types ON boats.id=boat_types.boat_id
        INNER JOIN types ON boat_types.type_id=types.id

        INNER JOIN photos ON boats.id=photos.boat_id
        
        INNER JOIN prices ON boats.id=prices.boat_id
        
        LEFT JOIN builders ON boats.builders_id=builders.id
        
        INNER JOIN boat_locations ON boats.id=boat_locations.boat_id
        
        LEFT JOIN boat_standard_items ON boats.id=boat_standard_items.boat_id

        WHERE photos.primaryPhoto=1  AND prices.current=1
        
        ";

    $querySelect="";
    $queryFrom="";
    $queryWhere="";

    // Filter by TYPE
    if ($_POST['boat-type'] != 'all') {
        echo "|".$_POST['boat-type']."|";
        $queryStr = $queryStr . " AND types.id=" . $_POST['boat-type'];
    }

    // Filter by BOAT SIZE
    if ($_POST['size-from'] != '' && $_POST['size-to'] != '') {
        $queryStr = $queryStr . " AND boat_standard_items.`value` BETWEEN " . $_POST['size-from'] . " AND " . $_POST['size-to'] . "";
    }

    // Filter by PRICE
    if ($_POST['price-from'] != '' && $_POST['price-to'] != '') {
        $queryStr = $queryStr . " AND prices.`value` BETWEEN " . $_POST['price-from'] . " AND " . $_POST['price-to'] . "";
    }

    // Filter by BUILDER
    if ($_POST['boat-builder'] != 'all') {
        $queryStr = $queryStr . " AND builders.`name`='" . $_POST['boat-builder'] . "'";
    }

    // Filter by COUNTRY
    if ($_POST['boat-country'] != 'all') {
        $queryStr = $queryStr . " AND boat_locations.`country`='" . $_POST['boat-country'] . "'";
    }

    // Filter by built YEAR
    if ($_POST['boat-year'] != 'all') {
        $queryStr = $queryStr . " AND boats.`year`>='" . $_POST['boat-year'] . "'";
    }

    // Filter by KEYWORD
    if ($_POST['boat-keyword'] != '') {
        $queryStr = $queryStr . " AND boat_standard_items.`description` LIKE '%" . $_POST['boat-keyword'] . "%'";
    }

    $queryStr=$queryStr." GROUP BY boats.title";

    $result = $conn->query($queryStr);

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

        $id = $row['id'];
        $title = $row['title'];
        $type = $row['type'];
        $price = $row['price'];
        $photo = $row['photo_url'];
        $builder = $row['builder'];
        $country = $row['country'];
        $boat_year = $row['year'];
        $boat_size = $row['boat_size'];
        $description = $row['description'];


        echo "<a href='http://".$_SERVER['HTTP_HOST']."/boat/boat.php?id=".$row['id']."'>";
        echo "<div class='res' id='$id'>";
        echo "<div class='col-md-4'><img src='" . $photoUrl . "" . $photo . "' class='image-rounded' height=\"100\" ></div>";
        echo "<h3 id='title' class='col-md-8'>Title: $title</h3>
            <h4 class='col-md-8'> Type: $type, 
            Price: $price &euro;, 
            Builder: $builder, 
            Country: $country, 
            Boat year: $boat_year,
            Boat size: $boat_size ft, 
            Description: $description</h4>";
        echo "</div>";
        echo "</a>";
    }
    $conn = null;
}

// Check if boat exists
function displayBoatInfo($boatID)
{
    $conn = connection();
    $queryStr = "
        SELECT boats.id as id, boats.title as title, 
            
            photos.location_filename as photo_url, 
            
            prices.value as price, 
            
            builders.name as builder, 
            
            boat_locations.country as country,
            
            boat_standard_items.value as boat_size,            
            boat_standard_items.description as description,
            
            boats.year as year

        FROM boats

        INNER JOIN photos ON boats.id=photos.boat_id
        
        INNER JOIN prices ON boats.id=prices.boat_id
        
        LEFT JOIN builders ON boats.builders_id=builders.id
        
        INNER JOIN boat_locations ON boats.id=boat_locations.boat_id
        
        INNER JOIN boat_standard_items ON boats.id=boat_standard_items.boat_id

        WHERE boats.id=".$boatID." AND photos.primaryPhoto=1 AND prices.current = 1";

    foreach ($conn->query($queryStr) as $row) {
        if ($row['id'] == $boatID) {
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
