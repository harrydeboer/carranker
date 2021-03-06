<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Translation\Translator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

abstract class AbstractValidator extends Validator
{
    public function __construct(
        array $data,
        array $messages = [],
        array $customAttributes = [],
    ) {
        parent::__construct(app()->make(Translator::class), $data, $this->rules(), $messages, $customAttributes);
    }

    public function validate(): array
    {
        $data = parent::validate();

        if (!isset($data['re-captcha-token']) && env('APP_ENV') !== 'testing') {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_POSTFIELDS, "secret=" . env('RE_CAPTCHA_SECRET') .
                           "&response=" . $data['re-captcha-token']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $result = json_decode($response);
            if ($httpCode !== 200 || $result->success === false) {
                throw ValidationException::withMessages(['reCAPTCHA' => 'No bot requests allowed.']);
            }

            curl_close($ch);
        }

        return $data;
    }

    abstract public function rules(): array;

    protected function profanitiesCheck(string $string, Collection $profanities): bool
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
