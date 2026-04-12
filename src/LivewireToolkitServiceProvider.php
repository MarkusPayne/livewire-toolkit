<?php

declare(strict_types=1);

namespace MarkusPayne\LivewireToolkit;

use Illuminate\Support\ServiceProvider;

final class LivewireToolkitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'toolkit');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/toolkit'),
            ], 'livewire-toolkit-views');

            $this->publishes([
                __DIR__.'/../stubs/claude/rules/livewire-toolkit.md' => base_path('.claude/rules/livewire-toolkit.md'),
            ], 'livewire-toolkit-claude-rules');

            $this->publishes([
                __DIR__.'/../resources/css/toolkit.css' => resource_path('css/vendor/toolkit.css'),
            ], 'livewire-toolkit-css');
        }
    }
}
