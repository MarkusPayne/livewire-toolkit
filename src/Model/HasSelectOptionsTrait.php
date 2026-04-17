<?php

declare(strict_types=1);

namespace MarkusPayne\LivewireToolkit\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait HasSelectOptionsTrait
{
    /**
     * Get options for select dropdowns, keyed by $key with $value as the label.
     *
     * @param  array{
     *     key?: string,
     *     value?: string,
     *     sortBy?: string,
     *     filters?: list<array{field: string, type?: string, value: mixed, pivot?: string}>,
     *     activeOnly?: bool,
     *     cache?: bool,
     *     cacheDuration?: int,
     * }  $parameters
     * @return Collection<int|string, mixed>
     */
    public static function getOptions(array $parameters = []): Collection
    {
        $key = $parameters['key'] ?? 'id';
        $value = $parameters['value'] ?? 'name';
        $sortBy = $parameters['sortBy'] ?? $value;
        $filters = $parameters['filters'] ?? [];
        $activeOnly = $parameters['activeOnly'] ?? false;
        $useCache = $parameters['cache'] ?? true;
        $cacheDuration = $parameters['cacheDuration'] ?? 300;

        $cacheKey = static::buildCacheKey($key, $value, $sortBy, $filters, $activeOnly);

        $taggedCache = Cache::tags([static::getOptionsCacheTag()]);

        if ($useCache && $taggedCache->has($cacheKey)) {
            return collect($taggedCache->get($cacheKey));
        }

        $query = static::buildOptionsQuery($filters, $activeOnly, $sortBy);
        $options = $query->get()->unique($key)->pluck($value, $key);

        if ($useCache) {
            $taggedCache->put($cacheKey, $options->all(), $cacheDuration);
        }

        return $options;
    }

    public static function forgetOptionsCache(): void
    {
        Cache::tags([static::getOptionsCacheTag()])->flush();
    }

    protected static function getOptionsCacheTag(): string
    {
        return 'select_options.'.static::class;
    }

    protected static function bootHasSelectOptionsTrait(): void
    {
        static::saved(function (): void {
            static::forgetOptionsCache();
        });
    }

    /**
     * @param  list<array{field: string, type?: string, value: mixed, pivot?: string}>  $filters
     */
    protected static function buildOptionsQuery(array $filters, bool $activeOnly, string $sortBy): Builder
    {
        $query = static::query();

        if ($activeOnly) {
            $query->where('active', true);
        }

        foreach ($filters as $filter) {
            if (isset($filter['pivot'])) {
                $query->whereHas($filter['pivot'], function (Builder $q) use ($filter): void {
                    if (is_array($filter['value'])) {
                        $q->whereIn($filter['field'], $filter['value']);
                    } else {
                        $q->where($filter['field'], $filter['type'] ?? '=', $filter['value']);
                    }
                });
            } else {
                $query->where($filter['field'], $filter['type'] ?? '=', $filter['value']);
            }
        }

        return $query->orderBy($sortBy);
    }

    /**
     * @param  list<array{field: string, type?: string, value: mixed, pivot?: string}>  $filters
     */
    protected static function buildCacheKey(
        string $key,
        string $value,
        string $sortBy,
        array $filters,
        bool $activeOnly,
    ): string {
        return 'select_options.'.static::class.'.'.md5(serialize(compact(
            'key',
            'value',
            'sortBy',
            'filters',
            'activeOnly',
        )));
    }
}
