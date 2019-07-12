<?php
/**
 * Created by PhpStorm.
 * User: ren
 * Date: 2019/7/12
 * Time: 3:45 PM
 */
declare(strict_types=1);
use Hyperf\Utils\ApplicationContext;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
if (false === function_exists('di')) {
	/**
	 * Finds an entry of the container by its identifier and returns it.
	 * @param null|mixed $id
	 * @return mixed|\Psr\Container\ContainerInterface
	 */
	function di($id = null)
	{
		$container = ApplicationContext::getContainer();
		if ($id) {
			return $container->get($id);
		}
		return $container;
	}
}

if (false === function_exists('format_throwable')) {
	/**
	 * Format a throwable to string.
	 * @param Throwable $throwable
	 * @return string
	 */
	function format_throwable(Throwable $throwable): string
	{
		return di()->get(FormatterInterface::class)->format($throwable);
	}
}