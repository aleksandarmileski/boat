<?php
session_start();
require "functions.php";

if (isset($_POST['search'])) {

    $_SESSION = $_POST;

//    echo "<pre>";
//    print_r($_POST);
//    echo "</pre>";
//    echo "<pre>";
//    print_r($_SESSION);
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
        <div class="pull-left col-md-10">
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

                    <div id="defaultStandardItems">
                        <input type="number"
                               min="0"
                            <?php if (isset($_SESSION['maxLength'])) {
                                echo "max='" . $_SESSION['maxLength'] . "'";
                            } ?>
                               placeholder="Min Length"
                               id="minLength" name="minLength"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['minLength'])) {
                                   echo $_SESSION['minLength'];
                               } ?>
                        >
                        <input type="number"
                            <?php if (isset($_SESSION['minLength'])) {
                                echo "min='" . $_SESSION['minLength'] . "'";
                            } else {
                                echo "min='0'";
                            } ?>
                               placeholder="Max Length"
                               id="maxLength" name="maxLength"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['maxLength'])) {
                                   echo $_SESSION['maxLength'];
                               } ?>
                        >
                        <input type="number" min="0"
                            <?php if (isset($_SESSION['maxHeadRoom'])) {
                                echo "max='" . $_SESSION['maxHeadRoom'] . "'";
                            } ?>
                               placeholder="Min Head Room"
                               id="minHeadRoom" name="minHeadRoom"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['minHeadRoom'])) {
                                   echo $_SESSION['minHeadRoom'];
                               } ?>
                        >
                        <input type="number"
                            <?php if (isset($_SESSION['minHeadRoom'])) {
                                echo "min='" . $_SESSION['minHeadRoom'] . "'";
                            } else {
                                echo "min='0'";
                            } ?>
                               placeholder="Max Head Room"
                               id="maxHeadRoom" name="maxHeadRoom"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['maxHeadRoom'])) {
                                   echo $_SESSION['maxHeadRoom'];
                               } ?>
                        >
                        <input type="number" min="0"
                            <?php if (isset($_SESSION['maxVoltage'])) {
                                echo "max='" . $_SESSION['maxVoltage'] . "'";
                            } ?>
                               placeholder="Min Voltage"
                               id="minVoltage" name="minVoltage"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['minVoltage'])) {
                                   echo $_SESSION['minVoltage'];
                               } ?>
                        >
                        <input type="number"
                            <?php if (isset($_SESSION['minVoltage'])) {
                                echo "min='" . $_SESSION['minVoltage'] . "'";
                            } else {
                                echo "min='0'";
                            } ?>
                               placeholder="Max Voltage"
                               id="maxVoltage" name="maxVoltage"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['maxVoltage'])) {
                                   echo $_SESSION['maxVoltage'];
                               } ?>
                        >
                        <input type="number" min="0"
                            <?php if (isset($_SESSION['maxMotor'])) {
                                echo "max='" . $_SESSION['maxMotor'] . "'";
                            } ?>
                               placeholder="Min Motor"
                               id="minMotor" name="minMotor"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['minMotor'])) {
                                   echo $_SESSION['minMotor'];
                               } ?>
                        >
                        <input type="number"
                            <?php if (isset($_SESSION['minMotor'])) {
                                echo "min='" . $_SESSION['minMotor'] . "'";
                            } else {
                                echo "min='0'";
                            } ?>
                               placeholder="Max Motor"
                               id="maxMotor" name="maxMotor"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['maxMotor'])) {
                                   echo $_SESSION['maxMotor'];
                               } ?>
                        >
                        <input type="number" min="0"
                            <?php if (isset($_SESSION['maxkW'])) {
                                echo "max='" . $_SESSION['maxkW'] . "'";
                            } ?>
                               placeholder="Min kW"
                               id="minkW" name="minkW"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['minkW'])) {
                                   echo $_SESSION['minkW'];
                               } ?>
                        >
                        <input type="number"
                            <?php if (isset($_SESSION['minkW'])) {
                                echo "min='" . $_SESSION['minkW'] . "'";
                            } else {
                                echo "min='0'";
                            } ?>
                               placeholder="Max kW"
                               id="maxkW" name="maxkW"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['maxkW'])) {
                                   echo $_SESSION['maxkW'];
                               } ?>
                        >
                        <input type="number" min="0"
                            <?php if (isset($_SESSION['maxHP'])) {
                                echo "max='" . $_SESSION['maxHP'] . "'";
                            } ?>
                               placeholder="Min POWER HP"
                               id="minHP" name="minHP"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['minHP'])) {
                                   echo $_SESSION['minHP'];
                               } ?>
                        >
                        <input type="number"
                            <?php if (isset($_SESSION['minHP'])) {
                                echo "min='" . $_SESSION['minHP'] . "'";
                            } else {
                                echo "min='0'";
                            } ?>
                               placeholder="Max POWER HP"
                               id="maxHP" name="maxHP"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['maxHP'])) {
                                   echo $_SESSION['maxHP'];
                               } ?>
                        >
                        <input type="number" min="0"
                            <?php if (isset($_SESSION['maxStabilizers'])) {
                                echo "max='" . $_SESSION['maxStabilizers'] . "'";
                            } ?>
                               placeholder="Min Stabilizers"
                               id="minStabilizers" name="minStabilizers"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['minStabilizers'])) {
                                   echo $_SESSION['minStabilizers'];
                               } ?>
                        >
                        <input type="number"
                            <?php if (isset($_SESSION['minStabilizers'])) {
                                echo "min='" . $_SESSION['minStabilizers'] . "'";
                            } else {
                                echo "min='0'";
                            } ?>
                               placeholder="Max Stabilizers"
                               id="maxStabilizers" name="maxStabilizers"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['maxStabilizers'])) {
                                   echo $_SESSION['maxStabilizers'];
                               } ?>
                        >
                        <input type="number" min="0"
                            <?php if (isset($_SESSION['maxBarometer'])) {
                                echo "max='" . $_SESSION['maxBarometer'] . "'";
                            } ?>
                               placeholder="Min Barometer"
                               id="minBarometer" name="minBarometer"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['minBarometer'])) {
                                   echo $_SESSION['minBarometer'];
                               } ?>
                        >
                        <input type="number"
                            <?php if (isset($_SESSION['minBarometer'])) {
                                echo "min='" . $_SESSION['minBarometer'] . "'";
                            } else {
                                echo "min='0'";
                            } ?>
                               placeholder="Max Barometer"
                               id="maxBarometer" name="maxBarometer"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['maxBarometer'])) {
                                   echo $_SESSION['maxBarometer'];
                               } ?>
                        >
                        <input type="number" min="0"
                            <?php if (isset($_SESSION['maxSleepingPlaces'])) {
                                echo "max='" . $_SESSION['maxSleepingPlaces'] . "'";
                            } ?>
                               placeholder="Min Sleeping Places"
                               id="minSleepingPlaces" name="minSleepingPlaces"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['minSleepingPlaces'])) {
                                   echo $_SESSION['minSleepingPlaces'];
                               } ?>
                        >
                        <input type="number"
                            <?php if (isset($_SESSION['minSleepingPlaces'])) {
                                echo "min='" . $_SESSION['minSleepingPlaces'] . "'";
                            } else {
                                echo "min='0'";
                            } ?>
                               placeholder="Max Sleeping Places"
                               id="maxSleepingPlaces" name="maxSleepingPlaces"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['maxSleepingPlaces'])) {
                                   echo $_SESSION['maxSleepingPlaces'];
                               } ?>
                        >
                        <input type="number" min="0"
                            <?php if (isset($_SESSION['maxCabin'])) {
                                echo "max='" . $_SESSION['maxCabin'] . "'";
                            } ?>
                               placeholder="Min Cabin"
                               id="minCabin" name="minCabin"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['minCabin'])) {
                                   echo $_SESSION['minCabin'];
                               } ?>
                        >
                        <input type="number"
                            <?php if (isset($_SESSION['minCabin'])) {
                                echo "min='" . $_SESSION['minCabin'] . "'";
                            } else {
                                echo "min='0'";
                            } ?>
                               placeholder="Max Cabin"
                               id="maxCabin" name="maxCabin"
                               class="inlineNumberInput form-control"
                               value=<?php if (isset($_SESSION['maxCabin'])) {
                                   echo $_SESSION['maxCabin'];
                               } ?>
                        >
                    </div>

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
                                   class="form-control input"
                                   value=<?php if (isset($_SESSION['description'][$i])) {
                                       echo $_SESSION['description'][$i];
                                   } ?>
                            >
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
                                if ((!isset($_POST['standard-item'])) && (!isset($_SESSION['standard-item']))) {
                                    echo "style='display:none'";
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
                <input type="number" step="100"
                       id="price-from" name="price-from"
                       min="0"
                    <?php if (isset($_SESSION['price-to'])) {
                        echo "max='" . $_SESSION['price-to'] . "'";
                    } ?>
                       value=<?php if (isset($_SESSION['price-from'])) {
                           echo $_SESSION['price-from'];
                       } else {
                           echo 0;
                       } ?>
                       class="form-control textinput input inlineProp inlineNumberInput">
                <label for="price-to">to</label>
                <input type="number" step="100"
                       id="price-to" name="price-to"
                    <?php if (isset($_SESSION['price-from'])) {
                        echo "min='" . $_SESSION['price-from'] . "'";
                    } else {
                        echo "min='0'";
                    } ?>
                       value=<?php if (isset($_SESSION['price-to'])) {
                           echo $_SESSION['price-to'];
                       } else {
                           echo 150000;
                       } ?>
                       class="form-control textinput input inlineProp inlineNumberInput">
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
    var categories = <?=json_encode(getBoatCategories()); ?>;
    var standardItems = <?=json_encode(getStandardItems()); ?>;
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