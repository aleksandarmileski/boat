<?php
require "functions.php";


if (isset($_GET['id'])) {
//    echo "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    $id = htmlspecialchars($_GET['id']);

    if (checkBoatExist($id)) {

        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://46.101.221.106/api/boat/'.$id.'?token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6ImRlbmtvbWFuY2Vza2kxMjNAZ21haWwuY29tIiwiaWQiOjE4NywiaWF0IjoxNDc4MDEyNDMxfQ.snQ9PvwVTrsJlNIfi69ZP5flsZe3lntaPCsszAakU9U',
            CURLOPT_USERAGENT => 'Sample cURL Boat Request'
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        //    echo gettype(json_decode($resp));
        $boatInfoObject=(array)json_decode($resp);

        if (isset($resp)) {
            var_dump($boatInfoObject);
        }

        $boatInfo = displayBoatInfo($id);
        //        var_dump($boatInfo);
        echo "<div class='loginNav col-md-12'></div>";
        echo "<a href='index.php'><div class='topNav col-md-12'>";
        echo "<img  src='Goliath.png' />";
        echo "</div></a>";

        echo "<div class='container col-md-12'>";
        echo "<div class='boatContent'><h3 id='title' class='col-md-10'>" . $boatInfo['title'] . "</h3><hr>

	<h3 id='title' class='col-md-10'>
		Price: " . $boatInfo['price'] . " &euro; <br> <br>
	</h3>

	<h4 class='col-md-10'> 
		Built: " . $boatInfo['year'] . " </h4><br>
		<h4 class='col-md-10'> 
			Builder: " . $boatInfo['builder'] . " </h4> 
			<h4 class='col-md-10'> Currently lying: " . $boatInfo['country'] . ", 
				 

				Description: " . $boatInfo['description'] . "</h4></div>";
        // echo "<img src='http://46.101.221.106/images/" . $boatInfo['photo_url'] . "' class='boatContent'>";

        getBoatPhotos($_GET['id']);

        echo "</div>";
        echo "
		<div class='dimension col-md-6'>
		<hr>
		<h3 class='col-md-10'>Dimensions</h3>
		<hr>
		<p class='col-md-10'>	LOA: " . $boatInfo['boat_size'] . " ft </p>
		</div>
		<div class='dimension col-md-12'>
			<hr>
			<h3 class='col-md-10'>Find out more</h3>
			<p class='col-md-10'>Interested in this boat? Find out more or arrange a viewing by completing this form.</p>
			<p class='col-md-10'>We'll get back to you with more information and take you thorough the options for viewing and buying this boat.</p>
			<p class='col-md-10'>Alternatively, don't forget that you can call us on +44 800 037 1329 anytime for a chat</p>
			<hr>
		</div>
		<div class='col-md-12'>
			<form id='getInfo' action=''>
			    <div class='form-group col-md-12'>
			      <label for='name'>Your name:</label>
			      <input class='form-control' id='name' type='text'>
			    </div>
			        <div class='form-group col-md-12''>
			      <label for='email'>Your email address:</label>
			      <input class='form-control' id='email' type='text'>
			    </div>
			        <div class='form-group col-md-12''>
			      <label for='phone'>Phone number:</label>
			      <input class='form-control' id='phone' type='text'>
			    </div>
			      <label for='options' class='col-md-10'>Preferred contact method:</label>
				<div class='col-md-10'>
					<label class='col-md-10'>
			      <input type='radio' name='options' checked>phone
			    </label>
			    <label class='col-md-10'>
			      <input type='radio' name='options'>e-mail
			    </label>
				</div>
				<div class='form-group col-md-12'>
			      <label for='notes'>Notes:</label>
			      <textarea class='form-control' id='notes' rows='5'></textarea>	
			    </div>
			    
			    <div class='col-md-2'>
					<button type='submit' id='submitInfo'>Send enquiry</button>
			    </div>
			</form>
		</div>
		<div class='col-md-12'>
			
		</div>
				";
    } else {
        header("Location: http://" . $_SERVER["HTTP_HOST"] . "/boat/");
    }
} else {
    echo "there is no set id";
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


<div class="foot col-md-12"></div>
<div class="footer col-md-12"></div>
<script src="https://code.jquery.com/jquery-3.1.1.js"
        integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA="
        crossorigin="anonymous"></script>
<script src="script.js"></script>
</body>
</html>