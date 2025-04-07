<?php


    namespace Tests\Unit;

    use Tests\TestCase;
    use App\Services\FeeCalculator\BaseFeeCalculator;
    use App\Contracts\FeeCalculatorInterface;
    use App\Services\FeeCalculator\Strategies\ExpressDeliveryStrategy;
    use App\Services\FeeCalculator\Strategies\StandardDeliveryStrategy;

    class BaseFeeCalculatorTest extends TestCase
    {
        protected FeeCalculatorInterface $calculator;

        protected function setUp(): void
        {
            parent::setUp();

            $this->calculator = new BaseFeeCalculator([
                'standard' => new StandardDeliveryStrategy(),
                'express' => new ExpressDeliveryStrategy(),
            ]);
        }

        /** @test */
        public function it_calculates_standard_delivery_under_2kg_without_discount()
        {
            $fee = $this->calculator->calculate('odessa', 1.5, 'standard');
            $this->assertEquals(50, $fee);
        }

        /** @test */
        public function it_calculates_standard_delivery_over_2kg_without_discount()
        {
            $fee = $this->calculator->calculate('odessa', 3.5, 'standard');
            // base: 50, extra: (3.5 - 2) * 10 = 15, total = 65
            $this->assertEquals(65, $fee);
        }

        /** @test */
        public function it_applies_discount_for_kyiv_on_standard_delivery()
        {
            $fee = $this->calculator->calculate('kyiv', 3.5, 'standard');
            // base: 50, extra: 15 → total: 65 → 10% off = 58.5 → round = 59
            $this->assertEquals(59, $fee);
        }

        /** @test */
        public function it_calculates_express_delivery_under_2kg_without_discount()
        {
            $fee = $this->calculator->calculate('lviv', 1.5, 'express');
            $this->assertEquals(100, $fee);
        }

        /** @test */
        public function it_calculates_express_delivery_over_2kg_without_discount()
        {
            $fee = $this->calculator->calculate('lviv', 3.5, 'express');
            // base: 100, extra: 15, total = 115
            $this->assertEquals(115, $fee);
        }

        /** @test */
        public function it_applies_discount_for_kyiv_on_express_delivery()
        {
            $fee = $this->calculator->calculate('kyiv', 3.5, 'express');
            // base: 100, extra: 15 → 115 → -10% = 103.5 → round = 104
            $this->assertEquals(104, $fee);
        }

        /** @test */
        public function it_handles_weight_equal_to_2kg()
        {
            $fee = $this->calculator->calculate('kyiv', 2.0, 'standard');
            // base: 50, no extra, 10% discount = 45
            $this->assertEquals(45, $fee);
        }

        /** @test */
        public function it_handles_case_insensitive_city_discount()
        {
            $fee = $this->calculator->calculate('KyIv', 3.5, 'express');
            $this->assertEquals(104, $fee);
        }

        /** @test */
        public function it_returns_validation_error_when_required_fields_are_missing()
        {
            $response = $this->postJson('/api/calculate-delivery-fee', []);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['destination', 'weight', 'delivery_type']);
        }

        /** @test */
        public function it_returns_validation_error_for_invalid_delivery_type()
        {
            $payload = [
                'destination' => 'kyiv',
                'weight' => 2.5,
                'delivery_type' => 'super-fast' // invalid
            ];

            $response = $this->postJson('/api/calculate-delivery-fee', $payload);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['delivery_type']);
        }

        /** @test */
        public function it_returns_validation_error_for_negative_weight()
        {
            $payload = [
                'destination' => 'kyiv',
                'weight' => -5,
                'delivery_type' => 'express'
            ];

            $response = $this->postJson('/api/calculate-delivery-fee', $payload);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['weight']);
        }

        /** @test */
        public function it_returns_validation_error_for_non_numeric_weight()
        {
            $payload = [
                'destination' => 'kyiv',
                'weight' => 'heavy',
                'delivery_type' => 'standard'
            ];

            $response = $this->postJson('/api/calculate-delivery-fee', $payload);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['weight']);
        }
    }
