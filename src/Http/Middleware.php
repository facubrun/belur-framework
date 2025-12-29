<?php

namespace Belur\Http;

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
    public function handle(Request $request, callable $next): Response;

}
