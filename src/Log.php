<?php
declare(strict_types=1);

namespace V8\Log;

use Hyperf\Logger\Logger;
use Hyperf\Utils\Context;
use Hyperf\Utils\Coroutine;

/**
 * @method static Logger get($name)
 * @method static void log($level, $message, array $context = [])
 * @method static void emergency($message, array $context = [])
 * @method static void alert($message, array $context = [])
 * @method static void critical($message, array $context = [])
 * @method static void error($message, array $context = [])
 * @method static void warning($message, array $context = [])
 * @method static void notice($message, array $context = [])
 * @method static void info($message, array $context = [])
 * @method static void debug($message, array $context = [])
 */
class Log
{
	public static function __callStatic($name, $arguments)
	{
		$factory = di(\Hyperf\Logger\LoggerFactory::class);
		if ($name === 'get') {
			return $factory->get(...$arguments);
		}
		$log = $factory->get('default');
		$log->pushProcessor(function ($record) {
			$record['extra']['host'] = gethostname();

			// 只能适应两层协程的情况，再深目前没辙
			$pid = Coroutine::parentId();
			if ($pid > 0) {
				$record['extra']['traceid'] = Context::get('tarce.id', null, $pid);
			} else {
				$record['extra']['traceid'] = Context::get('tarce.id');
			}

			return $record;
		});

		$log->$name(...$arguments);
	}
}