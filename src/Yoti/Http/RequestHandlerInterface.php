<?php

namespace Yoti\Http;

/**
 * Handle HTTP requests.
 */
interface RequestHandlerInterface
{
    /**
     * Execute HTTP request.
     *
     * @param \Yoti\Http\Request $request
     *
     * @return \Yoti\Http\Response
     */
    public function execute(Request $request);
}
