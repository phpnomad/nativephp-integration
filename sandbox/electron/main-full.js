// Full PHPNomad × NativePHP bootstrap.
//
// Differences from main.js:
//   • Uses the real `@nativephp/electron-plugin` bootstrap() (patched to
//     read NATIVEPHP_PHP_* env vars and to skip Laravel-only setup steps).
//   • Points the plugin at our PHPNomad bin/nomad CLI and the PHPNomad
//     front controller in sandbox/app/public/index.php.
//   • After PHP comes up, the plugin auto-opens a window on the served URL.

const path = require('path');

const APP_DIR = path.resolve(__dirname, '../app');
const PHP_BIN = process.env.PHP_BIN || '/usr/bin/php';

// These have to be set BEFORE requiring the plugin — its compiled php.js
// reads them at module-load time.
Object.assign(process.env, {
    NODE_ENV: 'development',
    APP_PATH: APP_DIR,
    NATIVEPHP_PHP_BOOT_BIN: path.join(APP_DIR, 'bin', 'nomad'),
    NATIVEPHP_PHP_CONFIG_CMD: 'native:config',
    NATIVEPHP_PHP_PHPINI_CMD: 'native:php-ini',
    NATIVEPHP_SERVER_SCRIPT: path.join(APP_DIR, 'public', 'index.php'),
    NATIVEPHP_SERVER_CWD: path.join(APP_DIR, 'public'),
    NATIVEPHP_SKIP_LARAVEL_SETUP: '1',
    NATIVE_PHP_SKIP_QUEUE: '1',
});

const { app, BrowserWindow } = require('electron');
const nativePHP = require('@nativephp/electron-plugin');
const state = require('@nativephp/electron-plugin/dist/server/state').default;
const { startAPI } = require('@nativephp/electron-plugin/dist/server');

async function main() {
    // The plugin's bootstrap() spawns PHP, fetches config, starts the API
    // server, etc. Hand it a placeholder icon (a tiny PNG would do; the
    // empty path is tolerated on Linux dev mode).
    const icon = path.join(APP_DIR, 'public', 'favicon.png');
    const cert = '';

    nativePHP.bootstrap(app, icon, PHP_BIN, cert);

    // Once PHP is serving, open a window pointing at it. The plugin's API
    // server is also live by then. We discover both via `state`.
    app.on('ready', () => {
        const wait = setInterval(() => {
            if (state.phpPort && state.electronApiPort) {
                clearInterval(wait);
                console.log(
                    JSON.stringify({
                        apiUrl: `http://127.0.0.1:${state.electronApiPort}/api/`,
                        secret: state.randomSecret,
                        phpUrl: `http://127.0.0.1:${state.phpPort}/`,
                    })
                );

                // Open a window on the PHPNomad page. We hit /api/window/open
                // via state.electronApiPort would also work, but BrowserWindow
                // direct is simpler for the boot moment.
                const win = new BrowserWindow({
                    width: 900,
                    height: 700,
                    title: 'PHPNomad × NativePHP',
                    webPreferences: { nodeIntegration: false, contextIsolation: true },
                });
                win.loadURL(`http://127.0.0.1:${state.phpPort}/`);
                state.windows['main'] = win;

                // Forward focus events into PHP so we can see Electron→PHP
                // events flow end-to-end.
                const { notifyLaravel } = require('@nativephp/electron-plugin/dist/server/utils');
                win.on('focus', () => {
                    notifyLaravel('events', {
                        event: '\\Native\\Laravel\\Events\\Windows\\WindowFocused',
                        payload: ['main'],
                    });
                });
            }
        }, 200);
    });

    app.on('window-all-closed', () => {
        // Keep alive so we can inspect.
    });
}

main().catch((err) => {
    console.error('electron host failed:', err);
    process.exit(1);
});
