<?php

    namespace App\Services\FeeCalculator;

    use App\Contracts\FeeCalculatorInterface;

    class BaseFeeCalculator implements FeeCalculatorInterface
    {
        protected array $discountedCities = ['kyiv'];
        protected array $strategies;

        public function __construct(
            array $strategies
        ) {
            $this->strategies = $strategies;
        }

        public function calculate(string $destination, float $weight, string $deliveryType): float
        {
            $strategy = $this->strategies[$deliveryType] ?? null;

            if (!$strategy) {
                throw new \InvalidArgumentException("Unknown delivery type: $deliveryType");
            }

            $baseFee = $strategy->getBaseFee();
            $extraFee = $weight > 2 ? ($weight - 2) * 10 : 0;
            $total = $baseFee + $extraFee;

            if (in_array(strtolower($destination), $this->discountedCities)) {
                $total *= 0.9;
            }

            return round($total);
        }
    }
