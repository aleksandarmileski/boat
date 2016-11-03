<?php
require "functions.php";

//echo "Your ip is: ".$_SERVER['REMOTE_ADDR'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Boat Search</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>
<body>

<div class='loginNav col-xs-12'></div>
<a href='index.php'>
    <div class='topNav'>
        <img src='Goliath.png'/>
    </div>
</a>

<div class="box col-xs-12">

    <div class="div1 col-xs-6">
        <form method="post" class="form-horizontal col-md-10">

            <!--boat types-->
            <label for="boat-type">Select boat type</label><br>

            <select id="boat-type" class="form-control input" name="boat-type">
                <option value="all">All boats</option>
                <?php

                $types = getBoatTypes();
                foreach ($types as $type): ?>
                    <option value="<?php echo $type['id']; ?>"
                            name="<?php echo $type['type']; ?>"
                            id="<?php echo $type['id']; ?>"
                        <?php if(isset($_POST['boat-type'])&&$_POST['boat-type']==$type['id']){echo 'selected';}?>
                    >
                        <?php echo $type['type']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br>

            <!--boat size-->
            <label for="size-from">Boat size:</label><br>
            <input type="text" id="size-from" class="form-control textinput input" name="size-from" value="">


            <label for="size-to">to</label>
            <input type="text" id="size-to" class="form-control textinput input" name="size-to" value="">
            <label>ft</label>
            <br><br>

            <!--boat price-->
            <label>Price</label><br>
            <label for="price-from">&euro;</label><br>
            <input type="text" id="price-from" class="form-control textinput input" name="price-from" value="">
            <label for="price-to">to</label><br>
            <input type="text" id="price-to" class="form-control textinput input" name="price-to" value="">
            <br>

            <div id="additional" class="invisible">
                <hr>
                <!--keyword-->
                <label for="boat-keyword">Keywords: </label><br>
                <input type="text" id="boat-keyword" class="form-control input" name="boat-keyword"><br>

                <!--boat builders-->
                <label for="boat-builder">Select boat builder</label><br>
                <select id="boat-builder" class="form-control input" name="boat-builder">
                    <option value="all">All boats</option>
                    <?php
                    $builders = getBoatBuilders();
                    foreach ($builders as $builder): ?>
                        <option value="<?php echo $builder['name']; ?>"
                                name="<?php echo $builder['name']; ?>"><?php echo $builder['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <br>

                <!--boat country-->
                <label for="boat-country">Select country</label><br>
                <select id="boat-country" class="form-control input" name="boat-country">
                    <option value="all">All boats</option>
                    <?php
                    $countries = getBoatCountry();
                    foreach ($countries as $country): ?>
                        <option calss="col-md-10" value="<?php echo $country['country']; ?>"
                                name="<?php echo $country['country']; ?>"><?php echo $country['country']; ?></option>
                    <?php endforeach; ?>
                </select>
                <br>

                <!--boat builders-->
                <label for="boat-year">Select boats built after</label><br>
                <select id="boat-year" class="form-control input" name="boat-year">
                    <option value="all">All boats</option>
                    <?php
                    $years = getBoatYears();
                    foreach ($years as $year): ?>
                        <option value="<?php echo $year['year']; ?>"
                                name="<?php echo $year['year']; ?>"><?php echo $year['year']; ?></option>
                    <?php endforeach; ?>
                </select>
                <br>

            </div>
            <a id="sh">Show aditional search properties</a>

            <input type="submit" class="btn btn-search col-md-12" value="Search" id="search" name="search">
        </form>
    </div>
    <div class="div2 col-xs-6">
        <?php
        if (isset($_POST['search'])) {
//            searchBoats();
            findBoats();
        } else {
            getRandomBoats();
        }
        ?>
    </div>
    <div class="clear">

    </div>
</div>


<script src="https://code.jquery.com/jquery-3.1.1.js"
        integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA="
        crossorigin="anonymous"></script>
<script src="script.js"></script>
</body>
</html>