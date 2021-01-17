<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Models\Aspects;
use App\Models\User;
use App\Repositories\Elastic\TrimRepository;
use App\Repositories\RatingRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Throwable;

class ModelPageTest extends DuskTestCase
{
    /**
     * @throws Throwable
     */
    public function testLoginAndRateCar(): void
    {
        $hasher = app()->make(Hasher::class);

        $trimRepository = app()->make(TrimRepository::class);
        $userRepository = app()->make(UserRepository::class);
        $ratingRepository = app()->make(RatingRepository::class);

        $password = 'secret';
        $user = User::factory()->create([
                                            'password' => $hasher->make($password),
                                            'email_verified_at' => now(),
                                        ]);
        $trim = $trimRepository->get(1);

        $this->browse(function (Browser $browser) use ($user, $trim) {
            $browser->visit(route('login'))
                ->type('email', $user->getEmail())
                ->type('password', 'secret')
                ->press('Login')
                ->assertPathIs('/');

            $browser->visit(route('modelPage', [
                'make' => $trim->getMakeName(),
                'model' => $trim->getModelName(),
                ]))
                ->click('#show-review-dialog');

            $browser->waitFor('#dialog');

            foreach (Aspects::getAspects() as $aspect) {
                $browser->radio('star[' . $aspect . ']', '8');
            }

            $generation = $trim->getYearBegin() . '-' . $trim->getYearEnd();
            $browser->select('#rating-form-generation', $generation);
            $browser->select('#rating-form-series', $generation . ';' . $trim->getFramework());
            $browser->type('content', 'test');

            $browser->click('#rating-form-submit');

            $browser->waitFor('#thank-you', 30);

            $this->assertEmpty($browser->driver->manage()->getLog('browser'));
        });

        foreach ($user->getRatings() as $rating) {
            $ratingRepository = $ratingRepository->delete($rating->getId());
        }

        $userRepository->delete($user->getId());
    }
}
