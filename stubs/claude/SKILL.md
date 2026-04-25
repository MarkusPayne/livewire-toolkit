---
name: livewire-toolkit
description: Reference for the markuspayne/livewire-toolkit Composer package — provides WithDataTable, WithSearch, HasSelectOptionsTrait traits and toolkit:: Blade components (data-table, modal, form, close). Use this skill whenever generating prompts, writing code, or planning features that involve datatables, searchable/filterable lists, model select options, modal forms, or any Livewire component scaffolding. Also trigger when the user mentions "toolkit", "data table", "WithDataTable", "WithSearch", "HasSelectOptionsTrait", or references "x-toolkit::" blade components.
---

# livewire-toolkit Package Reference

## What This Skill Is For

This skill tells Claude how to correctly use the `markuspayne/livewire-toolkit` package when:
- Writing Claude Code prompts that involve table/list components
- Generating Livewire component code that needs datatables, search, or select options
- Planning features that involve CRUD listing pages or form modals
- Reviewing code that should be using toolkit conventions

## Package Contents

### PHP Traits/Concerns

| Trait | Namespace | Purpose |
|-------|-----------|---------|
| `WithDataTable` | `MarkusPayne\LivewireToolkit\Concerns` | Sort, paginate, per-page selector for any table |
| `WithSearch` | `MarkusPayne\LivewireToolkit\Concerns` | Filterable/searchable queries with multiple operator types |
| `HasSelectOptionsTrait` | `MarkusPayne\LivewireToolkit\Model` | Generate `<select>` option collections from any Eloquent model |

### Blade Components (all prefixed `toolkit::`)

| Component | Purpose |
|-----------|---------|
| `<x-toolkit::data-table.index>` | Table wrapper — per-page selector, pagination, optional export icon |
| `<x-toolkit::data-table.header>` | Header group — manages Alpine sort state (`sortBy`, `sortDir`) |
| `<x-toolkit::data-table.body>` | Body wrapper |
| `<x-toolkit::data-table.row>` | Row — supports `hasError`, `hasWarning`, `hasSuccess`, `textColor` |
| `<x-toolkit::data-table.th>` | Sortable header cell — accepts `sortField` prop |
| `<x-toolkit::data-table.td>` | Data cell — accepts `grow` prop |
| `<x-toolkit::table.index>` | Simple table wrapper — no sorting or pagination |
| `<x-toolkit::table.thead>` | Static header group (disables hover) |
| `<x-toolkit::table.header-group>` | Header group with configurable background |
| `<x-toolkit::table.tbody>` | Body wrapper |
| `<x-toolkit::table.tr>` | Row with conditional styling |
| `<x-toolkit::table.th>` | Header cell (no sort) |
| `<x-toolkit::table.td>` | Data cell |
| `<x-toolkit::modal.large>` | Large modal with backdrop, event-driven open/close via Alpine |
| `<x-toolkit::form>` | 12-column CSS grid form wrapper (`md:grid md:gap-6 md:grid-cols-12`) |
| `<x-toolkit::icon.close>` | X / close icon (used internally by modal, also standalone) |
| `<x-toolkit::icon.edit>` | Pencil / edit icon |
| `<x-toolkit::icon.delete>` | Trash / delete icon |
| `<x-toolkit::icon.add>` | Plus / add icon |
| `<x-toolkit::icon.download>` | Download icon |
| `<x-toolkit::icon.chevron-down>` | Chevron down arrow |
| `<x-toolkit::icon.ellipsis>` | Horizontal dots |
| `<x-toolkit::input.group>` | Form group with stacked label, error, help text |
| `<x-toolkit::input.group-inline>` | Inline form group (label and input side-by-side) |
| `<x-toolkit::input.error>` | Validation error message (requires `$focus` from @alpinejs/ui) |
| `<x-toolkit::input.text>` | Text/number/email input |
| `<x-toolkit::input.textarea>` | Textarea with configurable rows |
| `<x-toolkit::input.select>` | Select dropdown with optional placeholder |
| `<x-toolkit::input.checkbox>` | Checkbox input |
| `<x-toolkit::input.radio>` | Radio button with label |
| `<x-toolkit::input.toggle>` | Toggle/switch with label |
| `<x-toolkit::input.money>` | Money input with $ prefix and USD suffix |
| `<x-toolkit::input.yes-no>` | Yes/No option pair (use inside select) |
| `<x-toolkit::input.date>` | Date picker (requires `window.flatpickr`) |
| `<x-toolkit::input.multi-select>` | Multi-select with tags (Alpine, self-contained) |
| `<x-toolkit::input.signature>` | Signature pad (requires `window.SignaturePad`) |
| `<x-toolkit::input.file-upload>` | Drag-and-drop file upload with progress |
| `<x-toolkit::input.check-all-rows>` | Select-all checkbox for data tables |
| `<x-toolkit::button>` | Base button with loading state |
| `<x-toolkit::button.primary>` | Sky blue primary action button |
| `<x-toolkit::button.secondary>` | Gray secondary/cancel button |
| `<x-toolkit::button.danger>` | Red destructive action button |
| `<x-toolkit::button.link>` | Text-only link-styled button |

