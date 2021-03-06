<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Log;
use MeiliSearch\Client;

class UpdateSearch extends Command
{

    protected $signature = 'c:search';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {


        Log::channel('jobs')->info('Update Search Started');

        $meiliClient = resolve(Client::class);

        foreach ($meiliClient->getAllIndexes() as $index) {
            $index->deleteAllDocuments();
            $index->delete();
        }

        sleep(5);


        $meiliClient->index('books')->updateRankingRules(
            [
                "exactness",
                "sort:desc",
                "words",
                "typo",
                "proximity",
                "attribute",

            ]

        );

        $meiliClient->index('books')->updateSearchableAttributes(['name']);
        $meiliClient->index('books')->updateDisplayedAttributes(['id']);


        sleep(5);


        $this->call('scout:import', ['model' => "App\Models\BookUnique"]);


        Log::channel('jobs')->info('Update Search Finished');
    }
}
