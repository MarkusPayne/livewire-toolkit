# livewire-toolkit — Package Rules

This project uses the `markuspayne/livewire-toolkit` Composer package. Follow these rules whenever building Livewire components that use datatables, search, forms, modals, menus, or model select options.

## Namespace

All toolkit classes are under `MarkusPayne\LivewireToolkit\`:
- `MarkusPayne\LivewireToolkit\Concerns\WithDataTable`
- `MarkusPayne\LivewireToolkit\Concerns\WithSearch`
- `MarkusPayne\LivewireToolkit\Model\HasSelectOptionsTrait`

## Blade Component Prefix

All toolkit blade components use the `toolkit::` view namespace prefix.

Data Table (sortable, paginated — requires WithDataTable trait):
- `<x-toolkit::data-table.index>`
- `<x-toolkit::data-table.thead>`
- `<x-toolkit::data-table.tbody>`
- `<x-toolkit::data-table.tr>`
- `<x-toolkit::data-table.th>`
- `<x-toolkit::data-table.td>`

Basic Table (static, no trait needed):
- `<x-toolkit::table.index>`
- `<x-toolkit::table.thead>`
- `<x-toolkit::table.header-group>`
- `<x-toolkit::table.tbody>`
- `<x-toolkit::table.tr>`
- `<x-toolkit::table.th>`
- `<x-toolkit::table.td>`

Modal & Form:
- `<x-toolkit::modal.large>`
- `<x-toolkit::modal.medium>`
- `<x-toolkit::modal.small>`
- `<x-toolkit::form>`

Icons:
- `<x-toolkit::icon.close>`
- `<x-toolkit::icon.edit>`
- `<x-toolkit::icon.delete>`
- `<x-toolkit::icon.add>`
- `<x-toolkit::icon.download>`
- `<x-toolkit::icon.chevron-down>`
- `<x-toolkit::icon.ellipsis>`

Inputs:
- `<x-toolkit::input.group>`
- `<x-toolkit::input.group-inline>`
- `<x-toolkit::input.error>`
- `<x-toolkit::input.text>`
- `<x-toolkit::input.textarea>`
- `<x-toolkit::input.select>`
- `<x-toolkit::input.checkbox>`
- `<x-toolkit::input.radio>`
- `<x-toolkit::input.toggle>`
- `<x-toolkit::input.money>`
- `<x-toolkit::input.yes-no>`
- `<x-toolkit::input.date>`
- `<x-toolkit::input.multi-select>`
- `<x-toolkit::input.signature>`
- `<x-toolkit::input.file-upload>`
- `<x-toolkit::input.check-all-rows>`

Buttons:
- `<x-toolkit::button>`
- `<x-toolkit::button.primary>`
- `<x-toolkit::button.secondary>`
- `<x-toolkit::button.danger>`
- `<x-toolkit::button.link>`

Menu (requires @alpinejs/ui):
- `<x-toolkit::menu.index>`
- `<x-toolkit::menu.button>`
- `<x-toolkit::menu.button-actions>`
- `<x-toolkit::menu.button-dots>`
- `<x-toolkit::menu.items>`
- `<x-toolkit::menu.item>`
- `<x-toolkit::menu.item-edit>`
- `<x-toolkit::menu.item-delete>`
- `<x-toolkit::menu.item-add>`
- `<x-toolkit::menu.item-download>`
- `<x-toolkit::menu.close>`

Sidebar:
- `<x-toolkit::sidebar.index>` — modes: `slideover` (drawer) or `fixed` (full page); sides: `left` or `right`
- `<x-toolkit::sidebar.group>`
- `<x-toolkit::sidebar.link>`
- `<x-toolkit::logo>`

Never use unprefixed component names for toolkit components.

## CSS Setup

Projects using the toolkit must import the base CSS in their `app.css`:

    @import 'tailwindcss';
    @import '../../vendor/markuspayne/livewire-toolkit/resources/css/toolkit.css' layer(base);

    @plugin '@tailwindcss/forms';

This provides base styles for all input types, checkboxes, radio buttons,
disabled states, labels, and flatpickr. The `@tailwindcss/forms` plugin is
a prerequisite.

Do NOT duplicate these styles in the consuming app's CSS — they come from
the toolkit.

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

## HasSelectOptionsTrait

Add to any Eloquent model that needs to provide `<select>` options.
Returns a plain array keyed by the key field, valued by the value field.
Results are cached with Redis tags and auto-flushed on model save.

    $options = Sport::getOptions();
    $options = Sport::getOptions(['value' => 'slug', 'activeOnly' => true]);

## DataTable Blade Structure

Always nest components in this exact order:

    <x-toolkit::data-table.index>
        <x-toolkit::data-table.thead>
            <x-toolkit::data-table.tr>
                <x-toolkit::data-table.th sortField="column_name">Label</x-toolkit::data-table.th>
                <x-toolkit::data-table.th>Non-sortable</x-toolkit::data-table.th>
            </x-toolkit::data-table.tr>
        </x-toolkit::data-table.thead>

        <x-toolkit::data-table.tbody>
            @foreach ($this->rows as $row)
                <x-toolkit::data-table.tr>
                    <x-toolkit::data-table.td>{{ $row->column }}</x-toolkit::data-table.td>
                </x-toolkit::data-table.tr>
            @endforeach
        </x-toolkit::data-table.tbody>
    </x-toolkit::data-table.index>

Row conditional styling props: `hasError` (red), `hasWarning` (yellow), `hasSuccess` (green), `textColor` (custom hex).

Extra heading slot for search/filters above the table:

    <x-toolkit::data-table.index>
        <x-slot:extraHeading>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..." />
        </x-slot:extraHeading>
        ...
    </x-toolkit::data-table.index>

## Basic Table Blade Structure

For static or lightweight tables that don't need sorting or pagination:

    <x-toolkit::table.index>
        <x-toolkit::table.thead>
            <x-toolkit::table.tr>
                <x-toolkit::table.th>Label</x-toolkit::table.th>
            </x-toolkit::table.tr>
        </x-toolkit::table.thead>

        <x-toolkit::table.tbody>
            @foreach ($items as $item)
                <x-toolkit::table.tr>
                    <x-toolkit::table.td>{{ $item->value }}</x-toolkit::table.td>
                </x-toolkit::table.tr>
            @endforeach
        </x-toolkit::table.tbody>
    </x-toolkit::table.index>

Use `table.*` for detail views, inline data. Use `data-table.*` for Livewire list pages.

## Modal Components

Three sizes available. All require a title slot:

    <x-toolkit::modal.large name="edit-athlete">
        <x-slot:title>Edit Athlete</x-slot:title>
        {{-- content --}}
        <x-slot:footer>
            <x-toolkit::button.secondary x-on:click="$dispatch('close-modal', { name: 'edit-athlete' })">Cancel</x-toolkit::button.secondary>
            <x-toolkit::button.primary wire:click="save">Save</x-toolkit::button.primary>
        </x-slot:footer>
    </x-toolkit::modal.large>

Size guide:
- `modal.large` — wide forms, detail views, combine forms
- `modal.medium` — standard add/edit forms
- `modal.small` — confirmations, simple single-field forms

### Modal Event Pattern

1. Blade dispatches: `$dispatch('edit-{model}', { modelId: id })`
2. Component catches: `#[On('edit-{model}')]`
3. Component opens: `$this->dispatch('open-modal', name: 'edit-{model}');`
4. On save: `$this->dispatch('refresh-{model}'); $this->dispatch('close-modal', name: 'edit-{model}');`
5. Use `x-on:click` with `$dispatch()` — NEVER `wire:click` for dispatch-only actions

