# Filament Brigada theme

The Brigada house style as a Filament v5 panel theme: a soft, rounded, warm panel driven by
one of six brio quadtone palettes, with a palette switcher in the sidebar and an animated
palette ground on the login screen.

## Installation

```bash
composer require wotz/filament-brigada-theme
```

Import the stylesheet from your panel's theme, with the other `@import` rules at the top —
CSS drops an `@import` that follows any other rule:

```css
@import '../../../../vendor/wotz/filament-brigada-theme/resources/css/filament-brigada-theme.css';
```

Register the plugin on the panel:

```php
use Wotz\FilamentBrigadaTheme\Filament\BrigadaThemePlugin;

$panel->plugins([
    BrigadaThemePlugin::make(),
]);
```

Then `npm run build`.

## What the plugin applies

The stylesheet is written against a panel with no topbar, whose chrome all lives in the
sidebar, so the plugin sets that layout up for you:

| | |
|---|---|
| `topbar(false)` | Filament then moves global search, the notifications trigger and the user menu into the sidebar itself, and renders its own floating toggle for mobile |
| `userMenu(position: Sidebar)` | pinned explicitly, so it holds even if a panel puts its topbar back |
| `sidebarFullyCollapsibleOnDesktop()` | collapsing to an icon rail leaves a nav of unlabelled glyphs, so the sidebar slides away entirely |
| `sidebarWidth('18.5rem')` | the sidebar carries every panel control, so it needs more room than the default |
| `darkMode(false)` | a single light theme; there are no dark rules |
| `colors(primary: ink)` | the palette paints every solid primary surface, so the ramp only supplies what the CSS leaves alone |

## Configuration

```php
BrigadaThemePlugin::make()
    ->defaultPalette(Palette::GreenYellow)
    ->sidebarWidth('20rem')
    ->paletteSwitcher(false) // hide the switcher, keep the default palette
    ->layout(false)          // stylesheet only; arrange the panel yourself
```

Publish the config to set the same defaults application-wide:

```bash
php artisan vendor:publish --tag=filament-brigada-theme-config
```

## Palettes

Each palette is a class on `<body>` — `brio-01` … `brio-06` — declaring four stops that
everything else derives from. The choice is stored in `localStorage.brigadaPalette` and
applied before first paint, so reloading never flashes the default.

| Case | Class | |
|---|---|---|
| `Palette::PurpleRed` | `brio-01` | Purple & Red |
| `Palette::PinkRed` | `brio-02` | Pink & Red |
| `Palette::YellowPink` | `brio-03` | Yellow & Pink |
| `Palette::GreenYellow` | `brio-04` | Green & Yellow |
| `Palette::BlueGreen` | `brio-05` | Blue & Green (default) |
| `Palette::OrangePurple` | `brio-06` | Orange & Purple |

Status colours (success, info, warning, danger) are fixed across all six, so a badge keeps
its meaning whichever palette is on.

## Adding your own rows to the sidebar foot

The switcher sits in a block at the bottom of the sidebar, above the user row. Render into
`filament-brigada-theme::sidebar.utilities` to add your own rows there, and give them the
`fi-brigada-util` class to match:

```php
$panel->renderHook(
    'filament-brigada-theme::sidebar.utilities',
    fn () => view('filament.view-website-link'),
);
```

## Design tokens

The theme derives everything from the active palette and exposes it as custom properties,
so application-specific components can be styled in the same language:

| | |
|---|---|
| `--ground` `--surface` | page/sidebar ground, and the white cards that sit on it |
| `--ink` `--muted` `--faint` | reading ink and its two quieter steps |
| `--line` `--line-soft` `--fill` | hairlines and the tint used for hover and chips |
| `--accent` `--accent-soft` | the palette's accent, and the wash behind an active nav item |
| `--r-sm` `--r-md` `--r-lg` `--r-pill` | the radius scale |
| `--shadow-card` `--shadow-pop` | the two elevations |
| `--ease` | the house easing curve |

Because a custom property resolves on the element that declares it, the derivation lives on
`.fi-body` / `.fi-simple-layout` rather than `:root` — declaring it on `:root` would freeze
every palette to whichever one `:root` happened to see. A `:root` fallback is declared for
pages the palette class never reaches, such as an error page rendered by the exception
handler outside the panel.

## Licence

MIT. See [LICENSE.md](LICENSE.md).
