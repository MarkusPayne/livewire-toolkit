# Livewire Toolkit

Reusable Livewire traits and Blade components for datatables, search, forms, modals, menus, and model select options.

## Installation

    composer require markuspayne/livewire-toolkit

Until this package is published on Packagist, add the GitHub repository to your `composer.json`:

    {
        "repositories": [
            { "type": "vcs", "url": "https://github.com/MarkusPayne/livewire-toolkit" }
        ]
    }

## Requirements

- PHP 8.3+
- Laravel 12 or 13
- Livewire 4
- Tailwind CSS (consuming project must provide)
- Alpine.js (consuming project must provide)
- @alpinejs/ui (for menu components and input.error)
- @tailwindcss/forms (for toolkit CSS)

## CSS Setup

The toolkit includes base styles for form inputs, checkboxes, radio buttons,
and the flatpickr date picker. Import it in your `app.css`:

    @import 'tailwindcss';
    @import '../../vendor/markuspayne/livewire-toolkit/resources/css/toolkit.css' layer(base);

    @plugin '@tailwindcss/forms';

Import it **after** `@import 'tailwindcss'` and **before** your own styles.

Alternatively, publish the CSS to customize it:

    php artisan vendor:publish --tag=livewire-toolkit-css

Then import the published copy:

    @import './vendor/toolkit.css' layer(base);

### Custom Primary Color

The toolkit uses `sky-*` as its default accent color. If your project defines
a custom `--color-primary` in your `@theme` block, publish the views and CSS
to override.

## Usage

### WithDataTable

    use MarkusPayne\LivewireToolkit\Concerns\WithDataTable;

    new class extends Component
    {
        use WithDataTable;

        public string $tableName = 'users';

        public function tableQuery(): Builder
        {
            return User::query();
        }

        protected function tableDefaults(): array
        {
            return [
                'sortBy' => 'name',
                'sortDir' => 'ASC',
                'perPage' => 25,
            ];
        }
    }

Blade:

    <x-toolkit::data-table.index>
        <x-toolkit::data-table.thead>
            <x-toolkit::data-table.tr>
                <x-toolkit::data-table.th sortField="name">Name</x-toolkit::data-table.th>
                <x-toolkit::data-table.th sortField="email">Email</x-toolkit::data-table.th>
                <x-toolkit::data-table.th>Actions</x-toolkit::data-table.th>
            </x-toolkit::data-table.tr>
        </x-toolkit::data-table.thead>

        <x-toolkit::data-table.tbody>
            @foreach ($this->rows as $row)
                <x-toolkit::data-table.tr>
                    <x-toolkit::data-table.td>{{ $row->name }}</x-toolkit::data-table.td>
                    <x-toolkit::data-table.td>{{ $row->email }}</x-toolkit::data-table.td>
                    <x-toolkit::data-table.td>
                        <x-toolkit::menu.index>
                            <x-toolkit::menu.button-dots />
                            <x-toolkit::menu.items>
                                <x-toolkit::menu.item-edit x-on:click="$dispatch('edit-user', { userId: {{ $row->id }} })" />
                                <x-toolkit::menu.item-delete wire:click="delete({{ $row->id }})" />
                            </x-toolkit::menu.items>
                        </x-toolkit::menu.index>
                    </x-toolkit::data-table.td>
                </x-toolkit::data-table.tr>
            @endforeach
        </x-toolkit::data-table.tbody>
    </x-toolkit::data-table.index>

### Basic Table

A simple table for static data — no sorting, pagination, or traits needed.

    <x-toolkit::table.index>
        <x-toolkit::table.thead>
            <x-toolkit::table.tr>
                <x-toolkit::table.th>Name</x-toolkit::table.th>
                <x-toolkit::table.th>Value</x-toolkit::table.th>
            </x-toolkit::table.tr>
        </x-toolkit::table.thead>

        <x-toolkit::table.tbody>
            @foreach ($items as $item)
                <x-toolkit::table.tr>
                    <x-toolkit::table.td>{{ $item->name }}</x-toolkit::table.td>
                    <x-toolkit::table.td>{{ $item->value }}</x-toolkit::table.td>
                </x-toolkit::table.tr>
            @endforeach
        </x-toolkit::table.tbody>
    </x-toolkit::table.index>

| Feature | `data-table.*` | `table.*` |
|---------|---------------|-----------|
| Sorting | Built-in via Alpine | None |
| Pagination | Built-in | None |
| WithDataTable trait | Required | Not needed |
| Use case | Livewire list pages | Detail views, static tables |

### WithSearch

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

Filter types: `like`, `match` (fulltext), `in`, `hasIn`, `pivot`, `range`, `=`, and any SQL operator.

