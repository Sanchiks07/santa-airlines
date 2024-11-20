<?php

include "Aiport.php";
include "Aicraft.php";

class Flight {
    public function __construct(public $flightCode,
                                public $origin,
                                public $destination,
                                public $departureTime,
                                public $aircraft) {
    }

    public function getDistance() {
        $earthRadius = 6371; // Zemes vidējais rādiuss kilometros

        // Konvertējam grādus uz radiāniem
        $platFrom = deg2rad($this->origin->platums);
        $garFrom = deg2rad($this->origin->garums);
        $platTo = deg2rad($this->destination->platums);
        $garTo = deg2rad($this->destination->garums);

        // Aprēķinām atšķirību
        $platDelta = $platTo - $platFrom;
        $garDelta = $garTo - $garFrom;

        // Haversine formula
        $a = sin($platDelta / 2) * sin($platDelta / 2) +
             cos($platFrom) * cos($platTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance; // Return the distance
    }

    public function getDuration() {
        $averageSpeed = $this->aircraft->averageSpeed; // Vidējais ātrums lidmašīnai
        $distance = $this->getDistance(); // Attālums starp lidostām

        // Aprēķinām lidojuma ilgumu stundās
        $flightDurationHours = $distance / $averageSpeed; // stundās
        $flightDurationMinutes = $flightDurationHours * 60; // minūtēs

        // Pievienojam 30 minūtes sagatavošanai
        $totalDuration = $flightDurationMinutes + 30;

        return $totalDuration; // Atgriežam lidojuma ilgumu minūtēs
    }

    public function getLandingTime() {
        // Aprēķinām lidojuma ilgumu
        $durationInMinutes = $this->getDuration();

        // Izveidojam jaunu DateTime objektu, lai aprēķinātu nosēšanās laiku
        $landingTime = clone $this->departureTime;
        $landingTime->modify("+{$durationInMinutes} minutes"); // Pievienojam lidojuma ilgumu

        // Iegūstam laika joslu no API
        $latitude = $this->destination->latitude;
        $longitude = $this->destination->longitude;

        $timezoneApiUrl = "https://tu.proti.lv/timezones/?latitude={$latitude}&longitude={$longitude}";
        $timezoneData = json_decode(file_get_contents($timezoneApiUrl), true);

        if (isset($timezoneData['timezone'])) {
            $timezone = $timezoneData['timezone'];
            $landingTime->setTimezone(new DateTimeZone($timezone)); // Iestata laika joslu
        }

        return $landingTime; // Atgriežam nosēšanās laiku
    }

}