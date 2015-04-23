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
		<td><span class="city"></span>June 3 (Wed)<br />June 4 (Thu) <br />June 5 (Fri) <br />June 6 (Sat)</td>
		<td>9:00 am<br />to<br />6:00 pm</td>
		<td><span class="room">PICASSOO ROOM</span><br /><span class="hotel">MERCURE London Bridge Hotel</span><br />71-79 Southwark Street, London, SE1 0JA, UK.<br />Phone number: (+44) 0207 6600683</td>
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