// Minimal Electron host for the NativePHP API server.
// Skips the artisan-coupled bootstrap entirely — we just want to expose
// the real /api/* endpoints so the PHPNomad client can hit them and
// trigger real Electron behavior (notifications, windows).

const { app } = require('electron');
const { startAPI } = require('@nativephp/electron-plugin/dist/server');
const state = require('@nativephp/electron-plugin/dist/server/state').default;

const SECRET = 'phpnomad-experiment-secret';

async function main() {
    await app.whenReady();

    state.randomSecret = SECRET;
    const { port } = await startAPI();
    state.electronApiPort = port;

    // Emit the connection info as a single JSON line so PHP can parse it.
    process.stdout.write(
        JSON.stringify({
            apiUrl: `http://127.0.0.1:${port}/api/`,
            secret: SECRET,
        }) + '\n'
    );

    // Keep the app running. macOS would normally quit on window-all-closed
    // but we never opened a window, so just wait for SIGINT/SIGTERM.
    app.on('window-all-closed', (e) => {
        // Do nothing — keep alive.
    });
}

main().catch((err) => {
    console.error('electron host failed:', err);
    process.exit(1);
});
