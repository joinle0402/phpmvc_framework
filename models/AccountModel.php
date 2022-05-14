<?php
namespace application\models;

use application\core\Model;

class AccountModel extends Model
{
    protected int $id;
    protected string $firstName = '';
    protected string $lastName = '';
    protected string $email = '';
    protected string $username = '';
    protected string $password = '';
    protected string $role = '';
    public const ROLE_USER = 'USER';
    public const ROLE_ADMINISTRATOR = 'ADMINISTRATOR';

	function tableName(): string
    {
        return 'accounts';
	}

	function primaryKey(): string
    {
        return 'id';
	}

	function attributes(): array
    {
        return ['id', 'firstName', 'lastName', 'username', 'password', 'email', 'role'];
	}

    public function register()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->role = empty($this->role) ? self::ROLE_USER : $this->role;
        return $this->save();
    }

    public function updateAccount()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return $this->update();
    }

    public function findAccountByUsername()
    {
        return $this->find([ 'username' => $this->username ]);
    }

    public function __construct(array $dataRequest = [])
    {
        parent::__construct($dataRequest);
    }

	/**
	 *
	 * @return string
	 */
	function getEmail(): string {
		return $this->email;
	}

	/**
	 *
	 * @param string $email
	 * @return AccountModel
	 */
	function setEmail(string $email): self {
		$this->email = $email;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	function getUsername(): string {
		return $this->username;
	}

	/**
	 *
	 * @param string $username
	 * @return AccountModel
	 */
	function setUsername(string $username): self {
		$this->username = $username;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	function getPassword(): string {
		return $this->password;
	}

	/**
	 *
	 * @param string $password
	 * @return AccountModel
	 */
	function setPassword(string $password): self {
		$this->password = $password;
		return $this;
	}
}

