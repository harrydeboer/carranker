<?php

declare(strict_types=1);

namespace App\Forms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;


abstract class BaseForm extends Model
{
    use ValidatesRequests {
        validate as validateTrait;
    }

    public function validate(?string $token, Request $request)
    {
        $this->validateTrait($request, $this->rules(), [], []);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POSTFIELDS,"secret=" . env('reCaptchaSecret') . "&response=" . $token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $result = json_decode($response);
        curl_close($ch);

        return $result->success;
    }
}