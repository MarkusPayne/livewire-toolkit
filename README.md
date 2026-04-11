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
| `<x-toolkit::modal.large>` | Large modal with backdrop, close button, event-driven open/close |
| `<x-toolkit::form>` | 12-column CSS grid wrapper for form layouts |
| `<x-toolkit::close>` | Close/X SVG icon |

## License

MIT - see [LICENSE](LICENSE) for details.
