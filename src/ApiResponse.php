<?php

declare(strict_types=1);

namespace App;

/**
 * Framework-freie Antwort: Status-Code plus dekodierter Body. Damit laesst
 * sich die API ohne laufenden Webserver testen, genau wie ein Client sie saehe.
 */
final class ApiResponse
{
    public function __construct(
        public readonly int $status,
        public readonly array $body,
    ) {
    }
}
