<?php

namespace Tests\Suites;

use App\Http\Requests\Request;
use Tests\TestCase;

/**
 * Class RequestTestSuite
 * @package Tests\Suites
 */
abstract class RequestTestSuite extends TestCase
{
    /**
     * @return Request
     */
    abstract public function getRequest(): Request;

    /**
     * @return array
     */
    protected function getRules(): array
    {
        return $this->getRequest()->rules();
    }
}
