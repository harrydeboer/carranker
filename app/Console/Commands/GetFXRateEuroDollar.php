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
    protected $signature = 'GetFXRate:get';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ch = curl_init("https://free.currencyconverterapi.com/api/v5/convert?q=EUR_USD&compact=y");

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($info === 200) {
            $jsonObj = json_decode((string) $data);
            $fxrate = $this->fXRateRepository->getByName('euro/dollar');
            $fxrate->setValue((float) $jsonObj->EUR_USD->val);
            $this->fXRateRepository->update($fxrate);
        }
    }
}
