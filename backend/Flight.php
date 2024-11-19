<?php

class Flight {
    public function __construct(public $flightCode,
                                public $origin,
                                public $destination,
                                public $departureTime,
                                public $aircraft) {

    }
}