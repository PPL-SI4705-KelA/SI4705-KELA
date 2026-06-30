<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;

$opts = (new ChromeOptions)->addArguments([
    '--no-sandbox', '--disable-dev-shm-usage', '--window-size=1920,1080',
    '--user-data-dir='.sys_get_temp_dir().'/dbg-'.getmypid(),
]);
$caps = DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $opts);
$driver = RemoteWebDriver::create('http://localhost:9515', $caps);

try {
    $driver->get("http://127.0.0.1:8000/_dusk/login/1/web");
    $driver->get('http://127.0.0.1:8000/admin/chat/43');
    sleep(3);

    $info = $driver->executeScript(<<<'JS'
        const btn = document.getElementById('chat-submit');
        if (!btn) return {found:false};
        const r = btn.getBoundingClientRect();
        const cx = r.left + r.width/2, cy = r.top + r.height/2;
        const atCenter = document.elementFromPoint(cx, cy);
        return {
            found: true,
            rect: {top: Math.round(r.top), left: Math.round(r.left), w: Math.round(r.width), h: Math.round(r.height)},
            viewport: {w: window.innerWidth, h: window.innerHeight},
            inViewport: (cy >= 0 && cy <= window.innerHeight && cx >=0 && cx <= window.innerWidth),
            elementAtCenter: atCenter ? (atCenter.id || atCenter.tagName + '.' + atCenter.className).slice(0,60) : 'NULL',
            centerIsButtonOrChild: atCenter ? (atCenter === btn || btn.contains(atCenter)) : false,
        };
    JS);
    echo "chat-submit on ADMIN page: " . json_encode($info, JSON_PRETTY_PRINT) . "\n";
} finally {
    $driver->quit();
}