### Choosing a modal size

- Forms with 3+ columns or many fields → `modal.large`
- Forms with 1-2 columns, standard CRUD → `modal.medium`
- Single field, confirmation, simple dialog → `modal.small`

## Form Grid

Use `<x-toolkit::form>` inside modals. Provides `md:grid md:gap-6 md:grid-cols-12 items-start`.
Attributes merge, so add `wire:submit` etc:

    <x-toolkit::form wire:submit="save">
        <x-toolkit::input.group label="Name" for="form.name" :error="$errors->first('form.name')" size="6">
            <x-toolkit::input.text wire:model="form.name" id="form.name" />
        </x-toolkit::input.group>
    </x-toolkit::form>

## Input Components

Use `<x-toolkit::input.group>` as the wrapper for all form inputs. It provides
label, error display, help text, and column sizing via the `size` prop (1-12).

Use `<x-toolkit::input.group-inline>` for side-by-side label/input layout.

### JS dependencies

- `<x-toolkit::input.date>` — requires `window.flatpickr`
- `<x-toolkit::input.signature>` — requires `window.SignaturePad`
- `<x-toolkit::input.error>` — uses `$focus` from `@alpinejs/ui`

### yes-no options

    <x-toolkit::input.select wire:model="form.active">
        <x-toolkit::input.yes-no />
    </x-toolkit::input.select>

## Icon Components

Use `<x-toolkit::icon.*>` for consistent icons. All accept $attributes for class merging:

    <x-toolkit::icon.edit class="size-3 text-sky-600" />
    <x-toolkit::icon.delete class="size-3 text-red-600" />

