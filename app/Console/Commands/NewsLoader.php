<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services;

class NewsLoader extends Command {

    /** @var Services\NewsLoader\Facade */
    private $newsLoader;

    public function __construct(Services\NewsLoader\Facade $newsLoader) {
        parent::__construct();
        $this->newsLoader = $newsLoader;
    }

    /** @var string */
    protected $signature = 'com:news-loader';

    /** @var string  */
    protected $description = 'Load news from web resources and save to DB';

    public function handle() {
        $this->info('Start');
        $newsList = $this->newsLoader->initNewsHandler();
        $this->info('Loaded news: ' . count($newsList));
        $this->info('Success');
    }
}