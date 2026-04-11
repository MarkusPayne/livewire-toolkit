# Livewire Toolkit

Reusable Livewire traits and Blade components for datatables, search, and model select options.

## Installation

```bash
composer require markuspayne/livewire-toolkit
```

Until this package is published on Packagist, add the GitHub repository to your `composer.json`:

```json
{
    "repositories": [
        { "type": "vcs", "url": "https://github.com/MarkusPayne/livewire-toolkit" }
    ]
}
```

## Requirements

- PHP 8.3+
- Laravel 12 or 13
- Livewire 4
- Tailwind CSS (consuming project must provide)
- Alpine.js (consuming project must provide)

## Usage

### WithDataTable

```php
use MarkusPayne\LivewireToolkit\Concerns\WithDataTable;

class UserList extends Component
{
    use WithDataTable;

    public string $tableName = 'users';

    public function tableQuery(): Builder
    {
        return User::query();
    }

    // Optional: override defaults
    protected function tableDefaults(): array
    {
        return [
            'sortBy' => 'name',
            'sortDir' => 'ASC',
            'perPage' => 25,
        ];
    }
}
```

```blade
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
                <x-toolkit::data-table.td>Actions here</x-toolkit::data-table.td>
            </x-toolkit::data-table.tr>
        @endforeach
    </x-toolkit::data-table.tbody>
</x-toolkit::data-table.index>
```

### Basic Table

A simple table component set for static or lightweight tables that don't
require sorting, pagination, or the `WithDataTable` trait.

```blade
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
```

#### Differences from data-table

| Feature | `data-table.*` | `table.*` |
|---------|---------------|-----------|
| Sorting | Built-in via Alpine | None |
| Pagination | Built-in per-page selector + links | None |
| WithDataTable trait | Required | Not needed |
| Use case | Livewire list pages | Detail views, static tables, inline data |

#### Row conditional styling

```blade
<x-toolkit::table.tr :hasError="$item->is_expired" :hasSuccess="$item->is_active">
```

### WithSearch

```php
use MarkusPayne\LivewireToolkit\Concerns\WithSearch;

class UserList extends Component
{
    use WithDataTable, WithSearch;

    public string $tableName = 'users';

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
```

Filter types: `like`, `match` (fulltext), `in`, `hasIn`, `pivot`, `range`, `=`, and any SQL operator.

### HasSelectOptionsTrait

```php
use MarkusPayne\LivewireToolkit\Model\HasSelectOptionsTrait;

class Sport extends Model
{
    use HasSelectOptionsTrait;
}

// Usage
$options = Sport::getOptions(); // Collection keyed by id, valued by name
$options = Sport::getOptions([
    'value' => 'slug',
    'activeOnly' => true,
    'filters' => [
        ['field' => 'type', 'value' => 'team'],
    ],
]);
```

### Modal

```blade
<x-toolkit::modal.large name="edit-athlete" title="Edit Athlete">
    {{-- Content here --}}
</x-toolkit::modal.large>
```

Props:
- `name` (string, default: `'large-modal'`) — used for event-based open/close
- `title` (string, optional) — currently reserved, not rendered in header

Opening and closing:

```blade
{{-- Open --}}
<button x-on:click="$dispatch('open-modal', { name: 'edit-athlete' })">Edit</button>

{{-- Close from inside --}}
<button x-on:click="$dispatch('close-modal', { name: 'edit-athlete' })">Cancel</button>

{{-- Close without specifying name closes the current modal --}}
<button x-on:click="$dispatch('close-modal')">Cancel</button>
```

### Form Grid

```blade
<x-toolkit::form>
    {{-- 12-column grid with gap-6. Use <x-input.group size="6"> etc. --}}
</x-toolkit::form>
```

The form component provides `md:grid md:gap-6 md:grid-cols-12 items-start`.
Input group components from the consuming app use `col-span-{n}` to fill the grid.

### Close Icon

```blade
<x-toolkit::close />
```

A standalone close/X SVG icon. Used internally by the modal but also available
for direct use.

### Input Components

Form input components for use inside `<x-toolkit::input.group>` wrappers.

#### Group (stacked label)

```blade
<x-toolkit::input.group label="Email" for="form.email" :error="$errors->first('form.email')" size="6">
    <x-toolkit::input.text wire:model="form.email" id="form.email" />
</x-toolkit::input.group>
```

Props: `label`, `for`, `error`, `helpText`, `size` (1-12, default 6)

#### Group Inline

```blade
<x-toolkit::input.group-inline label="Status" for="form.active" :error="$errors->first('form.active')" size="6">
    <x-toolkit::input.toggle wire:model="form.active">Active</x-toolkit::input.toggle>
</x-toolkit::input.group-inline>
```

Same props as group, but renders label and input side-by-side.

#### Available Input Components

| Component | Description |
|-----------|-------------|
| `<x-toolkit::input.group>` | Form group with stacked label, error, help text |
| `<x-toolkit::input.group-inline>` | Inline form group (label and input side-by-side) |
| `<x-toolkit::input.error>` | Validation error message display |
| `<x-toolkit::input.text>` | Text/number/email input |
| `<x-toolkit::input.textarea>` | Textarea with configurable rows |
| `<x-toolkit::input.select>` | Select dropdown with optional placeholder |
| `<x-toolkit::input.checkbox>` | Checkbox input |
| `<x-toolkit::input.radio>` | Radio button with label |
| `<x-toolkit::input.toggle>` | Toggle/switch with label |
| `<x-toolkit::input.money>` | Money input with $ prefix and USD suffix |
| `<x-toolkit::input.yes-no>` | Yes/No option pair (use inside select) |
| `<x-toolkit::input.date>` | Date picker (requires flatpickr) |
| `<x-toolkit::input.multi-select>` | Multi-select with tags (Alpine, self-contained) |
| `<x-toolkit::input.signature>` | Signature pad (requires signature_pad) |
| `<x-toolkit::input.file-upload>` | Drag-and-drop file upload with progress |
| `<x-toolkit::input.check-all-rows>` | Select-all checkbox for data tables |

