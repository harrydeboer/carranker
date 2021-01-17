<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\FXRateRepository;
use Illuminate\Console\Command;
use Exception;

class GetFXRateEuroDollar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:fx-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get FX Rate Euro/Dollar';

    public function __construct(
        private FXRateRepository $fXRateRepository,
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $ch = curl_init("http://data.fixer.io/api/latest?access_key=" . env("FIXER_API_KEY"));

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($info === 200) {
            $jsonObj = json_decode((string) $data);
            $fXRate = $this->fXRateRepository->getByName('euro/dollar');
            if (is_null($fXRate)) {
                $this->fXRateRepository->create([
                                                    'name' => 'euro/dollar',
                                                    'value' => (float) $jsonObj->rates->USD,
                                                ]);
            } else {
                $fXRate->setValue((float) $jsonObj->rates->USD);
                $fXRate->save();
            }

            $this->info('FX Rate updated!');

        } else {
            throw new Exception("Api for FX Rates not available.");
        }
    }
}
