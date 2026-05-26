#!/usr/bin/env node
// Stub of the NativePHP Electron API. Logs every authed request so we can
// verify the PHP-side integration produces the right wire format.

const http = require('http');

const SECRET = process.env.NATIVEPHP_SECRET || 'test-secret';
const PORT = parseInt(process.env.NATIVEPHP_API_PORT || '4200', 10);

const server = http.createServer((req, res) => {
    const provided = req.headers['x-nativephp-secret'];
    if (provided !== SECRET) {
        res.writeHead(403);
        res.end('forbidden');
        return;
    }

    let body = '';
    req.on('data', (chunk) => (body += chunk));
    req.on('end', () => {
        const parsed = body ? safeParse(body) : null;
        const line = JSON.stringify({
            method: req.method,
            path: req.url,
            body: parsed,
        });
        console.log(line);

        const respond = (status, payload) => {
            res.writeHead(status, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify(payload));
        };

        if (req.method === 'GET' && req.url === '/api/window/current') {
            return respond(200, {
                id: 'main',
                x: 0,
                y: 0,
                width: 800,
                height: 600,
                title: 'Stubbed',
                alwaysOnTop: false,
            });
        }

        respond(200, { ok: true });
    });
});

function safeParse(s) {
    try {
        return JSON.parse(s);
    } catch {
        return s;
    }
}

server.listen(PORT, '127.0.0.1', () => {
    console.error(`stub listening on http://127.0.0.1:${PORT}/api/ secret=${SECRET}`);
});
