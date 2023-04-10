<?php

namespace App\Console\Commands\Scout;

use Illuminate\Console\Command;

class ElasticSearchIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scout:elasticsearch:create {model}';

    protected $client;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an Elasticsearch Index';


    public function __construct()
    {
        parent::__construct();
        $this->client = app('elasticsearch');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!class_exists($model = $this->argument('model'))) {
            $this->error("{$model} could not be resolved");
        }
        $model = new $model;
        try {
            $this->client->indices( )->create([
                'index' => $model->searchableAs(),
                'body' => [
                    'settings' => [
                        'index' => [
                            'analysis' => [
                                'filter' => $this->filters(),
                                'analyser' =>$this->analyzers()
                            ]
                        ]
                    ]
                ]
            ]);
        } catch (Exception $e) {
            $this->error($e->getMessage( ));
        }
        }

    protected function filters()
    {
        return [
            "words_splitter" => [
                "type" => "word_delimiter",
                "preserve_original" => "true",
                "catenate_all" => "true"
            ]
        ];
    }

    protected function analyzers()
    {
        return [
            "default" => [
                "type" => "custom",
                "tokenizer" => "standard",
                "char_filter" => [
                    "html_strip"
                ],
                "filter" => [
                    "lowercase",
                    "words_splitter"
                ]
            ]
        ];
    }
}