#### JavaScript Dependencies

Some input components require third-party libraries loaded on `window` in
the consuming app's `bootstrap.js`:

| Component | Dependency | bootstrap.js |
|-----------|-----------|-------------|
| `date` | flatpickr | `window.flatpickr = flatpickr;` |
| `signature` | signature_pad | `window.SignaturePad = SignaturePad;` |
| `error` | @alpinejs/ui | Alpine plugin registered in app.js |

### Button Components

```blade
<x-toolkit::button>Default</x-toolkit::button>
<x-toolkit::button.primary>Save</x-toolkit::button.primary>
<x-toolkit::button.secondary>Cancel</x-toolkit::button.secondary>
<x-toolkit::button.danger>Delete</x-toolkit::button.danger>
<x-toolkit::button.link>View Details</x-toolkit::button.link>
```

All buttons support the `disabled` prop and have `wire:loading.attr="disabled"`
built in (except `link` which is a plain styled button).

| Component | Description |
|-----------|-------------|
| `<x-toolkit::button>` | Base button with border, focus ring, loading state |
| `<x-toolkit::button.primary>` | Sky blue filled button |
| `<x-toolkit::button.secondary>` | Gray outlined button with dark mode support |
| `<x-toolkit::button.danger>` | Red filled button for destructive actions |
| `<x-toolkit::button.link>` | Text-only button styled as a link |

Note: Button colors use `sky-*` as a generic default. If your project defines
a custom `--color-primary` theme, publish the views and update the classes:

```bash
php artisan vendor:publish --tag=livewire-toolkit-views
```

## Publishing Views

To customize the blade components:

```bash
php artisan vendor:publish --tag=livewire-toolkit-views
```

Views will be published to `resources/views/vendor/toolkit/`.

## Claude Code Integration

Publish the Claude Code rules file for AI-assisted development:

```bash
php artisan vendor:publish --tag=livewire-toolkit-claude-rules
```

This publishes `.claude/rules/livewire-toolkit.md` which teaches Claude Code
how to correctly use the toolkit's traits and components.

A SKILL.md reference file is also included at `stubs/claude/SKILL.md` for
use with Claude.ai skills or Claude Code skill directories.

## Available Components

| Component | Description |
|-----------|-------------|
| `<x-toolkit::data-table.index>` | Table wrapper with per-page selector, pagination, optional export |
| `<x-toolkit::data-table.thead>` | Table header with Alpine sort state |
| `<x-toolkit::data-table.tbody>` | Table body wrapper |
| `<x-toolkit::data-table.tr>` | Table row with conditional styling (error/warning/success/custom color) |
| `<x-toolkit::data-table.th>` | Sortable table header cell |
| `<x-toolkit::data-table.td>` | Table data cell |
| `<x-toolkit::table.index>` | Simple table wrapper (no sort/pagination) |
| `<x-toolkit::table.thead>` | Simple table header group (disables row hover) |
| `<x-toolkit::table.header-group>` | Header group with configurable background |
| `<x-toolkit::table.tbody>` | Simple table body wrapper |
| `<x-toolkit::table.tr>` | Simple table row with conditional styling (`hasError`, `hasSuccess`, `textColor`) |
| `<x-toolkit::table.th>` | Simple table header cell |
| `<x-toolkit::table.td>` | Simple table data cell |
| `<x-toolkit::modal.large>` | Large modal with backdrop, close button, event-driven open/close |
| `<x-toolkit::form>` | 12-column CSS grid wrapper for form layouts |
| `<x-toolkit::close>` | Close/X SVG icon |
| `<x-toolkit::button>` | Base button with border, focus ring, loading state |
| `<x-toolkit::button.primary>` | Sky blue filled button |
| `<x-toolkit::button.secondary>` | Gray outlined button with dark mode support |
| `<x-toolkit::button.danger>` | Red filled button for destructive actions |
| `<x-toolkit::button.link>` | Text-only button styled as a link |
| `<x-toolkit::input.group>` | Form group with stacked label, error, help text |
| `<x-toolkit::input.group-inline>` | Inline form group (label and input side-by-side) |
| `<x-toolkit::input.error>` | Validation error message display |
| `<x-toolkit::input.text>` | Text/number/email input |
| `<x-toolkit::input.textarea>` | Textarea with configurable rows |
| `<x-toolkit::input.select>` | Select dropdown with optional placeholder |
| `<x-toolkit::input.checkbox>` | Checkbox input |
| `<x-toolkit::input.radio>` | Radio button with label |
| `<x-toolkit::input.toggle>` | Toggle/switch with label |
| `<x-toolkit::input.money>` | Money input with $ prefix and USD suffix |
| `<x-toolkit::input.yes-no>` | Yes/No option pair (use inside select) |
| `<x-toolkit::input.date>` | Date picker (requires flatpickr) |
| `<x-toolkit::input.multi-select>` | Multi-select with tags (Alpine, self-contained) |
| `<x-toolkit::input.signature>` | Signature pad (requires signature_pad) |
| `<x-toolkit::input.file-upload>` | Drag-and-drop file upload with progress |
| `<x-toolkit::input.check-all-rows>` | Select-all checkbox for data tables |

## License

MIT - see [LICENSE](LICENSE) for details.
