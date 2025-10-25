<?php

namespace App\Console\Commands;

use App\Enums\ArticleApiSource;
use App\Jobs\FetchArticlesFromSourceJob;
use Illuminate\Console\Command;

class FetchArticlesFromSourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-articles-from-source {source?* : The source(s) to fetch articles from}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch articles from a source';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $apiSources = [];

        $source = $this->argument('source');

        if ($source) {
            foreach ($source as $s) {
                $apiSource = ArticleApiSource::tryFrom($s);
                if (!$apiSource) {
                    $this->error('Invalid source: ' . $s);
                    return Command::FAILURE;
                }
                $apiSources[] = $apiSource;
            }
        } else {
            $apiSources = ArticleApiSource::cases();
        }

        foreach ($apiSources as $apiSource) {
            dispatch(new FetchArticlesFromSourceJob($apiSource));
        }

        return Command::SUCCESS;
    }
}
