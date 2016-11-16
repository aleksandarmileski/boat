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
    if (isset($_POST['submitInfo'])) {
        getInfo($_POST, $boatMainDetails['id'], $boatMainDetails['brokers_id']);
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

<a href='index.php'>
    <div class='topNav'>
        <img class="img-responsive" style="margin-left: 150px;" src='Goliath.png'/>
    </div>
</a>

<div class='container'>

    <!--    Top main div-->
    <div class="col-sm-12">
        <div class="col-md-10 col-md-offset-1">

            <!--    Main info div-->
            <div class='boatContent'>
                <h3 id='title' class='col-sm-10'>
                    <span><?= $boatMainDetails['title']; ?></span>
                </h3>
                <hr>
                <h3 id='title' class='col-sm-10'>
                    <span> Price: <?= $boatPrice['value'] . " " . $boatPrice['currency'] ?></span>
                </h3>
                <hr>
                <h4 class='col-sm-10'>
                    Built in: <?= $boatMainDetails['year']; ?>
                </h4>
                <h4 class='col-sm-10'>
                    Builder: <?= $boatBuilder; ?>
                </h4>
                <h4 class='col-md-10'> Country: <?= $boatCountry[0]['country']; ?></h4>
                <h4 class='col-md-10'> Description: <?= $boatStatus['description']; ?></h4>
            </div>

            <!--    Picture div   -->
            <div class='w3-content w3-display-container slideImages slideImagesFix col-md-5'>
                <?php foreach ($boatPhotos as $url): ?>
                    <img class='img-responsive mySlides' style="width: 100%;"
                         src="<?= 'http://46.101.221.106/images/' . $url; ?>">
                <?php endforeach; ?>
                <a class='w3-btn-floating w3-display-left' onclick='plusDivs(-1)'>&#10094;</a>
                <a class='w3-btn-floating w3-display-right' onclick='plusDivs(1)'>&#10095;</a>

            </div>
        </div>
    </div>

    <!--    Standard Items div-->
    <div class="col-md-10 col-md-offset-1 text-center">
        <hr>
        <h3 class="text-center col-md-10 col-md-offset-1" style="margin-bottom: 20px;">Boat Standard Items</h3>
        <?php if (count($boatStandarsItems) == 0) : ?>
            <p class="text-center"> --- There is no data about Standard Items --- </p>
        <?php endif; ?>
        <?php foreach ($boatStandarsItems as $boatStandarsItemsCatgeoryID => $boatStandarsItemsDetails): ?>
            <div class="col-md-4">
                <h4 style="float: none !important; margin: 5px; font-weight: bold;"><?php if ($boatStandarsItemsCatgeoryID != 'null') {
                        echo($boatStandarsItemsCategory[$boatStandarsItemsCatgeoryID]);
                    } else {
                        echo 'No specified category';
                    }; ?>: </h4>
                <div class="col-md-8 col-md-offset-2">
                    <?php foreach ($boatStandarsItemsDetails as $boatStandarsItems): ?>
                        <h5 style="margin: 0 !important; font-weight: bold;"><?= $boatStandarsItems['name']; ?></h5> <?= $boatStandarsItems['value']; ?>
                    <?php endforeach; ?>
                    <hr/>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<!--    Contact div-->
<div class="col-md-10 col-md-offset-1 text-center" style="float:none !important;">

    <div class="col-md-6 col-md-offset-3" style="float:none !important;">
        <hr/>
        <h3 class='text-center' style="margin: 20px; float:none !important;">Find out more</h3>
        <p style="float:none; !important;">Interested in this boat? Find out more or arrange a viewing by completing
            this
            form.</p>
        <p style="float:none; !important;">We'll get back to you with more information and take you thorough the options
            for
            viewing and buying this boat.</p>
        <p style="float:none; !important;">Alternatively, don't forget that you can call us on +44 800 037 1329 anytime
            for a
            chat</p>
    </div>

    <div class="col-md-8 col-md-offset-2" style="float:none !important">

        <form id='getContactInfo' action='' method="post">
            <div class='form-group col-md-6 col-md-offset-3'>
                <label for='name'>Your name:</label>
                <input class='form-control' id='name' name="name" type='text' required>
            </div>
            <div class='form-group col-md-6 col-md-offset-3'>
                <label for='email'>Your email address:</label>
                <input class='form-control' id='email' name="email" type='text' required>
            </div>
            <div class='form-group col-md-6 col-md-offset-3'>
                <label for='phone'>Phone number:</label>
                <input class='form-control' id='phone' name="phone" type='text' required>
            </div>
            <label class="col-md-6 col-md-offset-3" for='options'>Preferred contact method:</label>
            <div class="col-md-6 col-md-offset-3">
                <label>
                    <input type='radio' name='options' value="phone">phone
                </label>
                <label>
                    <input type='radio' name='options' value="e-mail" checked>e-mail
                </label>
            </div>
            <div class="col-md-6 col-md-offset-3">
                <label for='notes'>Notes:</label>
                <textarea class='form-control' id='notes' name="notes" rows='5'></textarea>
            </div>

            <div class='sArea text-center col-md-8 col-md-offset-2' style="margin-top: 20px;">
                <button type='submit' id='submitInfo' name="submitInfo" class="btn">Send enquiry</button>
            </div>
        </form>
    </div>

</div>


<!--    Map div   -->
<div class="col-md-8 col-md-offset-2">
    <hr>
    <h3 class="text-center col-md-8 col-md-offset-2" style="margin-bottom: 20px;" Boat Map</h3>
    <div id="map"></div>
    <script>
        var latitude = "<?=$boatLatitude; ?>";
        var longitude = "<?=$boatLongitude; ?>";
        var boat_id = "<?=$boatMainDetails['id']; ?>";
        var brokers_id = "<?=$boatMainDetails['brokers_id']; ?>";
    </script>
</div>
</div>

<script>

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBusPgYdcaTfv_8qyYEgqOmKzy-SgVZX2M&callback=initMap"
        async defer></script>
<script src="https://code.jquery.com/jquery-3.1.1.js"
        integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA="
        crossorigin="anonymous"></script>
<script src="displayPicture.js"></script>
<script src="script.js"></script>
</body>
</html>