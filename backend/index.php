<?php
include "Aircraft.php";
include "Airport.php";
include "Flight.php";

echo "I am not alive. 🐱‍🐉<br><br>";//windows + punkts = emoji

$manaLidmasina = new Aircraft("Airbuss", "A220-300", 120, 850);
var_dump($manaLidmasina);

echo "<br><br>";

$manaAirport = new Airport("RIX", 56.924, 23.971);
var_dump($manaAirport);

echo "<br><br>";

$departureTime = new DateTime('2024-11-19 15:00:00', new DateTimeZone('Europe/Riga'));
$mansFlight = new Flight("SA503", $origin, $manaAirport, $departureTime, $manaLidmasina);
var_dump($mansFlight);