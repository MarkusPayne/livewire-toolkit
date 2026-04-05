<?php

declare(strict_types=1);

namespace MarkusPayne\LivewireToolkit\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;

trait WithSearch
{
    /** @var array<string, string> Field-to-filter-type map (e.g. ['name' => 'like', 'status' => '=']) */
    public array $filters = [];

    /** @var array<string, mixed> Current search values keyed by field */
    #[Url]
    public array $search = [];

    /** @var array<string, string> Field-to-pivot-relation map for pivot filters */
    public array $pivotTables = [];

    /** @var array<string, string> Field-to-"relation:column" map for hasIn filters */
    public array $hasRelations = [];

    public string $tableSearchString = '';

    /** @var list<string> Fields to search with the global tableSearchString */
    public array $tableSearchFields = [];

    public function clearSearch(): void
    {
        $this->search = [];
        $this->tableSearchString = '';
    }

    /**
     * Apply all configured filters and global search to a query builder.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    public function applySearch(Builder $query): Builder
    {
        $this->normalizeArrayFilters();

        foreach (Arr::dot($this->search) as $field => $value) {
            if (trim((string) $value) === '') {
                $this->forgetSearchField($field);

                continue;
            }

            $filterType = $this->filters[$field] ?? '=';

            match ($filterType) {
                'like', 'text' => $this->applyLikeFilter($query, $field, $value),
                'match' => $this->applyFullTextMatch($query, $field, $value),
                'in' => $this->applyInFilter($query, $field, $value),
                'hasIn' => $this->applyHasInFilter($query, $field, $value),
                'pivot' => $this->applyPivotFilter($query, $field, $value),
                'range' => $this->applyRangeFilter($query, $field, $value),
                default => $this->applyDefaultFilter($query, $field, $value, $filterType),
            };
        }

        return $this->applyGlobalSearch($query);
    }

    /**
     * Convert array values for 'in' and 'hasIn' filters to comma-separated strings,
     * or remove the search entry if the array is empty.
     */
    private function normalizeArrayFilters(): void
    {
        foreach ($this->filters as $key => $filterType) {
            if ($filterType !== 'in' && $filterType !== 'hasIn') {
                continue;
            }

            $value = Arr::get($this->search, $key);

            if (! is_array($value)) {
                continue;
            }

            if (count($value) > 0) {
                Arr::set($this->search, $key, implode(',', $value));
            } else {
                $this->forgetSearchField($key);
            }
        }
    }

    private function forgetSearchField(string $field): void
    {
        Arr::forget($this->search, $field);

        $parentKey = Str::beforeLast($field, '.');

        if ($parentKey !== $field && empty(Arr::get($this->search, $parentKey))) {
            Arr::forget($this->search, $parentKey);
        }
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     */
    private function applyLikeFilter(Builder $query, string $field, string $value): void
    {
        $terms = Str::of($value)->trim()->explode(' ')->unique();

        foreach ($terms as $term) {
            if (Str::of($term)->trim()->isNotEmpty()) {
                $query->where($field, 'like', '%'.$term.'%');
            }
        }
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     */
    private function applyFullTextMatch(Builder $query, string $field, string $value): void
    {
        $words = Str::of($value)
            ->replaceMatches('/[^a-zA-Z0-9\s&]/', ' ')
            ->replaceMatches('/&/', '\&')
            ->explode(' ')
            ->filter()
            ->unique();

        $shortWords = $words->filter(
            fn (string $word): bool => Str::length($word) <= 2 || Str::contains($word, '&')
        )->values();

        $longWords = $words->reject(
            fn (string $word): bool => Str::contains($word, '&')
        )->filter(
            fn (string $word): bool => Str::length($word) > 2
        )->values();

        $matchTerm = $longWords
            ->map(fn (string $word): string => '+'.trim($word).'*')
            ->join(' ');

        if (Str::of($matchTerm)->trim()->isNotEmpty()) {
            $query->whereFullText($field, $matchTerm, ['mode' => 'boolean']);
        }

        foreach ($shortWords as $shortWord) {
            if (Str::of($shortWord)->trim()->isNotEmpty()) {
                $query->whereLike($field, '%'.$shortWord.'%');
            }
        }
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     */
    private function applyInFilter(Builder $query, string $field, string $value): void
    {
        $arr = explode(',', $value);
        $query->whereIn($field, $arr);
        Arr::set($this->search, $field, $arr);
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     */
    private function applyHasInFilter(Builder $query, string $field, string $value): void
    {
        $arr = explode(',', $value);
        [$relation, $column] = explode(':', $this->hasRelations[$field]);
        $query->whereHas($relation, fn (Builder $q): Builder => $q->whereIn($column, $arr));
        Arr::set($this->search, $field, $arr);
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     */
    private function applyPivotFilter(Builder $query, string $field, string $value): void
    {
        $query->whereHas(
            $this->pivotTables[$field],
            fn (Builder $q): Builder => $q->where($field, $value)
        );
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     */
    private function applyRangeFilter(Builder $query, string $field, string $value): void
    {
        $range = Str::of($value)->trim()->explode(' to ');

        if (count($range) === 2) {
            $query->whereBetween($field, [$range[0], $range[1]]);
        } else {
            $query->where($field, $range[0]);
        }
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     */
    private function applyDefaultFilter(Builder $query, string $field, string $value, string $operator): void
    {
        if (Str::lower($value) === 'null') {
            $query->whereNull($field);
        } else {
            $query->where($field, $operator, $value);
        }
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    private function applyGlobalSearch(Builder $query): Builder
    {
        if ($this->tableSearchString === '' || $this->tableSearchFields === []) {
            return $query;
        }

        return $query->where(function (Builder $query): void {
            foreach ($this->tableSearchFields as $field) {
                $terms = Str::of($this->tableSearchString)->trim()->explode(' ');

                foreach ($terms as $term) {
                    if (Str::of($term)->trim()->isNotEmpty()) {
                        $query->orWhere($field, 'like', '%'.$term.'%');
                    }
                }
            }
        });
    }
}
