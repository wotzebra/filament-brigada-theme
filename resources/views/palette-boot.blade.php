{{--
    Applies the stored palette before first paint, so there is no flash of the default.

    Rendered through `PanelsRenderHook::BODY_START`, which is inside <body> — the element
    the class goes on. Kept inline and synchronous for the same reason: deferring it means
    the first paint happens on the default palette.

    Takes its data from the plugin when the panel renders it, but falls back to the package
    defaults so it can also be included directly — an error page rendered by the exception
    handler sits outside the panel, and no render hook fires there.
--}}
@php
    $palettes ??= \Wotz\FilamentBrigadaTheme\Enums\Palette::values();
    $default ??= \Wotz\FilamentBrigadaTheme\Enums\Palette::tryFrom(
        (string) config('filament-brigada-theme.default_palette')
    )?->value ?? \Wotz\FilamentBrigadaTheme\Enums\Palette::BlueGreen->value;
@endphp

<script>
    (() => {
        const fallback = @js($default);
        const allowed = @js($palettes);
        let palette = null;

        try {
            palette = localStorage.getItem('brigadaPalette');
        } catch (error) {
            // Storage can be unavailable (private windows, blocked site data); fall back.
        }

        document.body.classList.add(allowed.includes(palette) ? palette : fallback);
    })();
</script>
