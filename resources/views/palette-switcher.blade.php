{{--
    Palette switcher, rendered at the foot of the sidebar. Swapping the class on <body>
    reskins the panel immediately — every colour derives from the four stops that class
    declares, so there is nothing to reload.

    Anything else belonging in this block can be added through the `filament-brigada-theme::
    sidebar.utilities` render hook below, and given the `fi-brigada-util` class to match.
--}}
<div class="fi-brigada-utilities">
    {{ \Filament\Support\Facades\FilamentView::renderHook('filament-brigada-theme::sidebar.utilities') }}

    <div
        x-data="{
            palettes: @js(array_column($palettes, 'value')),
            fallback: @js($default),
            current: @js($default),
            open: false,
            init() {
                let stored = null

                try {
                    stored = localStorage.getItem('brigadaPalette')
                } catch (error) {
                    //
                }

                this.current = this.palettes.includes(stored) ? stored : this.fallback
                this.apply()
            },
            select(palette) {
                this.current = palette

                try {
                    localStorage.setItem('brigadaPalette', palette)
                } catch (error) {
                    //
                }

                this.apply()
                this.open = false
            },
            apply() {
                document.body.classList.remove(...this.palettes)
                document.body.classList.add(this.current)
            },
        }"
        class="fi-brigada-palette"
    >
        <button
            type="button"
            x-on:click="open = ! open"
            x-bind:aria-expanded="open"
            aria-haspopup="menu"
            class="fi-brigada-util fi-brigada-palette-trigger"
        >
            <span class="fi-brigada-palette-swatch" x-bind:class="current"></span>
            {{ __('filament-brigada-theme::theme.theme') }}
        </button>

        <div
            x-show="open"
            x-cloak
            x-on:click.outside="open = false"
            role="menu"
            class="fi-brigada-palette-panel"
        >
            @foreach ($palettes as $palette)
                <button
                    type="button"
                    role="menuitemradio"
                    x-on:click="select(@js($palette->value))"
                    x-bind:aria-checked="current === @js($palette->value)"
                    x-bind:aria-current="current === @js($palette->value)"
                    class="fi-brigada-palette-option {{ $palette->value }}"
                >
                    <span class="fi-brigada-palette-swatch"></span>
                    {{ $palette->getLabel() }}
                </button>
            @endforeach
        </div>
    </div>
</div>
