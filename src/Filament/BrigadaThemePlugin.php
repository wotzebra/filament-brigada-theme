<?php

namespace Wotz\FilamentBrigadaTheme\Filament;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Enums\UserMenuPosition;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Wotz\FilamentBrigadaTheme\Enums\Palette;

/**
 * The theme is not only a stylesheet: it is written against a panel with no topbar, whose
 * chrome all lives in the sidebar. Applying that layout here is what makes the package
 * drop-in — every part of it can be turned off for a panel that arranges itself otherwise.
 */
class BrigadaThemePlugin implements Plugin
{
    protected Palette | Closure | null $defaultPalette = null;

    protected string | Closure | null $sidebarWidth = null;

    protected bool | Closure $hasLayout = true;

    protected bool | Closure $hasPaletteSwitcher = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-brigada-theme';
    }

    public function register(Panel $panel): void
    {
        /*
         * Applied before first paint so the stored palette is on <body> when the first
         * pixel is drawn; deferring it flashes the default.
         */
        $panel->renderHook(
            PanelsRenderHook::BODY_START,
            fn (): string => view('filament-brigada-theme::palette-boot', [
                'palettes' => Palette::values(),
                'default' => $this->getDefaultPalette()->value,
            ])->render(),
        );

        if ($this->hasPaletteSwitcher()) {
            $panel->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_END,
                fn (): string => view('filament-brigada-theme::palette-switcher', [
                    'palettes' => Palette::cases(),
                    'default' => $this->getDefaultPalette()->value,
                ])->render(),
            );
        }

        if (! $this->hasLayout()) {
            return;
        }

        $panel
            /*
             * The stylesheet paints every solid primary surface from the active palette, so
             * the ramp only has to supply what it leaves alone — the Livewire progress bar,
             * checkbox fills, indeterminate states. Ink is the neutral those read as under
             * all six palettes.
             */
            ->colors(['primary' => Color::hex('#181614')])
            /*
             * A single light theme: there are no dark rules, so the toggle could only ever
             * produce an unstyled panel.
             */
            ->darkMode(false)
            /*
             * With no topbar, Filament moves global search, the notifications trigger and
             * the user menu into the sidebar by itself, and renders its own floating toggle
             * for mobile. The user menu is pinned explicitly so that stays true even if a
             * consuming panel puts its topbar back.
             */
            ->topbar(false)
            ->userMenu(position: UserMenuPosition::Sidebar)
            /*
             * Collapsing to an icon rail leaves a nav of unlabelled glyphs, so the sidebar
             * slides away entirely instead.
             */
            ->sidebarFullyCollapsibleOnDesktop()
            ->sidebarWidth($this->getSidebarWidth());
    }

    public function boot(Panel $panel): void {}

    public function defaultPalette(Palette | Closure | null $palette): static
    {
        $this->defaultPalette = $palette;

        return $this;
    }

    public function getDefaultPalette(): Palette
    {
        $palette = $this->defaultPalette instanceof Closure
            ? ($this->defaultPalette)()
            : $this->defaultPalette;

        return $palette
            ?? Palette::tryFrom((string) config('filament-brigada-theme.default_palette'))
            ?? Palette::BlueGreen;
    }

    public function sidebarWidth(string | Closure | null $width): static
    {
        $this->sidebarWidth = $width;

        return $this;
    }

    public function getSidebarWidth(): string
    {
        $width = $this->sidebarWidth instanceof Closure
            ? ($this->sidebarWidth)()
            : $this->sidebarWidth;

        return $width ?? (string) config('filament-brigada-theme.sidebar_width', '18.5rem');
    }

    /**
     * Turn off for a panel that keeps its own topbar and sidebar arrangement. The
     * stylesheet still applies, but it assumes this layout, so expect to redo parts of it.
     */
    public function layout(bool | Closure $condition = true): static
    {
        $this->hasLayout = $condition;

        return $this;
    }

    public function hasLayout(): bool
    {
        return (bool) ($this->hasLayout instanceof Closure
            ? ($this->hasLayout)()
            : $this->hasLayout);
    }

    public function paletteSwitcher(bool | Closure $condition = true): static
    {
        $this->hasPaletteSwitcher = $condition;

        return $this;
    }

    public function hasPaletteSwitcher(): bool
    {
        return (bool) ($this->hasPaletteSwitcher instanceof Closure
            ? ($this->hasPaletteSwitcher)()
            : $this->hasPaletteSwitcher);
    }
}
