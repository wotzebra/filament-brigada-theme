<?php

namespace Wotz\FilamentBrigadaTheme\Enums;

/**
 * The six brio quadtone palettes. The case value is the class the theme puts on <body>;
 * every colour in the panel derives from the four stops that class declares.
 */
enum Palette: string
{
    case PurpleRed = 'brio-01';

    case PinkRed = 'brio-02';

    case YellowPink = 'brio-03';

    case GreenYellow = 'brio-04';

    case BlueGreen = 'brio-05';

    case OrangePurple = 'brio-06';

    public function getLabel(): string
    {
        return match ($this) {
            self::PurpleRed => __('filament-brigada-theme::theme.palettes.purple_red'),
            self::PinkRed => __('filament-brigada-theme::theme.palettes.pink_red'),
            self::YellowPink => __('filament-brigada-theme::theme.palettes.yellow_pink'),
            self::GreenYellow => __('filament-brigada-theme::theme.palettes.green_yellow'),
            self::BlueGreen => __('filament-brigada-theme::theme.palettes.blue_green'),
            self::OrangePurple => __('filament-brigada-theme::theme.palettes.orange_purple'),
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
