<?php

namespace Wotz\FilamentBrigadaTheme\Providers;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentBrigadaThemeServiceProvider extends PackageServiceProvider
{
    protected const PACKAGE_NAME = 'filament-brigada-theme';

    public function configurePackage(Package $package): void
    {
        $package
            ->name($this->packageName())
            ->setBasePath(__DIR__ . '/../')
            ->hasConfigFile()
            ->hasViews($this->packageName())
            ->hasTranslations();
    }

    public function packageName(): string
    {
        return self::PACKAGE_NAME;
    }
}
