<?php

    namespace App\Contracts;

    interface FeeCalculatorInterface
    {
        public function calculate(string $destination, float $weight, string $deliveryType): float;
    }
