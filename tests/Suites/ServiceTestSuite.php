<?php

namespace Tests\Suites;

use Tests\TestCase;

/**
 * Class ServiceTestSuite
 * @package Tests\Suites
 */
abstract class ServiceTestSuite extends TestCase
{
    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setService();
    }

    /**
     * @return void
     */
    abstract public function setService(): void;
}
