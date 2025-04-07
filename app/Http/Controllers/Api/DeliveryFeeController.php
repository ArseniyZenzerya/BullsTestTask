<?php

    namespace App\Http\Controllers\Api;

    use App\Contracts\FeeCalculatorInterface;
    use App\Http\Requests\CalculateFeeRequest;

    class DeliveryFeeController
    {

        public function calculate(CalculateFeeRequest $request, FeeCalculatorInterface $calculator)
        {
            $fee = $calculator->calculate(
                $request->input('destination'),
                $request->input('weight'),
                $request->input('delivery_type')
            );

            return response()->json(['fee' => $fee]);
        }

    }
