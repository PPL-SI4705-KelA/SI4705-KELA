<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;

abstract class DuskTestCase extends BaseTestCase
{
    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        if (! static::runningInSail()) {
            static::startChromeDriver(['--port=9515']);
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        // Use a dedicated, throwaway Chrome profile so the automated browser does
        // NOT attach to an already-running Chrome session (which makes ChromeDriver
        // lose its browser and crash with "Couldn't connect to server"). This keeps
        // the browser visible for the pause()-based demo while staying isolated.
        $userDataDir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'dusk-chrome-'.getmypid();

        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
            '--user-data-dir='.$userDataDir,
            '--no-first-run',
            '--no-default-browser-check',
        ])->unless($this->hasHeadlessDisabled(), function ($items) {
            return $items->merge([
                // Uncomment to run without a visible window (e.g. on CI):
                // '--disable-gpu',
                // '--headless=new',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }
}
