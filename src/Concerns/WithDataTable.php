<?php

declare(strict_types=1);

namespace MarkusPayne\LivewireToolkit\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

/**
 * @property string $tableName
 */
trait WithDataTable
{
    use WithoutUrlPagination, WithPagination;

    public ?int $perPage = 10;

    public string $sortBy = 'id';

    public string $sortDir = 'DESC';

    public array $perPageOptions = [5, 10, 25, 50, 100];

    abstract public function tableQuery(): Builder;

    /** @return array{sortBy?: string, sortDir?: string, perPage?: int} */
    protected function tableDefaults(): array
    {
        return [];
    }

    public function mountWithDataTable(): void
    {
        if ($this->tableName === '') {
            throw new \LogicException('Components using WithDataTable must declare a public string $tableName property.');
        }

        $defaults = $this->tableDefaults();

        $this->sortBy = Cache::get(
            $this->tablePreferenceKey('sortBy'),
            $defaults['sortBy'] ?? $this->sortBy,
        );

        $this->sortDir = Cache::get(
            $this->tablePreferenceKey('sortDir'),
            $defaults['sortDir'] ?? $this->sortDir,
        );

        $this->perPage = (int) Cache::get(
            $this->tablePreferenceKey('perPage'),
            $defaults['perPage'] ?? $this->perPage,
        );
    }

    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return $this->tableQuery()
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }

    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'ASC';
        }

        $this->persistTablePreference('sortBy', $this->sortBy);
        $this->persistTablePreference('sortDir', $this->sortDir);
        $this->resetPage();
        $this->refreshRows();
    }

    public function updatedSortBy(): void
    {
        $this->persistTablePreference('sortBy', $this->sortBy);
        $this->resetPage();
        $this->refreshRows();
    }

    public function updatedSortDir(): void
    {
        $this->persistTablePreference('sortDir', $this->sortDir);
        $this->resetPage();
        $this->refreshRows();
    }

    public function updatedPerPage(): void
    {
        $this->perPage = (int) $this->perPage;
        $this->persistTablePreference('perPage', (int) $this->perPage);
        $this->resetPage();
        $this->refreshRows();
    }

    public function refreshRows(): void
    {
        unset($this->rows);
    }

    protected function tablePreferenceKey(string $setting): string
    {
        return 'table.'.$this->tableName.'.'.auth()->id().'.'.$setting;
    }

    protected function persistTablePreference(string $key, string|int|null $value): void
    {
        Cache::put($this->tablePreferenceKey($key), $value, now()->addWeek());
    }
}
