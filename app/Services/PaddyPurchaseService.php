<?php

namespace App\Services;

use App\Models\PaddyPurchase;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaddyPurchaseService
{
    public function __construct(private InventoryService $inventory) {}

    /** @param array<string, mixed> $data */
    public function create(array $data, ?int $userId): PaddyPurchase
    {
        return $this->inventory->transaction(function () use ($data, $userId): PaddyPurchase {
            $product = $this->inventory->lockProducts([(int) $data['product_id']])->firstOrFail();
            $total = $this->total((float) $data['quantity'], (float) $data['unit_price']);
            $paid = round((float) ($data['paid_amount'] ?? 0), 2);
            $this->assertPaymentDoesNotExceedTotal($paid, $total);

            $purchase = PaddyPurchase::query()->create([
                ...Arr::only($data, [
                    'supplier_id', 'product_id', 'purchase_date', 'quantity',
                    'moisture_percentage', 'unit_price', 'notes',
                ]),
                'purchase_number' => 'PUR-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6)),
                'total_amount' => $total,
                'paid_amount' => $paid,
                'payment_status' => $this->paymentStatus($paid, $total),
                'created_by' => $userId,
            ]);

            $this->inventory->change(
                $product,
                (float) $purchase->quantity,
                'purchase_in',
                $purchase,
                $userId,
                "Purchase received: {$purchase->purchase_number}",
            );

            return $purchase->load(['supplier', 'product']);
        });
    }

    /** @param array<string, mixed> $data */
    public function update(PaddyPurchase $purchase, array $data, ?int $userId): PaddyPurchase
    {
        return $this->inventory->transaction(function () use ($purchase, $data, $userId): PaddyPurchase {
            $purchase = PaddyPurchase::query()->lockForUpdate()->findOrFail($purchase->id);
            $products = $this->inventory->lockProducts([
                (int) $purchase->product_id,
                (int) $data['product_id'],
            ]);
            $oldProduct = $products->get($purchase->product_id);
            $newProduct = $products->get((int) $data['product_id']);

            $this->inventory->change(
                $oldProduct,
                -(float) $purchase->quantity,
                'adjustment',
                $purchase,
                $userId,
                "Purchase stock reversed for update: {$purchase->purchase_number}",
            );

            $total = $this->total((float) $data['quantity'], (float) $data['unit_price']);
            $paid = round((float) ($data['paid_amount'] ?? 0), 2);
            $this->assertPaymentDoesNotExceedTotal($paid, $total);

            $purchase->update([
                ...Arr::only($data, [
                    'supplier_id', 'product_id', 'purchase_date', 'quantity',
                    'moisture_percentage', 'unit_price', 'notes',
                ]),
                'total_amount' => $total,
                'paid_amount' => $paid,
                'payment_status' => $this->paymentStatus($paid, $total),
            ]);

            $this->inventory->change(
                $newProduct,
                (float) $purchase->quantity,
                'purchase_in',
                $purchase,
                $userId,
                "Purchase received after update: {$purchase->purchase_number}",
            );

            return $purchase->load(['supplier', 'product']);
        });
    }

    public function delete(PaddyPurchase $purchase, ?int $userId): void
    {
        $this->inventory->transaction(function () use ($purchase, $userId): void {
            $purchase = PaddyPurchase::query()->lockForUpdate()->findOrFail($purchase->id);
            $product = $this->inventory->lockProducts([(int) $purchase->product_id])->firstOrFail();

            $this->inventory->change(
                $product,
                -(float) $purchase->quantity,
                'adjustment',
                $purchase,
                $userId,
                "Purchase voided: {$purchase->purchase_number}",
            );

            $purchase->delete();
        });
    }

    private function total(float $quantity, float $unitPrice): float
    {
        return round($quantity * $unitPrice, 2);
    }

    private function paymentStatus(float $paid, float $total): string
    {
        return match (true) {
            $paid <= 0 => 'unpaid',
            $paid >= $total => 'paid',
            default => 'partial',
        };
    }

    private function assertPaymentDoesNotExceedTotal(float $paid, float $total): void
    {
        if ($paid > $total) {
            throw ValidationException::withMessages([
                'paid_amount' => 'The paid amount may not exceed the transaction total.',
            ]);
        }
    }
}