### HasSelectOptionsTrait

    use MarkusPayne\LivewireToolkit\Model\HasSelectOptionsTrait;

    class Sport extends Model
    {
        use HasSelectOptionsTrait;
    }

    // Returns plain array keyed by id => name
    $options = Sport::getOptions();
    $options = Sport::getOptions([
        'value' => 'slug',
        'activeOnly' => true,
        'filters' => [
            ['field' => 'type', 'value' => 'team'],
        ],
    ]);

Results are cached with Redis tags and auto-flushed on model save.
Pass `'cache' => false` to bypass.

### Modal

    <x-toolkit::modal.large name="edit-athlete">
        {{-- Content --}}
    </x-toolkit::modal.large>

Props: `name` (string, default: `'large-modal'`), `title` (string, optional).

    {{-- Open --}}
    <button x-on:click="$dispatch('open-modal', { name: 'edit-athlete' })">Edit</button>

    {{-- Close --}}
    <button x-on:click="$dispatch('close-modal', { name: 'edit-athlete' })">Cancel</button>
    <button x-on:click="$dispatch('close-modal')">Cancel</button>

### Form

    <x-toolkit::form wire:submit="save">
        <x-toolkit::input.group label="First Name" for="form.firstName" :error="$errors->first('form.firstName')" size="6">
            <x-toolkit::input.text wire:model="form.firstName" id="form.firstName" />
        </x-toolkit::input.group>
        <x-toolkit::input.group label="Last Name" for="form.lastName" :error="$errors->first('form.lastName')" size="6">
            <x-toolkit::input.text wire:model="form.lastName" id="form.lastName" />
        </x-toolkit::input.group>
    </x-toolkit::form>

Provides `md:grid md:gap-6 md:grid-cols-12 items-start`. Input groups use `col-span-{n}` via the `size` prop.

### Icon Components

    <x-toolkit::icon.close />
    <x-toolkit::icon.edit class="text-sky-600" />
    <x-toolkit::icon.delete class="text-red-600" />
    <x-toolkit::icon.add class="text-green-600" />
    <x-toolkit::icon.download class="text-sky-600" />
    <x-toolkit::icon.chevron-down />
    <x-toolkit::icon.ellipsis />

All icons accept `$attributes` for class merging.

### Input Components

| Component | Description |
|-----------|-------------|
| `<x-toolkit::input.group>` | Form group — label, error, help text, `size` (1-12) |
| `<x-toolkit::input.group-inline>` | Inline label/input layout |
| `<x-toolkit::input.error>` | Validation error display |
| `<x-toolkit::input.text>` | Text/number/email input |
| `<x-toolkit::input.textarea>` | Textarea with `rows` prop |
| `<x-toolkit::input.select>` | Select with optional `placeholder` |
| `<x-toolkit::input.checkbox>` | Checkbox |
| `<x-toolkit::input.radio>` | Radio with `label` prop |
| `<x-toolkit::input.toggle>` | Toggle switch |
| `<x-toolkit::input.money>` | Money input ($, USD) |
| `<x-toolkit::input.yes-no>` | Yes/No options (use inside select) |
| `<x-toolkit::input.date>` | Date picker (requires flatpickr) |
| `<x-toolkit::input.multi-select>` | Multi-select with tags |
| `<x-toolkit::input.signature>` | Signature pad (requires SignaturePad) |
| `<x-toolkit::input.file-upload>` | Drag-and-drop file upload |
| `<x-toolkit::input.check-all-rows>` | Select-all checkbox for tables |

#### JavaScript Dependencies

| Component | Dependency | Setup |
|-----------|-----------|-------|
| `date` | flatpickr | `window.flatpickr = flatpickr;` in bootstrap.js |
| `signature` | signature_pad | `window.SignaturePad = SignaturePad;` in bootstrap.js |
| `error` | @alpinejs/ui | Alpine plugin in app.js |
| `menu.*` | @alpinejs/ui | Alpine plugin in app.js |

### Button Components

    <x-toolkit::button>Default</x-toolkit::button>
    <x-toolkit::button.primary>Save</x-toolkit::button.primary>
    <x-toolkit::button.secondary>Cancel</x-toolkit::button.secondary>
    <x-toolkit::button.danger>Delete</x-toolkit::button.danger>
    <x-toolkit::button.link>View Details</x-toolkit::button.link>

All buttons include `wire:loading.attr="disabled"`. Colors use `sky-*` as default.

### Menu (Dropdown)

Action menu built on Alpine UI's `x-menu` directive.

    <x-toolkit::menu.index>
        <x-toolkit::menu.button-actions />

        <x-toolkit::menu.items>
            <x-toolkit::menu.item-edit x-on:click="$dispatch('edit-athlete', { athleteId: {{ $athlete->id }} })" />
            <x-toolkit::menu.item-delete wire:click="delete({{ $athlete->id }})" />
            <x-toolkit::menu.item-add x-on:click="$dispatch('create-result')">Add Result</x-toolkit::menu.item-add>
            <x-toolkit::menu.item-download wire:click="export({{ $athlete->id }})" />
        </x-toolkit::menu.items>
    </x-toolkit::menu.index>

