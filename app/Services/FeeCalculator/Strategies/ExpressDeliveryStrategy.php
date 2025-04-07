<?php

    namespace App\Services\FeeCalculator\Strategies;

    class ExpressDeliveryStrategy
    {
        public function getBaseFee(): float
        {
            return 100;
        }
    }
