<?php

namespace Belur\Http;

use Closure;

/**
 * Middleware interface.
 */
interface Middleware {

    /**
     * Handle the incoming request and return a response.
     *
     * @param Request $request
     * @param callable $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response;

}
