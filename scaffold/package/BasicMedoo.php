<?php
namespace Scaffold\package;

use Medoo\Medoo;

class BasicMedoo
{

	const _TABLE_NAME = '';

	const CONF_DATABASE_TYPE = 'mysql';
	const CONF_DATABASE_NAME = '';
	const CONF_SERVER = '';
	const CONF_USERNAME = '';
	const CONF_PASSWORD = '';
	private static $instance = [];

	private function __construct()
	{
	}

	private static function getInstance()
	{
		$config = [
			'database_type' => self::CONF_DATABASE_TYPE,
			'database_name' => static::CONF_DATABASE_NAME,
			'server' => self::CONF_SERVER,
			'username' => self::CONF_USERNAME,
			'password' => self::CONF_PASSWORD
		];
		$singleKey = md5(json_encode($config, JSON_UNESCAPED_UNICODE));
		if (!isset(self::$instance[$singleKey])) {
			self::$instance[$singleKey] = new Medoo($config);
		}
		return self::$instance[$singleKey];
	}

	public static function __callStatic($name, $arguments)
	{
		$medoo = self::getInstance();

		return call_user_func_array([
			$medoo,
			strtolower($name)], array_merge([static::_TABLE_NAME], $arguments)
		);
	}
}