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

    $_SESSION['category'] = $_POST['category'];
    $_SESSION['standard-item'] = $_POST['standard-item'];

    $_SESSION['description'] = $_POST['description'];

//    echo "<pre>";
//    print_r($_POST);
//    echo "</pre>";

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Boat Search</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
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

                    isset($_SESSION['category'])
                        ? $cycleNo = count($_SESSION['category'])
                        : $cycleNo = 1;

                    for ($i = 0; $i < $cycleNo; $i++):
//                        if (isset($_SESSION['standard-item'])) print_r($_SESSION['standard-item'][$i]);

                        ?>
                        <div class="mainStandardItem" id="mainStandardItem<?php echo $i > 0 ? $i : ""; ?>">
                            <label>Boat category:</label><br>
                            <select
                                name="category[]"
                                id="category<?php echo $i > 0 ? $i : ""; ?>"
                                class="form-control input">
                                <option id='all'
                                        value='all'>
                                    All categories
                                </option>
                                <?php $categories = getBoatCategories();
                                for ($iCat = 0; $iCat < count($categories); $iCat++): ?>
                                    <option id="<?= $categories[$iCat]['id']; ?>"
                                            value="<?= $categories[$iCat]['id']; ?>"
                                        <?php if (isset($_SESSION['category']) && ($_SESSION['category'][$i] == $categories[$iCat]['id'])) echo "selected"; ?>>
                                        <?= $categories[$iCat]['name']; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <label>Boat standard item:</label><br>
                            <select
                                name="standard-item[]"
                                id="standard-item<?php echo $i > 0 ? $i : ""; ?>"
                                class="form-control input">
                                <?php $standardItems = getStandardItems();
                                for ($iSI = 0; $iSI < count($standardItems); $iSI++): ?>
                                    <option id="<?= $standardItems[$iSI]['id']; ?>"
                                            value="<?= $standardItems[$iSI]['id']; ?>"
                                        <?php if (isset($_SESSION['standard-item']) && ($_SESSION['standard-item'][$i] == $standardItems[$iSI]['id'])) echo "selected"; ?>>
                                        <?= $standardItems[$iSI]['name']; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <label>Description: </label>
                            <input type="text"
                                   id="description<?php echo $i > 0 ? $i : ""; ?>"
                                   name="description[]"
                                   class="form-control input">

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
            findBoats();
        } else {
            getRandomBoats();
        }
        ?>
    </div>
    <div class="clear">

    </div>
</div>

<script>
    var categories = <?=json_encode(getBoatCategories()); ?>;
    var standardItems = <?=json_encode(getStandardItems()); ?>;
    var standardItemsNullDimension = <?=json_encode(getStandardItemsNullDimensions()); ?>;
</script>
<script src="https://code.jquery.com/jquery-3.1.1.js"
        integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA="
        crossorigin="anonymous"></script>


<script src="script.js"></script>
</body>
</html>