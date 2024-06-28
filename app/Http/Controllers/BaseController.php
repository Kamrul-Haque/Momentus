<?php

namespace App\Http\Controllers;

use BadMethodCallException;
use Closure;
use Illuminate\Routing\ControllerMiddlewareOptions;
use Symfony\Component\HttpFoundation\Response;

class BaseController
{
    /**
     * The middleware registered on the controller.
     *
     * @var array
     */
    protected array $middleware = [];

    /**
     * Register middleware on the controller.
     *
     * @param array|Closure|string $middleware
     * @param array $options
     * @return ControllerMiddlewareOptions
     */
    public function middleware(array|Closure|string $middleware, array $options = [])
    {
        foreach ((array)$middleware as $m)
        {
            $this->middleware[] = [
                'middleware' => $m,
                'options' => &$options,
            ];
        }

        return new ControllerMiddlewareOptions($options);
    }

    /**
     * Get the middleware assigned to the controller.
     *
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * Execute an action on the controller.
     *
     * @param string $method
     * @param array $parameters
     * @return Response
     */
    public function callAction(string $method, array $parameters)
    {
        return $this->{$method}(...array_values($parameters));
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call(string $method, array $parameters)
    {
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}
