<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Sidebar defaults
    |--------------------------------------------------------------------------
    |
    | Defaults for <x-toolkit::sidebar.index>. The component reads these
    | values when its `mode` or `side` props are omitted.
    |
    | mode: 'slideover' (overlay drawer toggled by a hamburger) or
    |       'fixed'     (persistent column at lg: + slide-over below lg:)
    |
    | side: 'left' or 'right'
    |
    */

    'sidebar' => [
        'default_mode' => 'slideover',
        'default_side' => 'left',
    ],

];
