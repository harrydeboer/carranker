<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Models\MySQL\AspectsTrait;
use App\Models\MySQL\User;
use App\Repositories\Interfaces\RatingRepositoryInterface;
use App\Repositories\Interfaces\TrimReadRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
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

        $trimRepository = app()->make(TrimReadRepositoryInterface::class);
        $userRepository = app()->make(UserRepositoryInterface::class);
        $ratingRepository = app()->make(RatingRepositoryInterface::class);

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

            foreach (AspectsTrait::getAspects() as $aspect) {
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
