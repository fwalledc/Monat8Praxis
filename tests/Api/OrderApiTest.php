<?php

declare(strict_types=1);

namespace Tests\Api;

use App\ContractViolation;
use App\OrderApi;
use App\SchemaValidator;
use Codeception\Test\Unit;

/**
 * Startpunkt Monat 8 – API & Contract Testing.
 *
 * Teil 1: die API über handle(method, path) ansprechen, Status und Body prüfen.
 * Teil 2: die echte Antwort gegen das OpenAPI-Schema prüfen (der Vertrag).
 *
 * Die API, der SchemaValidator und src/openapi.json sind fertig. Ihr schreibt
 * die Tests. Siehe AUFGABE.md.
 */
final class OrderApiTest extends Unit
{
    private OrderApi $api;
    private SchemaValidator $contract;

    protected function _before(): void
    {
        $this->api = new OrderApi();
        $this->contract = new SchemaValidator(__DIR__ . '/../../src/openapi.json');
    }

    // PFLICHT: nur der Statuscode (der oberflächliche Test)
    public function testStatusIstOk(): void
    {
        $this->markTestIncomplete('GET /orders/1 soll 200 liefern');
    }

    // PFLICHT: der Fehlerfall
    public function testUnbekannteBestellungLiefert404(): void
    {
        $this->markTestIncomplete('GET /orders/999 soll 404 liefern');
    }

    // PFLICHT: der Vertrag
    public function testAntwortHaeltDenVertragEin(): void
    {
        $this->markTestIncomplete('Antwort gegen das OpenAPI-Schema validieren, keine ContractViolation erwartet');
    }

    // BONUS: ein umbenanntes Feld bricht den Vertrag
    public function testVertragsbruchWirdErkannt(): void
    {
        $this->markTestIncomplete('Body mit state statt status -> ContractViolation erwarten');
    }
}
