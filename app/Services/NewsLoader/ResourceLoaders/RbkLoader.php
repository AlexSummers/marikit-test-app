<?php

namespace App\Services\NewsLoader\ResourceLoaders;

use App\Services\NewsLoader\Entities;
use GuzzleHttp;
use Illuminate\Support\Facades\Log;

class RbkLoader extends AbstractResourceLoader {

    const URL = 'https://www.rbc.ru/v8/ajax/getnewsfeed/region/world/';
    const NEWS_LIMIT = 15;
    const ASYNC_REQUEST_COUNT = 5;

    /** @var GuzzleHttp\Client */
    private $httpClient;

    public function __construct() {
        $this->httpClient = new GuzzleHttp\Client();
    }

    /**
     * @return string
     */
    public function getResourceName(): string {
        return 'rbk';
    }

    /**
     * @return Entities\News[]
     */
    public function loadNews(): array {
        $newsListData = $this->getNewsUrls();
        return $this->loadFullNewsList($newsListData);
    }

    /**
     * @return array
     */
    private function getNewsUrls(): array {
        $result = [];
        $response = $this->sendRequest(self::URL);
        $decodedResponse = GuzzleHttp\json_decode($response, true);
        $items = array_get($decodedResponse, 'items', []);
        foreach ($items as $item) {
            if (count($result) >= self::NEWS_LIMIT) {
                break;
            }
            $htmlDom = str_get_html($item['html']);
            $fullNewsUrl = $this->findContentAtHtmlDom(str_get_html($htmlDom), 'a.news-feed__item', 'href');
            $isRbkNews = (bool)preg_match('#^http(s)?://www\.rbc\.ru/#', $fullNewsUrl);
            if (!$isRbkNews) {
                continue;
            }
            $result[] = [
                'id'              => $item['id'],
                'publicationDate' => new \DateTime("@{$item['time_t']}"),
                'fullNewsUrl'     => $fullNewsUrl,
            ];
        }
        return $result;
    }

    /**
     * @param string $url
     * @return string|null
     */
    private function sendRequest(string $url): ?string {
        $response = null;
        try {
            $response = $this->httpClient
                ->get($url)
                ->getBody()
                ->getContents();
        } catch (\Throwable $t) {
            Log::error($t);
        }
        return $response;
    }

    /**
     * @param \simple_html_dom $html
     * @param string $elementName
     * @param string $elementProperty
     * @return string|null
     */
    private function findContentAtHtmlDom(\simple_html_dom $html, string $elementName, string $elementProperty): ?string {
        foreach ($html->find($elementName) as $item) {
            return $item->$elementProperty;
        }
        Log::error('Element not found at html dom', [
            'html'            => (string)$html,
            'elementName'     => $elementName,
            'elementProperty' => $elementProperty,
        ]);
        return null;
    }

    /**
     * @param array $newsListData
     * @return Entities\News[]
     */
    private function loadFullNewsList(array $newsListData): array {
        $result = [];
        $promises = function(array $newsListData) {
            foreach ($newsListData as $newsData) {
                yield $this->httpClient->requestAsync('GET', $newsData['fullNewsUrl']);
            }
        };
        $pool = new GuzzleHttp\Promise\EachPromise($promises($newsListData), [
            'concurrency' => self::ASYNC_REQUEST_COUNT,
            'fulfilled'   => function(GuzzleHttp\Psr7\Response $response, $index) use (&$result, $newsListData) {
                $newsData     = $newsListData[$index];
                $html         = $response->getBody()->getContents();
                $htmlDom      = str_get_html($html);
                $title        = trim($this->findContentAtHtmlDom($htmlDom, 'div.article__header__title', 'plaintext'));
                $content      = trim($this->findContentAtHtmlDom($htmlDom, 'div.article__content', 'plaintext'));
                $mainImageUrl = $this->findContentAtHtmlDom($htmlDom, 'img.article__main-image__image', 'src');
                $result[]     = new Entities\News($newsData['id'], $title, $content, $newsData['publicationDate'], $mainImageUrl);
            },
            'rejected' => function($exception) {
                Log::error($exception);
            },
        ]);
        $pool->promise()->wait();
        return $result;
    }
}