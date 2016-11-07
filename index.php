<?php
session_start();
require "functions.php";

if (isset($_POST['search'])){
    $_SESSION['boat-type']=$_POST['boat-type'];
    $_SESSION['size-from']=$_POST['size-from'];
    $_SESSION['size-to']=$_POST['size-to'];
    $_SESSION['price-from']=$_POST['price-from'];
    $_SESSION['price-to']=$_POST['price-to'];
    $_SESSION['boat-keyword']=$_POST['boat-keyword'];
    $_SESSION['boat-builder']=$_POST['boat-builder'];
    $_SESSION['boat-country']=$_POST['boat-country'];
    $_SESSION['boat-year']=$_POST['boat-year'];
}

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
            <label for="boat-type">Boat type:</label><br>

            <select id="boat-type" class="form-control input" name="boat-type">
                <option value="all">All boats</option>
                <?php

                $types = getBoatTypes();
                foreach ($types as $type): ?>
                    <option value="<?php echo $type['id']; ?>"
                            name="<?php echo $type['type']; ?>"
                            id="<?php echo $type['id']; ?>"
                            <?php if(isset($_SESSION['boat-type'])&&($type['id']==$_SESSION['boat-type'])) {echo 'selected';} ?> >
                        <?php echo $type['type']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br>

            <!--boat size-->
            <label for="size-from">Boat size:</label><br>
            <input type="number" min="0" step="50" name="size-from" id="size-from"
                   value=<?php if(isset($_SESSION['size-from'])) {echo $_SESSION['size-from'];} else{echo 50;} ?>
                   class="form-control textinput input">


            <label for="size-to">to</label>
            <input type="number" step="50" id="size-to" min="0" name="size-to"
                   value=<?php if(isset($_SESSION['size-to'])) {echo $_SESSION['size-to'];} else{echo 1500;} ?>
                   class="form-control textinput input">
            <label>ft</label>
            <br><br>

            <!--boat price-->
            <label>Price</label><br>
            <label for="price-from">&euro;</label><br>
            <input type="number" step="100" id="price-from" name="price-from" min="0"
                   value=<?php if(isset($_SESSION['price-from'])) {echo $_SESSION['price-from'];} else{echo 0;} ?>
                   class="form-control textinput input">
            <label for="price-to">to</label><br>
            <input type="number" step="100" id="price-to" name="price-to" min="0"
                   value=<?php if(isset($_SESSION['price-to'])) {echo $_SESSION['price-to'];} else{echo 15000;} ?>
                   class="form-control textinput input">
            <br>

            <div id="additional" class="invisible">
                <hr>
                <!--keyword-->
                <label for="boat-keyword">Keywords: </label><br>
                <input type="text" id="boat-keyword" class="form-control input" name="boat-keyword"
                       value=<?php if(isset($_SESSION['boat-keyword'])) {echo $_SESSION['boat-keyword'];} ?>
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
                            <?php if(isset($_SESSION['boat-builder'])&&($builder['name']==$_SESSION['boat-builder'])) {echo 'selected';} ?>
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
                            <?php if(isset($_SESSION['boat-country'])&&($country['country']==$_SESSION['boat-country'])) {echo 'selected';} ?>
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
                            <?php if(isset($_SESSION['boat-year'])&&($year['year']==$_SESSION['boat-year'])) {echo 'selected';} ?>
                        ><?php echo $year['year']; ?></option>
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