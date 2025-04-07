<?php

    namespace App\Services\FeeCalculator\Strategies;

    class StandardDeliveryStrategy
    {
        public function getBaseFee(): float
        {
            return 50;
        }
    }
