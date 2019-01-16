<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\FXRateRepository;
use Illuminate\Console\Command;

class GetFXRateEuroDollar extends Command
{
    private $fXRateRepository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getfxrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get FXRate Euro/Dollar';

    public function __construct()
    {
        parent::__construct();
        $this->fXRateRepository = new FXRateRepository();
    }

    public function handle()
    {
        $ch = curl_init("http://data.fixer.io/api/latest?access_key=" . env("FIXER_API_KEY"));

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($info === 200) {
            $jsonObj = json_decode((string) $data);
            $fxrate = $this->fXRateRepository->getByName('euro/dollar');
            if (is_null($fxrate)) {
                $this->fXRateRepository->create(['name' => 'euro/dollar', 'value' => (float) $jsonObj->rates->USD]);
            } else {
                $fxrate->setValue((float) $jsonObj->rates->USD);
                $this->fXRateRepository->update($fxrate);
            }

        } else {
            throw new \Exception("Api for fxrates not available.");
        }
    }
}
