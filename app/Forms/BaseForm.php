<?php

declare(strict_types=1);

namespace App\Forms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

abstract class BaseForm extends Model
{
    use ValidatesRequests;

    public function validateFull(Request $request, string $token=null): bool
    {
        try {
            $this->validate($request, $this->rules(), [], []);
        } catch (ValidationException $exception) {
            return false;
        }

        if (!is_null($token) && env('APP_ENV') !== 'testing') {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_POSTFIELDS, "secret=" . env('reCaptchaSecret') . "&response=" . $token);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $result = json_decode($response);
            curl_close($ch);

            return $result->success;
        }

        return true;
    }

    abstract public function rules(): array;
}