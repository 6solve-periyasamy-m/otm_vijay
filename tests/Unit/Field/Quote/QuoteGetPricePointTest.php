<?php

namespace Field\Quote;

use Tests\DatabaseTestCase;
use Tests\Traits\TestsQuote;

/**
 * @covers \App\Repository\Model\Quote\QuoteRepository::getPricePerPerson
 * @covers \App\Repository\Model\Quote\QuoteRepository::addPricePoint
 */
class QuoteGetPricePointTest extends DatabaseTestCase
{
    use TestsQuote;

    public function testGetWithOne()
    {
        $quote = $this->generateQuote();
        $quote->repository->addPricePoint(1, 100);
        $this->assertEquals(100, $quote->repository->getPricePerPerson(1)->price_per_person);
        $this->assertEquals(100, $quote->repository->getPricePerPerson(10)->price_per_person);
        $this->assertEquals(100, $quote->repository->getPricePerPerson(100)->price_per_person);
        $this->assertEquals(0, $quote->repository->getPricePerPerson(0)->price_per_person);
    }

    public function testGetWithTwo()
    {
        $quote = $this->generateQuote();
        $quote->repository->addPricePoint(1, 100);
        $quote->repository->addPricePoint(10, 80);
        $this->assertEquals(100, $quote->repository->getPricePerPerson(1)->price_per_person);
        $this->assertEquals(100, $quote->repository->getPricePerPerson(9)->price_per_person);
        $this->assertEquals(80, $quote->repository->getPricePerPerson(10)->price_per_person);
        $this->assertEquals(80, $quote->repository->getPricePerPerson(100)->price_per_person);
        $this->assertEquals(0, $quote->repository->getPricePerPerson(0)->price_per_person);
    }

    public function testGetWithThree()
    {
        $quote = $this->generateQuote();
        $quote->repository->addPricePoint(1, 100);
        $quote->repository->addPricePoint(10, 80);
        $quote->repository->addPricePoint(100, 50);
        $this->assertEquals(100, $quote->repository->getPricePerPerson(1)->price_per_person);
        $this->assertEquals(100, $quote->repository->getPricePerPerson(9)->price_per_person);
        $this->assertEquals(80, $quote->repository->getPricePerPerson(10)->price_per_person);
        $this->assertEquals(80, $quote->repository->getPricePerPerson(99)->price_per_person);
        $this->assertEquals(50, $quote->repository->getPricePerPerson(100)->price_per_person);
        $this->assertEquals(50, $quote->repository->getPricePerPerson(1000)->price_per_person);
        $this->assertEquals(0, $quote->repository->getPricePerPerson(0)->price_per_person);
    }
}
