<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

abstract class BaseValidator
{
    /**
     * @throws ValidationException
     */
    public function validate(Request $request): array
    {
        $data = $request->validate($this->rules());

        if (!is_null($request->get('reCaptchaToken')) && env('APP_ENV') !== 'testing') {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_POSTFIELDS, "secret=" . env('reCaptchaSecret') .
                           "&response=" . $request->get('reCaptchaToken'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $result = json_decode($response);
            if ($httpCode !== 200 || $result->success === false) {
                throw ValidationException::withMessages(['recaptcha' => 'No bot requests allowed.']);
            }

            curl_close($ch);
        }

        return $data;
    }

    abstract public function rules(): array;

    protected function profanitiesCheck(?string $string, Collection $profanities): bool
    {
        if (is_null($string)) {
            return true;
        }

        $string = strtolower($string);
        $stringWords = explode(' ', $string);
        foreach ($profanities as $profanity) {
            foreach ($stringWords as $word) {
                if ($word === $profanity->getName()) {
                    return false;
                }
            }
        }

        return true;
    }
}