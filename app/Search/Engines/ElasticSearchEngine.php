<?php

namespace App\Search\Engines;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;

class ElasticSearchEngine extends Engine
{
    protected $client;

    public function __construct(\Elasticsearch\Client $client)
    {
        $this->client = $client;
    }

    /**
     * Update the given model in the index.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $models
     * @return void
     */
    public function update($models)
    {
        $models->each(function ($modal) {
            $params = [
                'index' => $modal->searchableAs(),
                'type' => $modal->searchableAs(),
                'id' => $modal->id,
                'body' => $modal->toSearchableArray()
            ];

            $this->client->index($params);
        });
    }

    /**
     * Remove the given model from the index.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $models
     * @return void
     */
    public function delete($models)
    {
        $models->each(function ($modal) {
            $params = [
                'index' => $modal->searchableAs(),
                'type' => $modal->searchableAs(),
                'id' => $modal->id
            ];

            $this->client->delete($params);
        });
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \Laravel\Scout\Builder  $builder
     * @return mixed
     */
    public function search(Builder $builder)
    {
        return $this->performSearch($builder);
    }


    protected function performSearch(Builder $builder, array $options = [])
    {
        $params = array_merge_recursive([
            'index' => $builder->model->searchableAs(),
            'type' => $builder->model->searchableAs(),
            'body' => [
                'from' => 0,
                'size' => 500,
                'query' => [
                    'multi_match' => [
                        'query' => $builder->query ?? '',
                        'fields' => $this->getSearchableFields($builder->model),
                        'type' => 'phrase_prefix'
                    ]
                ]
            ]
        ], $options);

        return $this->client->search($params );
    }

    protected function getSearchableFields($model)
    {
        if (!method_exists($model, 'searchableFields')) {
            return [];
        }

        return $model->searchableFields();
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \Laravel\Scout\Builder  $builder
     * @param  int  $perPage
     * @param  int  $page
     * @return mixed
     */
    public function paginate(Builder $builder, $perPage, $page)
    {
        return $this->performSearch($builder, [
            'from' => ($page - 1) * $perPage,
            'size' =>10
        ]);
    }

    /**
     * Pluck and return the primary keys of the given results.
     *
     * @param  mixed  $results
     * @return \Illuminate\Support\Collection
     */
    public function mapIds($results)
    {
        return collect(Arr::get($results, 'hits.hits'))->pluck('_id')->values();
    }

    /**
     * Map the given results to instances of the given model.
     *
     * @param  \Laravel\Scout\Builder  $builder
     * @param  mixed  $results
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function map(Builder $builder, $results, $model)
    {
        if (count($hits = Arr::get($results, 'hits.hits')) === 0) {
            return $model->newCollection();
        }

        return $model->getScoutModelsByIds($builder, collect($hits)->pluck('_id')->values()->all());
    }

    /**
     * Map the given results to instances of the given model via a lazy collection.
     *
     * @param  \Laravel\Scout\Builder  $builder
     * @param  mixed  $results
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Support\LazyCollection
     */
    public function lazyMap(Builder $builder, $results, $model)
    {
    }

    /**
     * Get the total count from a raw result returned by the engine.
     *
     * @param  mixed  $results
     * @return int
     */
    public function getTotalCount($results)
    {
         return Arr::get($results, 'hits.total.value', 0);
    }

    /**
     * Flush all of the model's records from the engine.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function flush($model)
    {
        $this->client->indices()->delete([
            'index' => $model->searchableAs()
        ]);

        Artisan::call('scout:elasticsearch:create', [
            'model' => get_class($model)
        ]);
    }

    /**
     * Create a search index.
     *
     * @param  string  $name
     * @param  array  $options
     * @return mixed
     */
    public function createIndex($name, array $options = []) {

    }

    /**
     * Delete a search index.
     *
     * @param  string  $name
     * @return mixed
     */
    public function deleteIndex($name)
    {
    }
}

