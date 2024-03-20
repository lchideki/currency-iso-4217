<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ICrawlCurrencyService;
use InvalidArgumentException;
use Spatie\Crawler\Crawler;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Illuminate\Support\Facades\Cache;
use App\Observers\CrawlCurrencyIso4217Observer;

class CurrencyController extends Controller
{
    protected $crawlCurrencyService;

    public function __construct(ICrawlCurrencyService $crawlCurrencyService)
    {
        $this->crawlCurrencyService = $crawlCurrencyService;
    }

    public function find(Request $request)
    {
        $filter = ['code' => $request->get('code'), 
            'number' => $request->get('number'), 
            'number_list' => $request->get('number_list'), 
            'code_list' => $request->get('code_list')];

        $keyFilter = json_encode($filter);
        $cachedResult = Cache::get($keyFilter);

        if ($cachedResult)
            return response()->json($cachedResult);

        try
        {
            $myCrawlObserver = new CrawlCurrencyIso4217Observer($this->crawlCurrencyService, $filter);

            $crawler = Crawler::create()
                ->setCrawlObserver($myCrawlObserver)
                ->setTotalCrawlLimit(1)
                ->startCrawling('https://pt.wikipedia.org/wiki/ISO_4217');

                $result = $myCrawlObserver->getResult();

            dd($result);

            echo 'oi';
        }
        catch (InvalidArgumentException $e)
        {
            return response()->json(['errors' => $e->getMessage()], 422);
        }

        return response()->json([]);
    }
}