Or with dots trigger:

    <x-toolkit::menu.index>
        <x-toolkit::menu.button-dots />
        <x-toolkit::menu.items>
            <x-toolkit::menu.item x-on:click="doSomething()">Custom Action</x-toolkit::menu.item>
        </x-toolkit::menu.items>
    </x-toolkit::menu.index>

`<x-toolkit::menu.item>` accepts an optional `$icon` slot:

    <x-toolkit::menu.item x-on:click="openSettings()">
        <x-slot:icon><x-icons icon="gear" class="size-4" /></x-slot:icon>
        Settings
    </x-toolkit::menu.item>

| Component | Description |
|-----------|-------------|
| `<x-toolkit::menu.index>` | Menu wrapper |
| `<x-toolkit::menu.button>` | Base trigger |
| `<x-toolkit::menu.button-actions>` | "Actions" trigger with chevron |
| `<x-toolkit::menu.button-dots>` | Ellipsis trigger |
| `<x-toolkit::menu.items>` | Dropdown panel |
| `<x-toolkit::menu.item>` | Generic item |
| `<x-toolkit::menu.item-edit>` | Edit with icon |
| `<x-toolkit::menu.item-delete>` | Delete with icon + confirm |
| `<x-toolkit::menu.item-add>` | Add with icon |
| `<x-toolkit::menu.item-download>` | Download with icon |
| `<x-toolkit::menu.close>` | Closes menu on click |

Requires `@alpinejs/ui` plugin in consuming app's `app.js`.

### Sidebar

Slide-over navigation sidebar with collapsible groups.

    {{-- In your layout, add x-data with sidebarOpen state --}}
    <div x-data="{ sidebarOpen: false }">
        {{-- Toggle button (e.g. hamburger in header) --}}
        <button x-on:click="sidebarOpen = true">Menu</button>

        <x-toolkit::sidebar.index>
            <x-slot:logo>
                <x-toolkit::logo src="{{ asset('images/logo.png') }}" class="h-12! w-auto" />
            </x-slot:logo>

            <x-toolkit::sidebar.link href="{{ route('dashboard') }}">
                <x-slot:icon><x-icons icon="browser" class="size-5" /></x-slot:icon>
                Dashboard
            </x-toolkit::sidebar.link>

            <x-toolkit::sidebar.group label="Settings">
                <x-slot:icon><x-icons icon="gear" class="size-5" /></x-slot:icon>

                <x-toolkit::sidebar.link href="{{ route('users.index') }}">Users</x-toolkit::sidebar.link>
            </x-toolkit::sidebar.group>
        </x-toolkit::sidebar.index>
    </div>

| Component | Description |
|-----------|-------------|
| `<x-toolkit::sidebar.index>` | Sidebar shell with slide-over animation, backdrop, close button |
| `<x-toolkit::sidebar.group>` | Collapsible nav group with label and icon slot |
| `<x-toolkit::sidebar.link>` | Nav link with active state detection and icon slot |
| `<x-toolkit::logo>` | Logo image with fallback to app name text |

The sidebar requires `sidebarOpen` Alpine state in a parent element.
Icons are passed via named `$icon` slots — use your app's own icon components.

### Sidebar Layout

A full page layout with persistent sidebar on desktop and hamburger
slide-over on mobile.

    <x-toolkit::layout.sidebar title="Dashboard">
        <x-slot:logo>
            <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto" />
        </x-slot:logo>

        <x-slot:nav>
            <x-toolkit::sidebar.link href="/dashboard">
                <x-slot:icon>{{-- your icon here --}}</x-slot:icon>
                Dashboard
            </x-toolkit::sidebar.link>
            <x-toolkit::sidebar.group label="Admin">
                <x-toolkit::sidebar.link href="/users">Users</x-toolkit::sidebar.link>
            </x-toolkit::sidebar.group>
        </x-slot:nav>

        <x-slot:topbar>
            {{-- Top bar content (search, profile, etc) --}}
        </x-slot:topbar>

        {{-- Page content --}}
    </x-toolkit::layout.sidebar>

| Slot | Description |
|------|-------------|
| `$logo` | Logo image/markup in sidebar header |
| `$nav` | Navigation items (sidebar.link, sidebar.group) |
| `$topbar` | Top bar content (right side of header bar) |
| `$slot` | Main page content |

Props: `title` (optional — renders `<h1>` above content)

Behavior:
- `>= lg` (1024px): Sidebar always visible, hamburger hidden
- `< lg`: Sidebar hidden, hamburger in top bar opens slide-over

## Publishing

    php artisan vendor:publish --tag=livewire-toolkit-views
    php artisan vendor:publish --tag=livewire-toolkit-css
    php artisan vendor:publish --tag=livewire-toolkit-claude-rules

## License

MIT - see [LICENSE](LICENSE) for details.
