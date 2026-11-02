<?php

declare(strict_types=1);

namespace App;

/**
 * Minimaler Validator: prueft einen Antwort-Body gegen das Response-Schema
 * aus einer OpenAPI-Beschreibung (required, type, enum).
 *
 * Bewusst klein gehalten. In Produktion nimmt man league/openapi-psr7-validator,
 * das die volle OpenAPI-Semantik samt $ref abdeckt. Die Idee ist dieselbe:
 * die ECHTE Antwort gegen den VERTRAG pruefen.
 */
final class SchemaValidator
{
    private array $spec;

    public function __construct(string $openApiJsonPath)
    {
        $this->spec = json_decode((string) file_get_contents($openApiJsonPath), true, flags: JSON_THROW_ON_ERROR);
    }

    public function validate(string $path, string $method, int $status, array $body): void
    {
        $schema = $this->spec['paths'][$path][strtolower($method)]['responses'][(string) $status]
            ['content']['application/json']['schema'] ?? null;

        if ($schema === null) {
            throw new ContractViolation("Kein Schema fuer {$method} {$path} ({$status}) in der Spec");
        }

        $this->checkObject($schema, $body);
    }

    private function checkObject(array $schema, array $body): void
    {
        foreach ($schema['required'] ?? [] as $field) {
            if (!array_key_exists($field, $body)) {
                throw new ContractViolation("Pflichtfeld fehlt in der Antwort: '{$field}'");
            }
        }

        foreach ($schema['properties'] ?? [] as $name => $prop) {
            if (!array_key_exists($name, $body)) {
                continue; // optional
            }
            $this->checkType($name, $prop, $body[$name]);
        }
    }

    private function checkType(string $name, array $prop, mixed $value): void
    {
        $type = $prop['type'] ?? null;
        $ok = match ($type) {
            'integer' => is_int($value),
            'number'  => is_int($value) || is_float($value),
            'string'  => is_string($value),
            'boolean' => is_bool($value),
            'array'   => is_array($value) && array_is_list($value),
            'object'  => is_array($value),
            default   => true,
        };
        if (!$ok) {
            throw new ContractViolation("Feld '{$name}' hat den falschen Typ, erwartet {$type}");
        }

        if (isset($prop['enum']) && !in_array($value, $prop['enum'], true)) {
            $allowed = implode(', ', $prop['enum']);
            throw new ContractViolation("Feld '{$name}' hat einen unerlaubten Wert, erlaubt sind: {$allowed}");
        }
    }
}
