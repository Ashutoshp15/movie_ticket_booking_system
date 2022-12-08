<html>
	<head>
		<meta charset="utf-8">
		<title>MovieBooking</title>
		<link rel="stylesheet" type="text/css" href="main.css">
		<style>

            #movie_info
            {
                font-weight: normal;
                font-size: 18px;
                margin-bottom: 12px;
                color: #1034a6;
            }
            
            #show_info
            {
                font-weight: normal;
                font-size: 18px;
                margin-bottom: 60px;
                color: #1034a6;
            }

            .seat
            {
                color: green;
                width: 40px;
                height: 40px;
                text-align: center;
                line-height: 40px;
                border: 1px solid green;
                border-radius: 12px;
                margin: 5px;
            }

            .space
            {
                width: 40px;
                height: 40px;
                border: 1px dotted;
                border-radius: 12px;
                margin: 5px;
                display: none;
            }

            .available
            {
                background-color: white;
                color: green;
            }
            
            .available:hover
            {
                cursor: pointer;
            }

            .booked
            {
                background-color: darkgray;
                color: white;
                border-color: darkgray; 
            }
            
            .booked:hover
            {
                cursor: default;
                background-color: darkgray;
                border-color: darkgray;
            }
            
            .clicked
            {
                background-color: green;
                color: white;
                cursor: pointer;
            }

            #booking
            {
                width: 126px;
                margin-left: auto;
                margin-right: auto;
                margin-top: 40px;
            }

            #button
            {
                border-radius: 8px;
                font-family:'Roboto',sans-serif;
                font-weight: 500;
                color: #FFFFFF;
                background-color: #1072d6;
                text-align: center;
                transition: all 0.2s;
                padding: 12px 36px;
                font-size: 22px;
                cursor: pointer;
            }

            #button:hover
            {
                opacity: 0.9;
            }

            #ticket
            {
                background: white;
                margin: 20px;
                padding: 40px;
                border-radius: 35px;
                width: 50%;
                margin-left: auto;
                margin-right: auto;
                text-align: center;
            }

            #ticket #transaction
            {
                color: #1072d6;
                font-size: 22px;
            }
			
			#seatmap
            {
                overflow-x: scroll;
                padding-bottom: 16px;
            }

		</style>
	</head>

	<body>

<?php
include 'db.php';
session_start();

$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$_SESSION["url"] = filter_var($url, FILTER_VALIDATE_URL);

$username = @$_SESSION["username"];
?>

		<div id="header">
			
			<div id="logo">
                <a href='index.php'>MovieBooking</a>
            </div>

			<div id="login">

<?php

$userId = 0;

if(@$username) {
    $userId = @$_SESSION["userid"];
	echo "
			<a>Profile</a>
            <div id='menu'>
                <span id='username'>$username</span>
                <a href='bookedtickets.php'>Booked Tickets</a>
                <a href='logout.php'>Logout</a>
            </div>";
}
else {
	echo "<a href='login.php'>Login</a>";
}
?>
			</div>
		</div>

		<div id="main">

<?php

$showId = $_GET["showId"];

//Extracting data from the table movie_show
$sql = "SELECT movie_id, date, time, screen_id FROM movie_show WHERE show_id=$showId";

if($result = mysqli_query($conn, $sql)) {
	$row = mysqli_fetch_assoc($result);
    
    $movieId = $row['movie_id'];
    $date = date_create($row['date']);
    $date = date_format($date, "l, j M Y");
    $time = date_create($row['date']. " ". $row['time']);
    $time = date_format($time, "h:i A");
    $screenId = $row['screen_id'];
    
}
else {
	echo "<p>ERROR: Failed to execute query $sql ".mysqli_error($conn)."</p>";
}

//Extracting data from the table movie
$sql = "SELECT name, language, age_certificate, format, runtime FROM movie WHERE movie_id=$movieId";

if($result = mysqli_query($conn, $sql)) {
    $row = mysqli_fetch_assoc($result);
    
    $movie_name = $row['name'];
    $language = $row['language'];
    $format = $row['format'];
    $runtime = $row['runtime'];
    $age_certificate = $row['age_certificate'];

}
else {
    echo "<p>ERROR: Failed to execute query $sql ".mysqli_error($conn)."</p>";
}


//Extracting data from the table screen
$sql = "SELECT name, theatre_id, seat_map FROM screen WHERE screen_id=$screenId";

if($result = mysqli_query($conn, $sql)) {
    $row = mysqli_fetch_assoc($result);
    
    $screen_name = $row['name'];
    $theatreId = $row['theatre_id'];
    $seatmap = $row['seat_map'];

}
else {
    echo "<p>ERROR: Failed to execute query $sql ".mysqli_error($conn)."</p>";
}


//Extracting data from the table theatre
$sql = "SELECT name FROM theatre WHERE theatre_id=$theatreId";

if($result = mysqli_query($conn, $sql)) {
    $row = mysqli_fetch_assoc($result);
    
    $theatre_name = $row['name'];

}
else {
    echo "<p>ERROR: Failed to execute query $sql ".mysqli_error($conn)."</p>";
}


$sql = "SELECT seat_number FROM ticket WHERE show_id=$showId";
$bookedSeats = "";

