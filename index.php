<?php
session_start();
require "functions.php";

if (isset($_POST['search'])) {

    $_SESSION['search'] = $_POST['search'];

    $_SESSION['boat-type'] = $_POST['boat-type'];
    $_SESSION['price-from'] = $_POST['price-from'];
    $_SESSION['price-to'] = $_POST['price-to'];
    $_SESSION['boat-keyword'] = $_POST['boat-keyword'];
    $_SESSION['boat-builder'] = $_POST['boat-builder'];
    $_SESSION['boat-country'] = $_POST['boat-country'];
    $_SESSION['boat-year'] = $_POST['boat-year'];

    $_SESSION['value-from'] = $_POST['value-from'];
    $_SESSION['value-to'] = $_POST['value-to'];

    $_SESSION['standard-item'] = $_POST['standard-item'];

    $_SESSION['description'] = $_POST['description'];

//    echo "<pre>";
//    print_r($_POST);
//    echo "</pre>";

}

?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Boat Search</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css"
          integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>
<body>

<a href='index.php'>
    <div class='topNav'>
        <img class="img-responsive" style="margin-left: 150px;" src='Goliath.png'/>
    </div>
</a>

<div class="container">

    <div class="pull-left col-md-6">
        <div class="pull-left col-md-6">
            <form method="post" class="form-horizontal">

                <!--boat types-->
                <label for="boat-type">Boat type:</label><br>

                <select id="boat-type" class="form-control input" name="boat-type">
                    <option value="all">All boats</option>
                    <?php
                    $types = getBoatTypes();
                    foreach ($types as $type): ?>
                        <option value="<?php echo $type['id']; ?>"
                                name="<?php echo $type['type']; ?>"
                                id="<?php echo $type['id']; ?>"
                            <?php if (isset($_SESSION['boat-type']) && ($type['id'] == $_SESSION['boat-type'])) {
                                echo 'selected';
                            } ?> >
                            <?php echo $type['type']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br>

                <!--boat standard-item-value-->
                <div class="standardItems">
                    <label for="standard-item-value-from">Boat standard items:</label>
                    <hr>
                    <?php

                    isset($_SESSION['standard-item'])
                        ? $cycleNo = count($_SESSION['standard-item'])
                        : $cycleNo = 1;

                    for ($i = 0; $i < $cycleNo; $i++):