## Button Components

    <x-toolkit::button.primary wire:click="save">Save</x-toolkit::button.primary>
    <x-toolkit::button.secondary x-on:click="$dispatch('close-modal')">Cancel</x-toolkit::button.secondary>
    <x-toolkit::button.danger wire:click="delete">Delete</x-toolkit::button.danger>

Base `<x-toolkit::button>` includes `wire:loading.attr="disabled"` automatically.
Variant buttons extend the base. Colors use `sky-*` as generic default.

## Menu Components

Use `<x-toolkit::menu.*>` for action dropdowns on table rows:

    <x-toolkit::menu.index>
        <x-toolkit::menu.button-actions />
        <x-toolkit::menu.items>
            <x-toolkit::menu.item-edit x-on:click="$dispatch('edit-model', { id: {{ $row->id }} })" />
            <x-toolkit::menu.item-delete wire:click="delete({{ $row->id }})" />
        </x-toolkit::menu.items>
    </x-toolkit::menu.index>

Shortcut items (item-edit, item-delete, item-add, item-download) use
`<x-toolkit::icon.*>` components and include default labels. Pass slot
content to override the label. Requires `@alpinejs/ui` plugin.

## Sidebar Components

`<x-toolkit::sidebar.index>` is a single component with two modes.

Props:
- `mode` — `slideover` (default) or `fixed`. Falls back to `config('livewire-toolkit.sidebar.default_mode')`.
- `side` — `left` (default) or `right`. Falls back to `config('livewire-toolkit.sidebar.default_side')`.
- `title` — optional `<h1>` (fixed mode only).

Slots:
- `$logo`, `$nav` — used in both modes.
- `$topbar`, `$slot` (page content) — fixed mode only.

### Slideover (drop-in)

    {{-- Toggle from anywhere --}}
    <button x-on:click="$dispatch('toolkit-sidebar-open')">Menu</button>

    <x-toolkit::sidebar.index>
        <x-slot:logo>
            <x-toolkit::logo src="{{ asset('images/logo.png') }}" />
        </x-slot:logo>
        <x-slot:nav>
            <x-toolkit::sidebar.link href="{{ route('dashboard') }}">Dashboard</x-toolkit::sidebar.link>
            <x-toolkit::sidebar.group label="Admin">
                <x-toolkit::sidebar.link href="{{ route('users.index') }}">Users</x-toolkit::sidebar.link>
            </x-toolkit::sidebar.group>
        </x-slot:nav>
    </x-toolkit::sidebar.index>

The component owns its own Alpine state. Dispatch `toolkit-sidebar-open` /
`toolkit-sidebar-close` as window events to control it. `Escape` closes
automatically. Do NOT add `x-data="{ sidebarOpen: false }"` on the body —
that pattern is gone.

### Fixed (page layout)

    <x-toolkit::sidebar.index mode="fixed" title="Page Title">
        <x-slot:logo>...</x-slot:logo>
        <x-slot:nav>
            <x-toolkit::sidebar.link href="...">...</x-toolkit::sidebar.link>
            <x-toolkit::sidebar.group label="...">...</x-toolkit::sidebar.group>
        </x-slot:nav>
        <x-slot:topbar>...</x-slot:topbar>

        {{-- page content --}}
    </x-toolkit::sidebar.index>

Desktop: persistent sidebar (w-72) on the chosen side. Mobile: hamburger in
the topbar opens the slide-over.

Icons in nav items are passed via named `$icon` slots.

### Defaults

Override via `config/livewire-toolkit.php`:

    php artisan vendor:publish --tag=livewire-toolkit-config

    return [
        'sidebar' => [
            'default_mode' => 'fixed',  // make every page use fixed by default
            'default_side' => 'left',
        ],
    ];

## Do NOT

- Create class-based Livewire components in `app/Livewire/` — use MFC anonymous classes
- Use `wire:click` for event dispatches — use `x-on:click` with `$dispatch()`
- Hardcode per-page options — the trait provides `$perPageOptions`
- Skip the `$tableName` property — it's required for session-based sort/filter persistence
- Reference toolkit components without the `toolkit::` prefix
- Place form content outside modals — use `<x-toolkit::modal.large>`, `<x-toolkit::modal.medium>`, or `<x-toolkit::modal.small>`
- Skip the title slot on modals — always provide `<x-slot:title>`
- Use raw HTML form inputs — use `<x-toolkit::input.*>` components
- Forget to wrap inputs in `<x-toolkit::input.group>` — provides error display and sizing
- Use inline SVGs for common icons — use `<x-toolkit::icon.*>` instead
- Use `data-table.*` for static tables — use `table.*` when no sort/pagination needed
- Use old component names — data-table uses `thead`/`tbody`/`tr` not `header`/`body`/`row`
