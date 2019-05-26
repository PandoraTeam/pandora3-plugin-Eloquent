<?php
namespace Pandora3\Plugins\Eloquent;

use Illuminate\Database\Capsule\Manager as EloquentManager;
use Illuminate\Database\Schema\Builder as SchemaBuilder;
use Illuminate\Database\DatabaseManager;
use Pandora3\Core\Interfaces\DatabaseConnectionInterface;

/**
 * Class EloquentConnection
 * @package Pandora3\Plugins\Eloquent
 */
class EloquentConnection implements DatabaseConnectionInterface {
	
	/** @var array $params */
	protected $params;

	/** @var EloquentManager $eloquent */
	protected $eloquent;

	/** @var DatabaseManager $database */
	protected $database;
	
	/**
	 * @param array $params
	 */
	public function __construct(array $params) {
		$params['charset'] = $params['charset'] ?? 'utf8';
		$params['collation'] = $params['collation'] ?? 'utf8_unicode_ci';
		$params['prefix'] = $params['prefix'] ?? '';

		$this->params = $params;
		// $eloquent = self::getManager();
		$connectionName = $params['connectionName'] ?? 'default';
		$this->eloquent = new EloquentManager;
		$this->eloquent->addConnection($params /* [
			'driver' => $params['driver'],
			'host' => $params['host'],
			'database' => $params['database'],
			'username' => $params['username'],
			'password' => $params['password'],
			'charset' => $params['encoding'] ?? 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => $params['prefix'] ?? '',
			'strict' => false,
		]*/, $connectionName);
		if ($params['global'] ?? false) {
			$this->setAsGlobal();
		}
		$this->connect();
	}

	/**
	 * @return SchemaBuilder
	 */
	public function getSchemaBuilder(): SchemaBuilder {
		return $this->database->getSchemaBuilder();
	}
	
	/**
	 * @return EloquentManager
	 */
	public function getManager(): EloquentManager {
		return $this->eloquent;
	}

	public function setAsGlobal(): void {
		$this->eloquent->setAsGlobal();
	}
	
	public function connect(): void {
		$this->eloquent->bootEloquent();
		$this->database = $this->eloquent->getDatabaseManager();
	}
	
	public function close(): void {
		$this->database->disconnect();
	}
	
}