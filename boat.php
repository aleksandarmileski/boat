<?php
require "functions.php";

if (isset($_GET['id'])) {
//    echo "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    $id = htmlspecialchars($_GET['id']);

    if (checkBoatExist($id)) {

        $boatInfoObject = getBoatDataObject($id);
//        print_r(gettype($boatInfoObject));

        if (isset($boatInfoObject)) {
            $boatMainDetails = getBoatMainDetails($boatInfoObject);
            $boatStandarsItems = getBoatStandardItems($boatInfoObject);
            $boatStandarsItemsCategory = getBoatStandardItemsCategories();
            $boatTypes = getBoatType($boatInfoObject);
            $boatBuilder = getBoatBuilder($boatInfoObject);
            $boatStatus = getBoatStatus($boatInfoObject);
            $boatPhotos = getBoatPhotos($boatInfoObject);
            $boatPrice = getBoatPrice($boatInfoObject);
            $boatCountry = getBoatCountry($id);
            $boatLatitude = getBoatLatitude($boatInfoObject);
            $boatLongitude = getBoatLongitude($boatInfoObject);
            $boatAddress = getBoatAddress($boatInfoObject);
            $boatPrimaryPhoto = getBoatPrimaryPhoto($boatInfoObject);


//            echo '<pre>';
//            print_r($boatStandarsItems);
//            print_r($boatStandarsItemsCategory);
//            print_r($boatPrimaryPhoto);
//            print_r($boatLongitude);
//            print_r($boatInfoObject);
//            echo '</pre>';
        } else {
            echo "There is no data availdable for the chosen boat at the moment.";
        }
    } else {
        header("Location: http://" . $_SERVER["HTTP_HOST"] . "/boat/");
    }
} else {
    header("Location: http://" . $_SERVER["HTTP_HOST"] . "/boat/");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>
<body>

<div class='loginNav col-md-12'></div>
<a href='index.php'>
    <div class='topNav col-md-12'>
        <img src='Goliath.png'/>
    </div>
</a>

<div class='container col-md-12'>

    <!--    Top main div-->
    <div class="col-md-12">
        <!--    Main info div-->
        <div class='boatContent'>
            <h3 id='title' class='col-md-10'>
                <?= $boatMainDetails['title']; ?>
            </h3>
            <hr>
            <h3 id='title' class='col-md-10'>
                Price <?= $boatPrice['value'] . " " . $boatPrice['currency'] ?>
            </h3>
            <hr>
            <h4 class='col-md-10'>
                Built <?= $boatMainDetails['year']; ?>
            </h4>
            <h4 class='col-md-10'>
                Builder: <?= $boatBuilder; ?>
            </h4>
            <h4 class='col-md-10'> Country: <?= $boatCountry[0]['country']; ?></h4>
            <h4 class='col-md-10'> Description: <?= $boatStatus['description']; ?></h4>
        </div>

        <!--    Picture div   -->
        <div class='w3-content w3-display-container slideImages col-md-6'>
            <?php foreach ($boatPhotos as $url): ?>
                <img class='mySlides' src="<?= 'http://46.101.221.106/images/' . $url; ?>">
            <?php endforeach; ?>
            <a class='w3-btn-floating w3-display-left' onclick='plusDivs(-1)'>&#10094;</a>
            <a class='w3-btn-floating w3-display-right' onclick='plusDivs(1)'>&#10095;</a>
        </div>
    </div>

    <!--    Standard Items div-->
    <div class="col-md-12">
        <hr>
        <h3 class="text-center col-md-12">Boat Standard Items</h3>
        <?php if(count($boatStandarsItems)==0) : ?>
            <p class="text-center col-md-12"> --- There is no data about Standard Items --- </p>
        <?php endif; ?>
        <?php foreach ($boatStandarsItems as $boatStandarsItemsCatgeoryID => $boatStandarsItemsDetails): ?>
            <div class="col-md-4">
                <h3 class="text-center col-md-12"><?php if ($boatStandarsItemsCatgeoryID != 'null') {
                        echo ($boatStandarsItemsCategory[$boatStandarsItemsCatgeoryID]);
                    } else {
                        echo 'No specified category';
                    }; ?></h3>
                <?php foreach ($boatStandarsItemsDetails as $boatStandarsItems): ?>
                    <p class="col-md-12"><?= $boatStandarsItems['name']; ?>: <?= $boatStandarsItems['value']; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!--    Contact div-->
    <div class="col-md-12">
        <div class='dimension col-md-12'>
            <hr>
            <h3 class='col-md-12'>Find out more</h3>
            <p class='col-md-10'>Interested in this boat? Find out more or arrange a viewing by completing this
                form.</p>
            <p class='col-md-10'>We'll get back to you with more information and take you thorough the options for
                viewing and buying this boat.</p>
            <p class='col-md-10'>Alternatively, don't forget that you can call us on +44 800 037 1329 anytime for a
                chat</p>
            <hr>
        </div>
        <div class='col-md-12'>
            <form id='getContactInfo' action=''>
                <div class='form-group col-md-12'>
                    <label for='name'>Your name:</label>
                    <input class='form-control' id='name' type='text' required>
                </div>
                <div class='form-group col-md-12'>
                    <label for='email'>Your email address:</label>
                    <input class='form-control' id='email' type='text' required>
                </div>
                <div class='form-group col-md-12'>
                    <label for='phone'>Phone number:</label>
                    <input class='form-control' id='phone' type='text' required>
                </div>
                <label for='options' class='col-md-10'>Preferred contact method:</label>
                <div class='col-md-10'>
                    <label class='col-md-10'>
                        <input type='radio' name='options' value="phone">phone
                    </label>
                    <label class='col-md-10'>
                        <input type='radio' name='options' value="e-mail" checked>e-mail
                    </label>
                </div>
                <div class='form-group col-md-12'>
                    <label for='notes'>Notes:</label>
                    <textarea class='form-control' id='notes' rows='5'></textarea>
                </div>

                <div class='sArea col-md-12'>
                    <button type='submit' id='submitInfo' class="btn col-md-4">Send enquiry</button>
                </div>
            </form>
        </div>
    </div>

    <!--    Map div   -->
    <div class="col-md-12">
        <hr>
        <h3 class="text-center col-md-12">Boat Map</h3>
        <div id="map"></div>
        <script>
            var latitude = "<?=$boatLatitude; ?>";
            var longitude = "<?=$boatLongitude; ?>";
            var boat_id = "<?=$boatMainDetails['id']; ?>";
            var brokers_id = "<?=$boatMainDetails['brokers_id']; ?>";
        </script>
    </div>
</div>
</div>


<script>

</script>


<div class="foot col-md-12"></div>
<div class="footer col-md-12"></div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBusPgYdcaTfv_8qyYEgqOmKzy-SgVZX2M&callback=initMap"
        async defer></script>
<script src="https://code.jquery.com/jquery-3.1.1.js"
        integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA="
        crossorigin="anonymous"></script>
<script src="displayPicture.js"></script>
<script src="script.js"></script>
</body>
</html>