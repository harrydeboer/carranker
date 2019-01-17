<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Hashing\HashManager;
use RuntimeException;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class WPHasher extends HashManager implements HasherContract
{
    /**
     * Default crypt cost factor.
     *
     * @var int
     */
    protected $rounds = 10;
    private $wphasher;

    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @param  array   $options
     * @return string
     *
     * @throws \RuntimeException
     */

    public function __construct($app)
    {
        parent::__construct($app);

        require_once base_path() . '/wordpress/wp/wp-includes/class-phpass.php';

        $this->wphasher = new \PasswordHash(8, true);
    }

    public function driver($driver = null)
    {
        return $this;
    }

    public function make($value, array $options = [])
    {
        $hash = $this->wphasher->HashPassword($value);

        if ($hash === false) {
            throw new RuntimeException('Bcrypt hashing not supported.');
        }

        return $hash;
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param  string  $value
     * @param  string  $hashedValue
     * @param  array   $options
     * @return bool
     */
    public function check($value, $hashedValue, array $options = [])
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }

        $hashedValueMade = $this->make($hashedValue);
        return $this->wphasher->CheckPassword($value, $hashedValue);
    }

    /**
     * Check if the given hash has been hashed using the given options.
     *
     * @param  string  $hashedValue
     * @param  array   $options
     * @return bool
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        return password_needs_rehash($hashedValue, PASSWORD_BCRYPT, [
            'cost' => $this->cost($options),
        ]);
    }

    /**
     * Set the default password work factor.
     *
     * @param  int  $rounds
     * @return $this
     */
    public function setRounds($rounds)
    {
        $this->rounds = (int) $rounds;

        return $this;
    }

    /**
     * Extract the cost value from the options array.
     *
     * @param  array  $options
     * @return int
     */
    protected function cost(array $options = [])
    {
        return $options['rounds'] ?? $this->rounds;
    }
}