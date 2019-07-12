<?php
/**
 * Created by PhpStorm.
 * User: ren
 * Date: 2019/7/11
 * Time: 8:40 PM
 */
declare(strict_types=1);

namespace V8\Log\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Utils\Context;
use Hyperf\Utils\Coroutine;

class LogMiddleware implements MiddlewareInterface
{
	/**
	 * @var ContainerInterface
	 */
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * @param ServerRequestInterface $request
	 * @param RequestHandlerInterface $handler
	 * @return ResponseInterface
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$this->getTraceId();

		return $handler->handle($request);
	}

	/**
	 * 获取traceId
	 *
	 * @return mixed|null
	 */
	private function getTraceId()
	{
		$root    = Context::get('tracer.root');
		$traceId = $root->getContext()->getTraceId();
		Context::set('tarce.id', $traceId);
	}
}