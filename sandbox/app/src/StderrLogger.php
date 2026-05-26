<?php

namespace SandboxApp;

use Exception;
use PHPNomad\Logger\Enums\LoggerLevel;
use PHPNomad\Logger\Interfaces\LoggerStrategy;

/**
 * Minimal logger. Writes to stderr, structured per line. Good enough for
 * a desktop PoC; a real app should use phpnomad/logger with a file or
 * structured-log backend (e.g. Monolog adapter).
 */
class StderrLogger implements LoggerStrategy
{
    public function emergency(string $message, array $context = []): void { $this->write('emergency', $message, $context); }
    public function alert(string $message, array $context = []): void     { $this->write('alert', $message, $context); }
    public function critical(string $message, array $context = []): void  { $this->write('critical', $message, $context); }
    public function error(string $message, array $context = []): void     { $this->write('error', $message, $context); }
    public function warning(string $message, array $context = []): void   { $this->write('warning', $message, $context); }
    public function notice(string $message, array $context = []): void    { $this->write('notice', $message, $context); }
    public function info(string $message, array $context = []): void      { $this->write('info', $message, $context); }
    public function debug(string $message, array $context = []): void     { $this->write('debug', $message, $context); }

    public function logException(Exception $e, string $message = '', array $context = [], string $level = null)
    {
        $this->write($level ?: 'error', $message !== '' ? $message : $e->getMessage(), $context + [
            'exception' => $e::class,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
    }

    /** @param array<string, mixed> $context */
    private function write(string $level, string $message, array $context): void
    {
        fwrite(STDERR, sprintf(
            "[%s] %s: %s%s\n",
            date('c'),
            strtoupper($level),
            $message,
            $context === [] ? '' : ' ' . json_encode($context, JSON_UNESCAPED_SLASHES)
        ));
    }
}
