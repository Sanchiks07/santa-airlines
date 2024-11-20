<?php

include_once "Airport.php";
include_once "Aircraft.php";

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
             sin($garDelta / 2) * sin($garDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }

    public function getDuration() {
        $vidAtrums = $this->aircraft->vidAtrums; // Vidējais ātrums lidmašīnai
        $distance = $this->getDistance(); // Attālums starp lidostām

        // Aprēķinām lidojuma ilgumu stundās
        $flightDurationHours = $distance / $vidAtrums; // stundās
        $flightDurationMinutes = $flightDurationHours * 60; // minūtēs

        // Pievienojam 30 minūtes sagatavošanai
        $totalDuration = $flightDurationMinutes + 30;

        return $totalDuration; // Atgriežam lidojuma ilgumu minūtēs
    }

    public function getLandingTime() {
        $durationInMinutes = $this->getDuration();

        // Izveidojam jaunu DateTime objektu, lai aprēķinātu nosēšanās laiku
        $landingTime = clone $this->departureTime;
        $landingTime->modify("+{$durationInMinutes} minutes"); // Pievienojam lidojuma ilgumu

        // Iegūstam laika joslu no API
        $platums = $this->destination->platums;
        $garums = $this->destination->garums;

        $timezoneApiUrl = "https://tu.proti.lv/timezones/?latitude={$platums}&longitude={$garums}";
        $timezoneData = json_decode(file_get_contents($timezoneApiUrl), true);

        if (isset($timezoneData['timezone'])) {
            $timezone = $timezoneData['timezone'];
            $landingTime->setTimezone(new DateTimeZone($timezone)); // Iestata laika joslu
        }

        return $landingTime;
    }

}

?>