if($result = mysqli_query($conn, $sql)) {
    while($row = mysqli_fetch_assoc($result)) {
        $bookedSeats .= $row['seat_number'].",";
    }
}
else {
    echo "<p>ERROR: Failed to execute query $sql ".mysqli_error($conn)."</p>";
}

mysqli_close($conn);
?>
            
            <div id='movie_info'>
<?php echo "$movie_name | $language $format &nbsp;($age_certificate) | $runtime mins"; ?>
			</div>

            <div id='show_info'>
<?php echo "$date $time | $theatre_name $screen_name"; ?>
            </div>

            <div id='seatmap'>
<?php //echo $seatmap; 
// echo "***";
// echo $seat_id; die();
?>
<table>	
				<tbody><tr>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="A-1"><span>A-1</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="A-2"><span>A-2</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="A-3"><span>A-3</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="A-4"><span>A-4</span></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="A-5"><span>A-5</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="A-6"><span>A-6</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="A-7"><span>A-7</span></div></td>
				</tr>
				<tr>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="B-1"><span>B-1</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="B-2"><span>B-2</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="B-3"><span>B-3</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="B-4"><span>B-4</span></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="space"></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="B-5"><span>B-5</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="B-6"><span>B-6</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="B-7"><span>B-7</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="B-8"><span>B-8</span></div></td>
				</tr>
				<tr>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-1"><span>C-1</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-2"><span>C-2</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-3"><span>C-3</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-4"><span>C-4</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-5"><span>C-5</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-6"><span>C-6</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-7"><span>C-7</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-8"><span>C-8</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-9"><span>C-9</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-10"><span>C-10</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-11"><span>C-11</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-12"><span>C-12</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-13"><span>C-13</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-14"><span>C-14</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-15"><span>C-15</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="C-16"><span>C-16</span></div></td>
				</tr>
				<tr>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-1"><span>D-1</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-2"><span>D-2</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-3"><span>D-3</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-4"><span>D-4</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-5"><span>D-5</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-6"><span>D-6</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-7"><span>D-7</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-8"><span>D-8</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-9"><span>D-9</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-10"><span>D-10</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-11"><span>D-11</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-12"><span>D-12</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-13"><span>D-13</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-14"><span>D-14</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-15"><span>D-15</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="D-16"><span>D-16</span></div></td>
				</tr>
				<tr>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-1"><span>E-1</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-2"><span>E-2</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-3"><span>E-3</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-4"><span>E-4</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-5"><span>E-5</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-6"><span>E-6</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-7"><span>E-7</span></div></td>
					<td><div class="seat booked" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-8"><span>E-8</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-9"><span>E-9</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-10"><span>E-10</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-11"><span>E-11</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-12"><span>E-12</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-13"><span>E-13</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-14"><span>E-14</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-15"><span>E-15</span></div></td>
					<td><div class="seat available" data-class="Platinum" data-price="200" onclick="clicked(this.id)" id="E-16"><span>E-16</span></div></td>
				</tr>
			</tbody></table>



            </div>

			<form style='display:none'>
                <input type="text" value='<?php echo $bookedSeats; ?>' id='bookedSeats'>
                <input type='text' id='userid' value='<?php echo $userId; ?>'>
                <input type='text' id='showid' value='<?php echo $showId; ?>'>
              
            </form>
            <div id='booking'>
                <button id='button' onclick='booktickets()'>Book</button>
            </div>

            <div class="clear"></div>

	    </div>

		<div id="footer"></div>

		<script>

            booked = document.getElementById('bookedSeats').value;
            len = booked.length;
            i=0;
            
            while(i<len)
            {
                seat="";
                while(booked.charAt(i)!=",")
                {
                    seat = seat + booked.charAt(i);
                    i=i+1;
                }
                document.getElementById(seat).className = 'seat booked';
                i=i+1;
            }
			
            function booktickets()
            {

                userid = document.getElementById('userid').value;
                showid = document.getElementById('showid').value;

                if(userid == 0){
                    window.alert("Please login to your account");
                    window.location.href = 'login.php';
                }
                else
                {
                    arr = document.getElementsByClassName('seat clicked');
                    len = arr.length;
                                
                    seats = "";
                    types = "";
                    prices = ""
                       
                    for(i=0; i<len; i++){
                        seats += arr[i].id;
                        types += arr[i].dataset.class;
                        prices += arr[i].dataset.price;

                        if(i!=len-1){
                            seats += ",";
                            types += ",";
                            prices += ",";
                        }
                           
                    }
                    
                    if(seats == "") {
                        window.alert("Please select your seats first");
                    }
                    
                    else {

                        if (window.XMLHttpRequest)
                        {
                            xmlhttp=new XMLHttpRequest();
                        }
                        xmlhttp.onreadystatechange=function()
                        {
                            if (this.readyState==4 && this.status==200)
                            {
                                document.getElementById('main').innerHTML = this.responseText;
                            }
                        }
                        xmlhttp.open("GET","booktickets.php?seats="+ seats +"&types="+ types +"&prices="+ prices +"&userId="+ userid +"&showId="+ showid, true);
                        xmlhttp.send();
                    }

                }
            }

            function clicked(str)
            {
                obj = document.getElementById(str);
                
                if(obj.className == 'seat available')
                    obj.className = 'seat clicked';
                
                else if(obj.className == 'seat clicked')
                    obj.className = 'seat available';
            }

		</script>

	</body>

</html>
