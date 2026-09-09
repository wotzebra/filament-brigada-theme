<?php

use Wotz\FilamentBrigadaTheme\Enums\Palette;

return [

    /*
    |--------------------------------------------------------------------------
    | Default palette
    |--------------------------------------------------------------------------
    |
    | The palette a visitor sees before they have chosen one, and the fallback
    | for a stored choice that is no longer valid. One of the `Palette` cases.
    |
    */

    'default_palette' => Palette::BlueGreen->value,

    /*
    |--------------------------------------------------------------------------
    | Sidebar width
    |--------------------------------------------------------------------------
    |
    | The theme puts every panel control in the sidebar, so it needs more room
    | than Filament's default. Any CSS length.
    |
    */

    'sidebar_width' => '18.5rem',

];
