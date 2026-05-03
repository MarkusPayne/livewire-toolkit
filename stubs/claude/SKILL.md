---
name: livewire-toolkit
description: Reference for the markuspayne/livewire-toolkit Composer package — provides WithDataTable, WithSearch, HasSelectOptionsTrait traits and toolkit:: Blade components (data-table, table, modal, form, icon, input, button, menu). Use this skill whenever generating prompts, writing code, or planning features that involve datatables, searchable/filterable lists, model select options, modal forms, menus, or any Livewire component scaffolding. Also trigger when the user mentions "toolkit", "data table", "WithDataTable", "WithSearch", "HasSelectOptionsTrait", or references "x-toolkit::" blade components.
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
| `HasSelectOptionsTrait` | `MarkusPayne\LivewireToolkit\Model` | Generate `<select>` option arrays from any Eloquent model |

### Blade Components (all prefixed `toolkit::`)

| Component | Purpose |
|-----------|---------|
| `<x-toolkit::data-table.index>` | Table wrapper — per-page selector, pagination, optional export icon |
| `<x-toolkit::data-table.thead>` | Header group — manages Alpine sort state (`sortBy`, `sortDir`) |
| `<x-toolkit::data-table.tbody>` | Body wrapper |
| `<x-toolkit::data-table.tr>` | Row — supports `hasError`, `hasWarning`, `hasSuccess`, `textColor` |
| `<x-toolkit::data-table.th>` | Sortable header cell — accepts `sortField` prop |
| `<x-toolkit::data-table.td>` | Data cell — accepts `grow` prop |
| `<x-toolkit::table.index>` | Simple table wrapper — no sorting or pagination |
| `<x-toolkit::table.thead>` | Static header group (disables hover) |
| `<x-toolkit::table.header-group>` | Header group with configurable background |
| `<x-toolkit::table.tbody>` | Body wrapper |
| `<x-toolkit::table.tr>` | Row with conditional styling |
| `<x-toolkit::table.th>` | Header cell (no sort) |
| `<x-toolkit::table.td>` | Data cell |
| `<x-toolkit::modal.large>` | Large modal (`--breakpoint-2xl`) — title slot, body, optional footer |
| `<x-toolkit::modal.medium>` | Medium modal (`2xl`) — title slot, body, optional footer |
| `<x-toolkit::modal.small>` | Small modal (`lg`) — title slot, body, optional footer |
| `<x-toolkit::form>` | 12-column CSS grid form wrapper (`md:grid md:gap-6 md:grid-cols-12`) |
| `<x-toolkit::icon.close>` | X / close icon |
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
| `<x-toolkit::menu.index>` | Menu wrapper with Alpine state |
| `<x-toolkit::menu.button>` | Base trigger button |
| `<x-toolkit::menu.button-actions>` | "Actions" trigger with chevron icon |
| `<x-toolkit::menu.button-dots>` | Ellipsis dot trigger |
| `<x-toolkit::menu.items>` | Dropdown panel with positioning |
| `<x-toolkit::menu.item>` | Generic menu item — accepts optional `$icon` slot |
| `<x-toolkit::menu.item-edit>` | Edit item with edit icon |
| `<x-toolkit::menu.item-delete>` | Delete item with trash icon and confirm prompt |
| `<x-toolkit::menu.item-add>` | Add item with plus icon |
| `<x-toolkit::menu.item-download>` | Download item with download icon |
| `<x-toolkit::menu.close>` | Wrapper that closes menu on click |
| `<x-toolkit::sidebar.index>` | Sidebar shell — `mode='slideover'` (drawer) or `mode='fixed'` (full page layout); `side='left'\|'right'` |
| `<x-toolkit::sidebar.group>` | Collapsible nav group |
| `<x-toolkit::sidebar.link>` | Nav link with active detection |
| `<x-toolkit::logo>` | Logo image with text fallback |

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
    - Blade uses <x-toolkit::data-table.*> components (thead/tbody/tr/th/td)
    - Columns: [list columns with sortField where applicable]
    - Actions column with <x-toolkit::menu.*> dropdown

### Form component prompt template

    Create a Livewire MFC form component at:
    resources/views/livewire/{module}/{entity}/⚡{entity}-form/

    The component should:
    - Use a Form Object at app/Livewire/Forms/{Model}Form.php
    - Render inside <x-toolkit::modal.medium name="{action}-{entity}">
    - Include <x-slot:title>{Action} {Entity}</x-slot:title>
    - Include <x-slot:footer> with cancel and save buttons
    - Use <x-toolkit::form> for the 12-column grid layout
    - Use <x-toolkit::input.*> components for all form fields
    - Use <x-toolkit::button.primary> and <x-toolkit::button.secondary> for actions
    - Listen for #[On('create-{entity}')] and #[On('edit-{entity}')]
    - On save: dispatch 'refresh-{entity}' and 'close-modal'

## WithDataTable — Full Reference

### Required

    use MarkusPayne\LivewireToolkit\Concerns\WithDataTable;

    use WithDataTable;

    public string $tableName = 'unique_name';

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

- `$sortBy`, `$sortDir`, `$perPage`, `$perPageOptions`

