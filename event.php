<?php
	require_once 'controllers/log_control.php';
	$custom_title = "Australia Trip Spring 2014";
	include "templates/head_tag.php";
?>
<style>
	.city {
		text-decoration:underline;
		font-weight:bold;
		font-style:italic;
	}
	.room {
		text-decoration:underline;
		font-style:italic;
	}
	.hotel {
		font-weight:bold;
	}
</style>
<div class="col-sm-10 col-sm-offset-1" style="margin-bottom:50px;">
	<table class="table table-bordered" >
	<caption style="font-weight:bold;font-size:18px;">EXHIBITION</caption>
	<tr>
		<th style="width:30%">Date(2015)</th>
		<th style="width:20%">Time</th>
		<th style="width:50%">Venue</th>
	</tr>
	<tr>
		<td><span class="city">SYDNEY</span><br />Jan 29 (Thu)<br />to<br />Jan 31 (Sat)</td>
		<td><br />9:00 am<br />to<br />6:00 pm</td>
		<td><br /><span class="room">Sussex Meeting Room</span> at <span class="hotel">Adina Apartment Hotel</span><br />511 Kent Street, Sydney, NSW 2000<br />Phone: (02) 9274-0000</td>
	</tr>
	<tr>
		<td><span class="city">BRISBANE</span><br />Feb 2 (Mon)<br />& <br />Feb 3 (Tue)</td>
		<td><br />9:00 am to 6:00 pm<br /> <br />9:00 am to 3:00 pm</td>
		<td><br /><span class="room">Room 3032</span> at <span class="hotel">Rendezvous Hotel Brisbane</span><br />225 Ann Street. (CNR Edward Street) Brisbane, QLD 4000<br />Phone: (07) 3001-9888</td>
	</tr>
	<tr>
		<td><span class="city">MELBOURNE</span><br />Feb 4 (Wed)<br />to<br />Feb 5 (Thu)</td>
		<td><br />9:00 am<br />to<br />6:00 pm</td>
		<td><br /><span class="room">Orchid Meeting Room</span> at <span class="hotel">Adina Apartment Hotel</span><br />189 Queen Street, Melbourne, VIC 3000<br />Phone: (03) 9934-0000</td>
	</tr>
	</table>
	<div class="row">
	<a href="booking.php" class="col-sm-offset-4 col-sm-4 btn btn-wcs-default">Make/Amend Booking</a>
	</div>
	<div class="row">
		<a href="portal.php" class="col-sm-offset-5 col-sm-2 btn">Back</a>
	</div>
</div>


<?php 
	include "templates/footer_tag.php";
?>