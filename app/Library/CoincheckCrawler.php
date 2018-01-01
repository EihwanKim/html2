<?php
// app/Library/BaseClass.php
namespace App\Library;

use Goutte\Client as CrawlerClient;

class CoincheckCrawler
{

    public function getPrice ($coin_type) {
        $client = new CrawlerClient();
        $client->setHeader('User-Agent', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36');
        $crawler = $client->request('GET', 'https://coincheck.com/ja/sessions/signin?account_type=consumer');

        return $crawler->html();
    }
}