<?php

declare(strict_types=1);

namespace App;

/**
 * Eine kleine Order-API ohne Framework. handle() nimmt Methode und Pfad und
 * liefert eine ApiResponse. In Produktion staeke das hinter einem Router und
 * einer echten Datenquelle; fuer die Uebung reicht ein fester Bestand.
 */
final class OrderApi
{
    /** @var array<int, array{id:int, status:string, amount:float}> */
    private array $orders = [
        1 => ['id' => 1, 'status' => 'paid',   'amount' => 49.90],
        2 => ['id' => 2, 'status' => 'open',   'amount' => 12.00],
    ];

    public function handle(string $method, string $path): ApiResponse
    {
        if ($method === 'GET' && preg_match('#^/orders/(\d+)$#', $path, $m)) {
            $id = (int) $m[1];
            if (isset($this->orders[$id])) {
                return new ApiResponse(200, $this->orders[$id]);
            }
            return new ApiResponse(404, ['error' => "Order {$id} not found"]);
        }

        return new ApiResponse(404, ['error' => 'Unknown route']);
    }
}