## Generating Prompts That Use The Toolkit

### Table component prompt template

    Create a Livewire MFC table component at:
    resources/views/livewire/{module}/{entity}/⚡{entity}-table/

    The component should:
    - use WithDataTable (from markuspayne/livewire-toolkit)
    - use WithSearch (if filterable)
    - tableName: '{entity_plural}'
    - tableQuery: {Model}::query() with necessary relationships
    - filters: { field: operator, ... }
    - tableSearchFields: [field1, field2]
    - Blade uses <x-toolkit::data-table.*> components
    - Columns: [list columns with sortField where applicable]

### Form component prompt template

    Create a Livewire MFC form component at:
    resources/views/livewire/{module}/{entity}/⚡{entity}-form/

    The component should:
    - Use a Form Object at app/Livewire/Forms/{Model}Form.php
    - Render inside <x-toolkit::modal.large name="{action}-{entity}">
    - Use <x-toolkit::form> for the 12-column grid layout
    - Listen for #[On('create-{entity}')] and #[On('edit-{entity}')]
    - On save: dispatch 'refresh-{entity}' and 'close-modal'

## WithDataTable — Full Reference

### Required

    use MarkusPayne\LivewireToolkit\Concerns\WithDataTable;

    // In anonymous class:
    use WithDataTable;

    public string $tableName = 'unique_name'; // Required — used for session persistence

    public function tableQuery(): Builder
    {
        return Model::query();
    }

### Optional Overrides

    protected function tableDefaults(): array
    {
        return [
            'sortBy' => 'name',
            'sortDir' => 'ASC',
            'perPage' => 25,
        ];
    }

### Provided Properties

- `$sortBy` — current sort column (wire:model bound)
- `$sortDir` — current sort direction (wire:model bound)
- `$perPage` — current per-page count (wire:model bound)
- `$perPageOptions` — array of per-page choices for the selector

## WithSearch — Full Reference

### Required alongside WithDataTable

    use MarkusPayne\LivewireToolkit\Concerns\WithSearch;

    // In anonymous class:
    use WithDataTable, WithSearch;

    public array $filters = [
        'name' => 'like',
        'email' => 'like',
        'status' => '=',
        'role_id' => 'in',
        'tag_id' => 'pivot',
        'score' => 'range',
        'bio' => 'match',
        'category_id' => 'hasIn',
    ];

    public array $tableSearchFields = ['name', 'email'];

    public function tableQuery(): Builder
    {
        return $this->applySearch(Model::query());
    }

### Filter Operators

| Operator | SQL | Notes |
|----------|-----|-------|
| `like` | `WHERE col LIKE %val%` | Case-insensitive substring |
| `match` | `MATCH(col) AGAINST(val)` | Requires FULLTEXT index |
| `=` | `WHERE col = val` | Exact match |
| `in` | `WHERE col IN (...)` | Array of values |
| `hasIn` | `whereHas` + `whereIn` | Filter through relationship |
| `pivot` | Pivot table filter | Many-to-many relationships |
| `range` | `WHERE col BETWEEN` | Expects `[min, max]` |
| Any SQL op | `WHERE col {op} val` | e.g. `>=`, `<`, `!=` |

