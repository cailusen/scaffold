<?php
namespace Scaffold\Tools;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;


/**
 * Class Log
 * @package Scaffold\Tools
 * example Log::info($tag, array $data);
 */
class Log
{

	const DEFAULT_LOG_DIRECTORY = '/tmp/';

	const DEFAULT_LOG_SEPARATOR = '#';

	const LOGSETTING_SLICE_DAILY = 'daily';
	const LOGSETTING_SLICE_MONTH = 'monthly';



	private static $singleLogger = [];

	private static $logLevel = [
	    'debug' => Logger::DEBUG,
        'info' => Logger::INFO,
        'notice' => Logger::NOTICE,
        'warning' => Logger::WARNING,
        'error' => Logger::ERROR,
        'critical' => Logger::CRITICAL,
        'alter' => Logger::ALERT,
        'emergency' => Logger::EMERGENCY
    ];

	private static $logSetting = [
		'logSlice' => self::LOGSETTING_SLICE_DAILY,
	];




	public static function __callStatic($name, $arguments)
	{
		$debugTrace = debug_backtrace(0,1);
		list($logPath, $line) = self::getLogPath($debugTrace);

		self::addGeneralLog($logPath, $line, $name, $arguments);

	}

	private static function addGeneralLog($logPath, $line, $level, $arguments)
    {
        $level = strtolower($level);
        if (!isset(self::$singleLogger[$logPath])) {
            self::$singleLogger[$logPath] = new Logger($logPath);
            $output = "%datetime%".self::DEFAULT_LOG_SEPARATOR."%level_name%".self::DEFAULT_LOG_SEPARATOR."%message%".self::DEFAULT_LOG_SEPARATOR."%context%".self::DEFAULT_LOG_SEPARATOR."%extra%\n";
            $lineFormat = new LineFormatter($output);
            $stream = new StreamHandler(self::DEFAULT_LOG_DIRECTORY . $logPath, self::$logLevel[$level]);
            $stream -> setFormatter($lineFormat);
            $firePHP = new FirePHPHandler();
            self::$singleLogger[$logPath] -> pushHandler($stream);
            self::$singleLogger[$logPath] -> pushHandler($firePHP);
        }

        $tagMsg = $line . self::DEFAULT_LOG_SEPARATOR . array_shift($arguments);
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            array_push($arguments, $_SERVER['HTTP_USER_AGENT']);
        }
        self::$singleLogger[$logPath] -> $level($tagMsg, $arguments);
    }

	private static function getLogPath($debugTrace)
	{
		$logPath =  ltrim(str_replace([
			dirname(__DIR__),
			DIRECTORY_SEPARATOR,
			'.php'
		], [
			'',
			'-',
			''
		], $debugTrace[0]['file']), '-');

		return [$logPath . '-' . self::logSliceSuffix() , $debugTrace[0]['line']];

	}

	private static function logSliceSuffix()
    {
        $suffix = '';
        if (self::$logSetting['logSlice'] == self::LOGSETTING_SLICE_DAILY) {
            $suffix = date('Ymd');
        } else if (self::$logSetting['logSlice'] == self::LOGSETTING_SLICE_MONTH) {
            $suffix = date('Ym');
        }

        return $suffix . '.log';
    }

}