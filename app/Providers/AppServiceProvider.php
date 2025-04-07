<?php

    namespace App\Providers;

    use App\Contracts\FeeCalculatorInterface;
    use App\Services\FeeCalculator\BaseFeeCalculator;
    use App\Services\FeeCalculator\Strategies\ExpressDeliveryStrategy;
    use App\Services\FeeCalculator\Strategies\StandardDeliveryStrategy;
    use Illuminate\Support\ServiceProvider;

    class AppServiceProvider extends ServiceProvider
    {
        /**
         * Register any application services.
         */
        public function register(): void
        {
            $this->app->bind(FeeCalculatorInterface::class, function () {
                return new BaseFeeCalculator([
                    'standard' => new StandardDeliveryStrategy(),
                    'express' => new ExpressDeliveryStrategy(),
                ]);
            });
        }

        /**
         * Bootstrap any application services.
         */
        public function boot(): void
        {
            //
        }
    }
