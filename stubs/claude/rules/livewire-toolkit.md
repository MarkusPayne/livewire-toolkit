# livewire-toolkit — Package Rules

This project uses the `markuspayne/livewire-toolkit` Composer package. Follow these rules whenever building Livewire components that use datatables, search, forms, modals, or model select options.

## Namespace

All toolkit classes are under `MarkusPayne\LivewireToolkit\`:
- `MarkusPayne\LivewireToolkit\Concerns\WithDataTable`
- `MarkusPayne\LivewireToolkit\Concerns\WithSearch`
- `MarkusPayne\LivewireToolkit\Model\HasSelectOptionsTrait`

## Blade Component Prefix

All toolkit blade components use the `toolkit::` view namespace prefix:
- `<x-toolkit::data-table.index>`
- `<x-toolkit::data-table.header>`
- `<x-toolkit::data-table.body>`
- `<x-toolkit::data-table.row>`
- `<x-toolkit::data-table.th>`
- `<x-toolkit::data-table.td>`
- `<x-toolkit::modal.large>`
- `<x-toolkit::form>`
- `<x-toolkit::close>`

Never use unprefixed component names for toolkit components.

## WithDataTable Trait

Every table component MUST:
1. `use WithDataTable;`
2. Define `public string $tableName = '{unique-name}';`
3. Implement `public function tableQuery(): Builder`
4. Optionally override `tableDefaults()` for sort/perPage

The trait provides: `$sortBy`, `$sortDir`, `$perPage`, `$perPageOptions`, and the `rows` computed property convention.

Example:

    use MarkusPayne\LivewireToolkit\Concerns\WithDataTable;

    new class extends Component
    {
        use WithDataTable;

        public string $tableName = 'athletes';

        public function tableQuery(): Builder
        {
            return Athlete::query();
        }

        protected function tableDefaults(): array
        {
            return [
                'sortBy' => 'last_name',
                'sortDir' => 'ASC',
                'perPage' => 25,
            ];
        }

        #[Computed]
        public function rows(): LengthAwarePaginator
        {
            return $this->tableQuery()
                ->orderBy($this->sortBy, $this->sortDir)
                ->paginate($this->perPage);
        }
    }

## WithSearch Trait

Used alongside WithDataTable for filterable tables:

1. `use WithSearch;`
2. Define `public array $filters` — maps field names to operators
3. Define `public array $tableSearchFields` — fields for the global search box
4. Call `$this->applySearch($query)` inside `tableQuery()`

Supported filter operators: `like`, `match` (fulltext), `in`, `hasIn`, `pivot`, `range`, `=`, and any SQL operator.

Example:

    use MarkusPayne\LivewireToolkit\Concerns\WithSearch;

    new class extends Component
    {
        use WithDataTable, WithSearch;

        public array $filters = [
            'name' => 'like',
            'email' => 'like',
            'active' => '=',
            'role_id' => 'in',
        ];

        public array $tableSearchFields = ['name', 'email'];

        public function tableQuery(): Builder
        {
            return $this->applySearch(User::query());
        }
    }

## HasSelectOptionsTrait

Add to any Eloquent model that needs to provide `<select>` options:

    use MarkusPayne\LivewireToolkit\Model\HasSelectOptionsTrait;

    class Sport extends Model
    {
        use HasSelectOptionsTrait;
    }

    // Default: keyed by id, valued by name
    $options = Sport::getOptions();

    // Custom value field, active-only, with filters
    $options = Sport::getOptions([
        'value' => 'slug',
        'activeOnly' => true,
        'filters' => [
            ['field' => 'type', 'value' => 'team'],
        ],
    ]);

## DataTable Blade Structure

Always nest components in this exact order:

    <x-toolkit::data-table.index>
        <x-toolkit::data-table.header>
            <x-toolkit::data-table.row>
                <x-toolkit::data-table.th sortField="column_name">Label</x-toolkit::data-table.th>
                <x-toolkit::data-table.th>Non-sortable</x-toolkit::data-table.th>
            </x-toolkit::data-table.row>
        </x-toolkit::data-table.header>

        <x-toolkit::data-table.body>
            @foreach ($this->rows as $row)
                <x-toolkit::data-table.row>
                    <x-toolkit::data-table.td>{{ $row->column }}</x-toolkit::data-table.td>
                </x-toolkit::data-table.row>
            @endforeach
        </x-toolkit::data-table.body>
    </x-toolkit::data-table.index>

### Row conditional styling

    <x-toolkit::data-table.row :hasError="$row->is_expired" :hasWarning="$row->needs_review">

Props: `hasError` (red), `hasWarning` (yellow), `hasSuccess` (green), `textColor` (custom hex).

### Extra heading slot

    <x-toolkit::data-table.index>
        <x-slot:extraHeading>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..." />
        </x-slot:extraHeading>
        ...
    </x-toolkit::data-table.index>

## Modal Component

### Large Modal

    <x-toolkit::modal.large name="edit-athlete">
        {{-- Content here --}}
    </x-toolkit::modal.large>

Props:
- `name` (string, default: `'large-modal'`) — used for event-based open/close
- `title` (string, optional) — reserved for future use

Opening and closing via Alpine events:

    {{-- Open --}}
    <button x-on:click="$dispatch('open-modal', { name: 'edit-athlete' })">Edit</button>

    {{-- Close by name --}}
    <button x-on:click="$dispatch('close-modal', { name: 'edit-athlete' })">Cancel</button>

    {{-- Close without name (closes current modal) --}}
    <button x-on:click="$dispatch('close-modal')">Cancel</button>

### Modal Event Pattern

1. Blade dispatches: `$dispatch('edit-{model}', { modelId: id })`
2. Component catches: `#[On('edit-{model}')]`
3. On save, dispatch: `refresh-{model}` and `close-modal`
4. Use `x-on:click` with `$dispatch()` — never `wire:click` for dispatch-only actions

## Form Grid

Use `<x-toolkit::form>` as the wrapper inside modals. It provides a 12-column grid
(`md:grid md:gap-6 md:grid-cols-12 items-start`):

    <x-toolkit::modal.large name="create-athlete">
        <x-toolkit::form>
            <x-input.group label="First Name" for="form.firstName" size="6">
                <x-input.text wire:model="form.firstName" id="form.firstName" />
            </x-input.group>
            <x-input.group label="Last Name" for="form.lastName" size="6">
                <x-input.text wire:model="form.lastName" id="form.lastName" />
            </x-input.group>
        </x-toolkit::form>
    </x-toolkit::modal.large>

Note: `<x-input.group>` and `<x-input.text>` are provided by the consuming app, not the toolkit.

## Do NOT

- Create class-based Livewire components in `app/Livewire/` — use MFC anonymous classes
- Use `wire:click` for event dispatches — use `x-on:click` with `$dispatch()`
- Hardcode per-page options — the trait provides `$perPageOptions`
- Skip the `$tableName` property — it's required for session-based sort/filter persistence
- Reference toolkit components without the `toolkit::` prefix
- Place form content outside modals — all forms render inside `<x-toolkit::modal.large>`
