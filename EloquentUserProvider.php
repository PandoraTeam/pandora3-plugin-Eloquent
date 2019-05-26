<?php
namespace Pandora3\Plugins\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Pandora3\Plugins\Authorisation\Interfaces\AuthorisableInterface;
use Pandora3\Plugins\Authorisation\Interfaces\UserProviderInterface;

/**
 * Class EloquentUserProvider
 * @package Pandora3\Plugins\Eloquent
 */
class EloquentUserProvider implements UserProviderInterface {

	/** @var string $userModel */
	protected $userModel;
	
	/** @var mixed|null $guestUser */
	protected $guestUser;
	
	/**
	 * @param string $userModel
	 * @param mixed $guestUser
	 */
	public function __construct(string $userModel, $guestUser = null) {
		$this->userModel = $userModel;
		$this->guestUser = $guestUser;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getGuestUser() {
		return $this->guestUser;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUserById($id): ?AuthorisableInterface {
		/** @var Model $userModel */
		$userModel = $this->userModel;

		/** @var AuthorisableInterface $user */
		$user = $userModel::find($id);
		return $user;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUserByLogin(string $login): ?AuthorisableInterface {
		/** @var Model $userModel */
		$userModel = $this->userModel;

		/** @var AuthorisableInterface $user */
		$user = $userModel::where(['login' => $login])->first();
		return $user;
	}

}