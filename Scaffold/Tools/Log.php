<?php
namespace Scaffold\Tools;

use Monolog\Logger;


/**
 * Class Log
 * @package Scaffold\Tools
 * Log::info($tag, array $data);
 */
class Log
{

	const DEFAULT_LOG_DIRECTORY = '/tmp/';

	const DEFAULT_LOG_SEPARATOR = '#';

	const LOGSETTING_SLICE_DAILY = 'daily';
	const LOGSETTING_SLICE_MONTH = 'monthly';

	private static $logSetting = [
		'logSlice' => self::LOGSETTING_SLICE_DAILY,
	];
	public static function __callStatic($name, $arguments)
	{
		$debugTrace = debug_backtrace(0,1);
		self::getLogPath($debugTrace);
	}

	private static function getLogPath($debugTrace)
	{
		$logPath = str_replace([
			dirname(__DIR__),
			DIRECTORY_SEPARATOR,
			'.php'
		], [
			'',
			'-',
			''
		], $debugTrace[0]['file']);

	}

}