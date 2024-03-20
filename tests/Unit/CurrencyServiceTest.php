<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Mockery;
use App\Services\ICrawlCurrencyService;
use App\Services\CurrencyService;
use App\Services\ICacheService;
use App\Observers\ICrawlCurrencyIso4217Observer;
use Spatie\Crawler\Crawler;

class CurrencyServiceTest extends TestCase
{
    protected $crawlCurrencyServiceMock;
    protected $cacheServiceMock;
    protected $crawlCurrencyIso4217Observer;
    protected $crawlerMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->crawlCurrencyServiceMock = Mockery::mock(ICrawlCurrencyService::class);
        $this->cacheServiceMock = Mockery::mock(ICacheService::class);
        $this->crawlCurrencyIso4217Observer = Mockery::mock(ICrawlCurrencyIso4217Observer::class);
        $this->crawlerMock = Mockery::mock('overload:' . Crawler::class);
    }
    
    public function testFind(): void
    {
        $expectedData = [
            0 => [
                "code" => "ALL",
                "number" => "008",
                "decimal_digits" => 2,
                "currency" => "LEK",
                "locations" => [
                    0 => [
                        "icon" => "//upload.wikimedia.org/wikipedia/commons/thumb/3/36/Flag_of_Albania.svg/22px-Flag_of_Albania.svg.png",
                        "location" => "Albânia"
                    ]
                ]
           ]
        ];

        // Arrange
        $this->cacheServiceMock
            ->shouldReceive('get')
            ->andReturn($expectedData);
        
        $currencyService = new CurrencyService($this->crawlCurrencyServiceMock, $this->cacheServiceMock, $this->crawlCurrencyIso4217Observer);
        // End Arrange

        // Act
        $finded = $currencyService->find(['code' => 'ALL']);
        // End Act

        // Assert
        $this->assertEquals($expectedData, $finded);
        // End Assert
    }

    public function testFindCrawling(): void
    {
        $filter = ['code' => 'ALL'];

        $expectedData = [
            0 => [
                "code" => "ALL",
                "number" => "008",
                "decimal_digits" => 2,
                "currency" => "LEK",
                "locations" => [
                    0 => [
                        "icon" => "//upload.wikimedia.org/wikipedia/commons/thumb/3/36/Flag_of_Albania.svg/22px-Flag_of_Albania.svg.png",
                        "location" => "Albânia"
                    ]
                ]
           ]
        ];

        // Arrange
        $this->cacheServiceMock
            ->shouldReceive('get')
            ->andReturn(null);

        $this->cacheServiceMock
            ->shouldReceive('put')
            ->once();

        $this->crawlCurrencyIso4217Observer
            ->shouldReceive('setFilter')
            ->andReturn($filter);

        $this->crawlerMock
            ->shouldReceive('create')
            ->andReturnSelf(); 

        $this->crawlerMock
            ->shouldReceive('setCrawlObserver')
            ->with($this->crawlCurrencyIso4217Observer)
            ->andReturnSelf(); 

        $this->crawlerMock
            ->shouldReceive('setTotalCrawlLimit')
            ->with(1)
            ->andReturnSelf();

        $this->crawlerMock
            ->shouldReceive('startCrawling')
            ->with('https://pt.wikipedia.org/wiki/ISO_4217')
            ->andReturn();
        
        $this->crawlCurrencyIso4217Observer
            ->shouldReceive('getResult')
            ->andReturn($expectedData);

        $currencyService = new CurrencyService($this->crawlCurrencyServiceMock, $this->cacheServiceMock, $this->crawlCurrencyIso4217Observer);
        // End Arrange

        // Act
        $finded = $currencyService->findCrawling($filter);
        // End Act

        // Assert
        $this->assertEquals($expectedData, $finded);
        // End Assert
    }

    public function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }
}
