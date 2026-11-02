# Monat 8 – API & Contract Testing

Übung der DC AG Testing-Schulungsserie.

## Worum geht es

Eine kleine Order-API wird über HTTP-Semantik getestet (Status, Body) und gegen
ihre OpenAPI-Spezifikation geprüft (der Vertrag). Ihr erlebt, wie ein
Contract-Test einen Formatbruch fängt, den ein reiner Statustest übersieht.

Die Aufgabenstellung steht in **AUFGABE.md**.

## Struktur

```
src/
  OrderApi.php          framework-freie Order-API (fertig)
  SchemaValidator.php   prüft eine Antwort gegen openapi.json (fertig)
  openapi.json          die OpenAPI-Beschreibung (der Vertrag)
  ApiResponse.php, ContractViolation.php

tests/
  Api.suite.yml
  Api/OrderApiTest.php  Startpunkt zum Ausfüllen
```

## Setup

```bash
composer install
vendor/bin/codecept build
vendor/bin/codecept run Api
```

Voraussetzung: PHP 8.2+.
