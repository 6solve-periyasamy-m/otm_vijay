<?php

namespace Tests\Unit\Test\Trait\Prefab;

use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\Prefab\BuildsQuote;

class BuildsQuoteTest extends DatabaseTestCase
{
    use BuildsQuote;

    public function testBuildBespokeQuote(): void
    {
        $this->validateQuote($this->buildBespokeQuote());
    }
}