//                        if (isset($_SESSION['standard-item'])) print_r($_SESSION['standard-item'][$i]);

                        ?>
                        <div class="mainStandardItem" id="mainStandardItem<?php echo $i > 0 ? $i : ""; ?>">
                            <!-- only standard items-->
                            <div>
                                <label>Boat standard item:</label><br>
                                <select
                                    name="standard-item[]"
                                    id="standard-item<?php echo $i > 0 ? $i : ""; ?>"
                                    class="form-control input">
                                    <?php $categories = getBoatCategories();
                                    for ($iCat = 0; $iCat < count($categories); $iCat++): ?>
                                        <option id="<?= $categories[$iCat]['id']; ?>"
                                                value="<?= $categories[$iCat]['id']; ?>"
                                                class="categoryBackground"
                                                disabled>
                                            <?= $categories[$iCat]['name']; ?>
                                        </option>
                                        <?php $standardItems = getBoatStandardItem($categories[$iCat]['id']);
                                        for ($iSI = 0; $iSI < count($standardItems); $iSI++): ?>
                                            <option id="<?= $standardItems[$iSI]['id']; ?>"
                                                    value="<?= $standardItems[$iSI]['id']; ?>"
                                                    class="standardItems"
                                                <?php if (isset($_SESSION['standard-item']) && ($standardItems[$iSI]['id'] == $_SESSION['standard-item'][$i])) {
                                                    echo 'selected';
                                                } ?>>
                                                <?= $standardItems[$iSI]['name']; ?>
                                            </option>
                                        <?php endfor; ?>
                                    <?php endfor; ?>
                                    <option value="all" name="all" id="0"
                                            class="categoryBackground"
                                            disabled>No category
                                    </option>
                                    <?php $standardItemsNoCategory = getBoatStandardItem('all');
                                    for ($iSInc = 0; $iSInc < count($standardItemsNoCategory); $iSInc++): ?>
                                        <option id="<?= $standardItemsNoCategory[$iSInc]['id']; ?>"
                                                value="<?= $standardItemsNoCategory[$iSInc]['id']; ?>"
                                                class="standardItems"
                                            <?php if (isset($_SESSION['standard-item']) && ($standardItemsNoCategory[$iSInc]['id'] == $_SESSION['standard-item'][$i])) {
                                                echo 'selected';
                                            } ?>>
                                            <?= $standardItemsNoCategory[$iSInc]['name']; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <label>Description: </label>
                            <input type="text"
                                   id="description<?php echo $i > 0 ? $i : ""; ?>"
                                   name="description[]"
                                   class="form-control input"
                                   value="<?php if (isset($_SESSION['description'][$i])) {
                                       echo $_SESSION['description'][$i];
                                   } ?>">

                            <div id="dimensionValues<?php echo $i > 0 ? $i : ""; ?>"
                                <?php
                                if (isset($_SESSION['standard-item'])) {
//                                echo $_SESSION['standard-item'][$i];
                                    $nullDimensions = getStandardItemsNullDimensions();
                                    $contains = false;
                                    foreach ($nullDimensions as $dim) {
                                        if ($_SESSION['standard-item'][$i] == $dim['id']) $contains = true;
                                    }
                                    echo $contains
                                        ? ""
                                        : "style='display:none'";
                                }
                                //      if ((!isset($_POST['standard-item'])) && (!isset($_SESSION['standard-item']))) {
                                //          echo "style='display:none'";
                                //      }
                                ?>
                            >
                                <label>from</label>
                                <input type="number" min="0" step="1"
                                       name="value-from[]"
                                       id="value-from<?php echo $i > 0 ? $i : ""; ?>"
                                       value=<?php if (isset($_SESSION['value-from'][$i])) {
                                           echo $_SESSION['value-from'][$i];
                                       } else {
                                           echo 0;
                                       } ?>
                                       class="form-control textinput input inlineProp">
                                <label>to</label>
                                <input type="number" step="1" min="0"
                                       id="value-to<?php echo $i > 0 ? $i : ""; ?>"
                                       name="value-to[]"
                                       value=<?php if (isset($_SESSION['value-to'][$i])) {
                                           echo $_SESSION['value-to'][$i];
                                       } else {
                                           echo 50;
                                       } ?>
                                       class="form-control textinput input inlineProp">
                            </div>
                            <?php if ($i > 0) : ?>
                                <button class='removeCategory btn btn-danger'>Remove category</button>
                            <?php endif ?>
                        </div>
                    <?php endfor; ?>
                </div>
                <br>
                <button class="addCategory btn btn-search col-md-12">Add category</button>
                <br><br>


                <!--boat price-->
                <label>Price</label><br>
                <input type="number" step="100" id="price-from" name="price-from" min="0"
                       value=<?php if (isset($_SESSION['price-from'])) {
                           echo $_SESSION['price-from'];
                       } else {
                           echo 0;
                       } ?>
                       class="form-control textinput input inlineProp">
                <label for="price-to">to</label>
                <input type="number" step="100" id="price-to" name="price-to" min="0"
                       value=<?php if (isset($_SESSION['price-to'])) {
                           echo $_SESSION['price-to'];
                       } else {
                           echo 150000;
                       } ?>
                       class="form-control textinput input inlineProp">
                <label for="price-from">&euro;</label><br>
                <br>

                <div id="additional" class="invisible">
                    <hr>
                    <!--keyword-->
                    <label for="boat-keyword">Keywords: </label><br>
                    <input type="text" id="boat-keyword" class="form-control input" name="boat-keyword"
                           value=<?php if (isset($_SESSION['boat-keyword'])) {
                               echo $_SESSION['boat-keyword'];
                           } ?>
                    ><br>

                    <!--boat builders-->
                    <label for="boat-builder">Builder:</label><br>
                    <select id="boat-builder" class="form-control input" name="boat-builder">
                        <option value="all">All boats</option>
                        <?php
                        $builders = getBoatBuilders();
                        foreach ($builders as $builder): ?>
                            <option value="<?php echo $builder['name']; ?>"
                                    name="<?php echo $builder['name']; ?>"
                                <?php if (isset($_SESSION['boat-builder']) && ($builder['name'] == $_SESSION['boat-builder'])) {
                                    echo 'selected';
                                } ?>
                            ><?php echo $builder['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br>

                    <!--boat country-->
                    <label for="boat-country">Currently lying:</label><br>
                    <select id="boat-country" class="form-control input" name="boat-country">
                        <option value="all">All boats</option>
                        <?php
                        $countries = getBoatCountry();
                        foreach ($countries as $country): ?>
                            <option calss="col-md-10" value="<?php echo $country['country']; ?>"
                                    name="<?php echo $country['country']; ?>"
                                <?php if (isset($_SESSION['boat-country']) && ($country['country'] == $_SESSION['boat-country'])) {
                                    echo 'selected';
                                } ?>
                            ><?php echo $country['country']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br>

                    <!--boat year-->
                    <label for="boat-year">Built after:</label><br>
                    <select id="boat-year" class="form-control input" name="boat-year">
                        <option value="all">All boats</option>
                        <?php
                        $years = getBoatYears();
                        foreach ($years as $year): ?>
                            <option value="<?php echo $year['year']; ?>"
                                    name="<?php echo $year['year']; ?>"
                                <?php if (isset($_SESSION['boat-year']) && ($year['year'] == $_SESSION['boat-year'])) {
                                    echo 'selected';
                                } ?>
                            ><?php echo $year['year']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br>

                </div>
                <a id="sh" style="margin-bottom: 10px;">Show additional search properties...</a>

                <input type="submit" class="btn btn-search col-md-12" value="Search" id="search" name="search">
            </form>
        </div>
    </div>

    <div class="col-md-6 pull-right">
        <?php
        if (isset($_POST['search']) || isset($_SESSION['search'])) {
            $boats = findBoats();
        } else {
            $boats = getRandomBoats();
        }
        ?>
        <div id="boatList">
            <h3>Boats found: <?= count($boats); ?></h3>
            <?php $i = 0; ?>
            <?php foreach ($boats as $boat): ?>
                <div class="boats" id="<?= $i; ?>">
                    <a href="http://<?= $_SERVER['HTTP_HOST']; ?>/boat/boat.php?id=<?= $boat['id']; ?>"
                       class="boatLink">
                        <div class='res grow' id='$id'>
                            <div class='col-md-4'>
                                <img src="<?= $photoUrl; ?><?= $boat['photo_url']; ?>" class='image-rounded grow'
                                     height=100>
                            </div>
                            <h3 id='title' class='col-md-8'><?= $boat['title']; ?></h3>
                            <h4 class='col-md-8'> Type: <?= $boat['type']; ?>, Price: <?= $boat['price']; ?> &euro;,
                                Builder: <?= $boat['builder']; ?>, Country: <?= $boat['country']; ?>,
                                Boat year: <?= $boat['year']; ?></h4>
                        </div>
                    </a>
                </div>
                <?php $i++; ?>
            <?php endforeach; ?>
        </div>
        <?php if (count($boats) > 0) : ?>
            <div id="pagination"></div>
        <?php endif; ?>
    </div>

</div>

<script>
    var standardItemsNullDimension = <?=json_encode(getStandardItemsNullDimensions()); ?>;
    var boatsNo = <?=json_encode(ceil(count($boats) / 10)); ?>;
</script>
<script src="https://code.jquery.com/jquery-3.1.1.js"
        integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js"
        integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js"
        integrity="sha384-VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU"
        crossorigin="anonymous"></script>
<script src="jquery.twbsPagination.js" type="text/javascript"></script>
<script src="pagination.js"></script>
<script src="script.js"></script>
</body>
</html>