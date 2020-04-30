<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Models;

class MailUser extends BaseModel
{
    protected static $table = 'mail_users';

    protected $id;
    protected $domain;
    protected $password;
    protected $email;
    protected $forward;

    public function getDomain(): string
    {
    	return $this->domain;
    }

	public function setDomain(string $domain): void
	{
		$this->domain = $domain;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function getForward(): string
	{
		return $this->forward;
	}

	public function setForward(string $forward): void
	{
		$this->forward = $forward;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	public static function all(): array
	{
		global $wpdb;

		$result = $wpdb->get_results("SELECT * FROM " . static::$table);

		$result = self::sanitize_result($result, $wpdb);

		$mailUsers = [];
		foreach ($result as $row) {
			$mailUsers[] = new MailUser($row);
		}

		return $mailUsers;
	}

	public static function findByEmail(string $email): ?MailUser
	{
		global $wpdb;

		$result = $wpdb->get_results("SELECT * FROM " . self::$table . " WHERE email='{$email}'");

		if ($result === []) {
			return null;
		}

		$result = self::sanitize_result($result, $wpdb);

		$className = static::class;

		return new $className($result[0]);
	}
}