## HasSelectOptionsTrait — Full Reference

    use MarkusPayne\LivewireToolkit\Model\HasSelectOptionsTrait;

    class Category extends Model
    {
        use HasSelectOptionsTrait;
    }

    // Basic — returns Collection keyed by id, valued by name
    $options = Category::getOptions();

    // Custom value field
    $options = Category::getOptions(['value' => 'slug']);

    // Active records only
    $options = Category::getOptions(['activeOnly' => true]);

    // With filters
    $options = Category::getOptions([
        'filters' => [
            ['field' => 'type', 'value' => 'team'],
        ],
    ]);

## Modal — Full Reference

### Large Modal

    <x-toolkit::modal.large name="edit-athlete">
        {{-- Content --}}
    </x-toolkit::modal.large>

Props:
- `name` (string, default: `'large-modal'`) — event identifier for open/close
- `title` (string, optional) — reserved for future header rendering

Alpine events:

    {{-- Open --}}
    x-on:click="$dispatch('open-modal', { name: 'edit-athlete' })"

    {{-- Close by name --}}
    x-on:click="$dispatch('close-modal', { name: 'edit-athlete' })"

    {{-- Close current (no name) --}}
    x-on:click="$dispatch('close-modal')"

The modal uses `@teleport('body')`, `x-trap.noscroll.inert`, and `x-cloak`.
Max width is `sm:max-w-(--breakpoint-2xl)`. Backdrop is `bg-black/50`.

## Form Grid — Full Reference

    <x-toolkit::form>
        {{-- Children use col-span-{n} classes --}}
    </x-toolkit::form>

Renders a `<form>` tag with `md:grid md:gap-6 md:grid-cols-12 items-start`.
Attributes are merged, so you can add `wire:submit` etc:

    <x-toolkit::form wire:submit="save">
        ...
    </x-toolkit::form>

## Modal Event Pattern

    1. Parent blade dispatches event:
       x-on:click="$dispatch('create-athlete')"
       x-on:click="$dispatch('edit-athlete', { athleteId: {{ $athlete->id }} })"

    2. Form component listens:
       #[On('create-athlete')]
       #[On('edit-athlete')]

    3. Component opens modal:
       $this->dispatch('open-modal', name: 'edit-athlete');

    4. On successful save, form component dispatches:
       $this->dispatch('refresh-athlete');
       $this->dispatch('close-modal', name: 'edit-athlete');

    5. IMPORTANT: Use x-on:click with $dispatch() — NEVER wire:click for dispatch-only

## Common Mistakes to Prevent

1. Missing `toolkit::` prefix — `<x-data-table.index>` won't resolve
2. Missing `$tableName` — required for session key namespacing
3. Custom sort/paginate logic — if WithDataTable is available, never write manual sort/paginate
4. Forms outside modals — all forms render inside `<x-toolkit::modal.large>`
5. Class-based Livewire components — always MFC anonymous classes in ⚡ directories
6. `wire:click` for dispatches — use `x-on:click="$dispatch(...)"` instead
7. Skipping `applySearch()` — if WithSearch is used, the query MUST go through `$this->applySearch()`
8. Using `<x-toolkit::form.grid>` — the correct component is `<x-toolkit::form>`
9. Using `<x-close />` — inside toolkit views, always use `<x-toolkit::icon.close />`
10. Using `data-table.*` for static tables — use `table.*` when you don't need
    sorting or pagination (detail views, inline data, relation tables)
11. Raw HTML inputs in forms — always use `<x-toolkit::input.*>` components
12. Missing `<x-toolkit::input.group>` wrapper — provides label, error, and col-span sizing
13. Forgetting JS dependencies — date needs flatpickr, signature needs SignaturePad
14. Missing toolkit CSS import — add `@import '../../vendor/markuspayne/livewire-toolkit/resources/css/toolkit.css' layer(base);` to app.css
15. Duplicating form element base styles — these come from the toolkit CSS, not the app
16. Inline SVGs for common icons — use <x-toolkit::icon.*> components instead
17. Using <x-toolkit::close /> — moved to <x-toolkit::icon.close />
