<?php
include "Aircraft.php";
include "Airport.php";
include "Flight.php";

echo "I am not alive. 🐱‍🐉<br><br>"; // windows + punkts = emoji

$manaLidmasina = new Aircraft("Airbus", "A220-300", 120, 850);
var_dump($manaLidmasina);

echo "<br><br>";

$manaAirport = new Airport("RIX", 56.924, 23.971);
var_dump($manaAirport);

echo "<br><br>";

$destination = new Airport("JFK", 40.6413, -73.7781); // Pievieno galamērķa lidostu
$departureTime = new DateTime('2023-10-15 10:00:00', new DateTimeZone('Europe/Riga'));
$mansFlight = new Flight("SA503", $manaAirport, $destination, $departureTime, $manaLidmasina); // Izmanto iepriekš definētos objektus
var_dump($mansFlight);

echo "<br><br><br>";

echo "Flight Code: " . $mansFlight->flightCode . "<br>";
echo "Origin: " . $mansFlight->origin->kods . " (" . $mansFlight->origin->platums . ", " . $mansFlight->origin->garums . ")<br>";
echo "Destination: " . $mansFlight->destination->kods . " (" . $mansFlight->destination->platums . ", " . $mansFlight->destination->garums . ")<br>";
echo "Departure Time: " . $mansFlight->departureTime->format('Y-m-d H:i:s') . "<br>";
echo "Aircraft: " . $mansFlight->aircraft->razotajs . " " . $mansFlight->aircraft->modelis . "<br>";
?>