## WithSearch — Full Reference

    use WithDataTable, WithSearch;

    public array $filters = [
        'name' => 'like',
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

## HasSelectOptionsTrait — Full Reference

Returns a plain array (not Collection). Cached with Redis tags, auto-flushed on save.

    $options = Category::getOptions();
    $options = Category::getOptions(['value' => 'slug']);
    $options = Category::getOptions(['activeOnly' => true]);
    $options = Category::getOptions(['cache' => false]);

## Modal Event Pattern

    1. Parent dispatches: x-on:click="$dispatch('edit-athlete', { athleteId: id })"
    2. Component listens: #[On('edit-athlete')]
    3. Component opens: $this->dispatch('open-modal', name: 'edit-athlete');
    4. On save: $this->dispatch('refresh-athlete'); $this->dispatch('close-modal', name: 'edit-athlete');
    5. IMPORTANT: x-on:click with $dispatch() — NEVER wire:click for dispatch-only

## Menu Usage

    <x-toolkit::menu.index>
        <x-toolkit::menu.button-actions />
        <x-toolkit::menu.items>
            <x-toolkit::menu.item-edit x-on:click="$dispatch('edit-model', { id: {{ $row->id }} })" />
            <x-toolkit::menu.item-delete wire:click="delete({{ $row->id }})" />
            <x-toolkit::menu.item-add x-on:click="$dispatch('create-child')">Add Child</x-toolkit::menu.item-add>
            <x-toolkit::menu.item-download wire:click="export({{ $row->id }})" />
        </x-toolkit::menu.items>
    </x-toolkit::menu.index>

Requires `@alpinejs/ui`. Shortcut items include icons and default labels.

## Sidebar Components

`<x-toolkit::sidebar.index>` has two modes selected by the `mode` prop:

- `mode="slideover"` (default) — overlay drawer toggled by a hamburger
- `mode="fixed"` — full page layout: persistent column at `lg:` + hamburger slide-over below

`side="left"` (default) or `side="right"` controls slide direction and persistent column position.
Defaults come from `config('livewire-toolkit.sidebar.*')`.

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

The component owns its own Alpine state. Open/close it by dispatching the
window events `toolkit-sidebar-open` / `toolkit-sidebar-close`. `Escape`
closes automatically. Do NOT add `x-data="{ sidebarOpen: false }"` on the
consuming layout's `<body>` — that pattern is gone.

### Fixed (page layout)

    <x-toolkit::sidebar.index mode="fixed" title="Page Title">
        <x-slot:logo>...</x-slot:logo>
        <x-slot:nav>
            <x-toolkit::sidebar.link href="...">...</x-toolkit::sidebar.link>
            <x-toolkit::sidebar.group label="...">...</x-toolkit::sidebar.group>
        </x-slot:nav>
        <x-slot:topbar>...</x-slot:topbar>

        {{-- page content (default slot) --}}
    </x-toolkit::sidebar.index>

Desktop: persistent sidebar (w-72) on the chosen side. Mobile: hamburger
in the topbar opens the slide-over. The `topbar` slot, `title` prop, and
default slot (page content) are only rendered in fixed mode.

Icons in nav items are passed via named `$icon` slots on
`<x-toolkit::sidebar.link>` and `<x-toolkit::sidebar.group>`.

## Common Mistakes to Prevent

1. Missing `toolkit::` prefix — components won't resolve
2. Missing `$tableName` — required for session key namespacing
3. Custom sort/paginate logic — use WithDataTable, never manual
4. Forms outside modals — all forms render inside `<x-toolkit::modal.large>`, `<x-toolkit::modal.medium>`, or `<x-toolkit::modal.small>`
5. Class-based Livewire components — always MFC anonymous classes in ⚡ directories
6. `wire:click` for dispatches — use `x-on:click="$dispatch(...)"` instead
7. Skipping `applySearch()` — WithSearch queries MUST go through `$this->applySearch()`
8. Using `<x-toolkit::form.grid>` — correct component is `<x-toolkit::form>`
9. Using old data-table names — use `thead`/`tbody`/`tr`, not `header`/`body`/`row`
10. Using `data-table.*` for static tables — use `table.*` instead
11. Raw HTML inputs — use `<x-toolkit::input.*>` components
12. Missing `<x-toolkit::input.group>` wrapper — provides label, error, col-span sizing
13. Forgetting JS deps — date needs flatpickr, signature needs SignaturePad
14. Missing toolkit CSS import in app.css
15. Inline SVGs for common icons — use `<x-toolkit::icon.*>` instead
16. Missing @alpinejs/ui for menus — x-menu directives require it
17. `getOptions()` returns array not Collection — don't call `->pluck()` on result
18. Hardcoding routes in sidebar — sidebar.index is a shell, routes go in consuming app
19. Using `<x-icons>` for sidebar icons — pass via named `$icon` slot instead
20. Adding `x-data="{ sidebarOpen: false }"` on `<body>` for the sidebar — the component owns its own state; dispatch `toolkit-sidebar-open` instead
21. Using `<x-toolkit::layout.sidebar>` — that component was removed; use `<x-toolkit::sidebar.index mode="fixed">` instead
22. Missing title slot on modals — always include `<x-slot:title>`
23. Using `modal.large` for simple forms — use `modal.medium` or `modal.small`
