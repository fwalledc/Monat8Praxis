# Monat 8 – Praxisaufgabe: API & Contract Testing

> PHP 8.2+ / Codeception 5 / eigene Api-Suite

## Ausgangslage

Fertig vorgegeben ist eine kleine, framework-freie Order-API:

- `OrderApi::handle(string $method, string $path): ApiResponse` liefert Status
  und Body, zum Beispiel `handle('GET', '/orders/1')`
- `src/openapi.json` beschreibt die API (der Vertrag): welche Felder die Antwort
  haben muss, welche Typen, welche erlaubten Werte
- `SchemaValidator` prüft einen Body gegen dieses Schema und wirft eine
  `ContractViolation`, wenn etwas nicht passt

## Ziel

Schreibt in der Suite `Api` zwei Arten von Tests: klassische API-Tests auf
Status und Body, und Contract-Tests, die die echte Antwort gegen die
OpenAPI-Spezifikation prüfen.

## Schritt 1 – Status und Fehlerfall

- `testStatusIstOk`: `GET /orders/1` liefert Status 200.
- `testUnbekannteBestellungLiefert404`: `GET /orders/999` liefert 404, und der
  Body hat ein Feld `error`.

## Schritt 2 – Den Vertrag prüfen

- `testAntwortHaeltDenVertragEin`: Ruft `GET /orders/1` auf und gebt die
  Antwort an `SchemaValidator::validate('/orders/{id}', 'GET', $status, $body)`.
  Wenn keine `ContractViolation` fliegt, ist der Vertrag eingehalten.

## Schritt 3 (Bonus) – Den Vertragsbruch provozieren

- `testVertragsbruchWirdErkannt`: Baut von Hand einen Body, in dem das Feld
  `status` zu `state` umbenannt ist, und erwartet eine `ContractViolation`.

## Der Aha-Moment

Benennt am Ende in `OrderApi` das Feld `status` in `state` um und lasst die
Tests laufen. Ihr werdet sehen: `testStatusIstOk` bleibt grün (der Statuscode
ist ja weiter 200), aber der Contract-Test wird rot. Genau das ist der Punkt:
Ein Statuscode-Test allein merkt nicht, dass das Antwortformat kaputt ist.

## Ausführen

```bash
composer install
vendor/bin/codecept build
vendor/bin/codecept run Api
```

## Lernziel

Ein OpenAPI-Schema ist ein Vertrag, aber erst der Test, der die echte Antwort
dagegen prüft, macht ihn verbindlich. So bleibt eure API-Doku ehrlich, statt
still zu veralten.
