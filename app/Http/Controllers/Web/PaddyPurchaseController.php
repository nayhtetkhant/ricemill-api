<?php

namespace App\Http\Controllers\Web;

use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaddyPurchaseRequest;
use App\Http\Requests\UpdatePaddyPurchaseRequest;
use App\Models\PaddyPurchase;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\PaddyPurchaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaddyPurchaseController extends Controller
{
    public function index(): View
    {
        $purchases = PaddyPurchase::query()
            ->with(['supplier:id,name', 'product:id,name,unit'])
            ->orderByDesc('purchase_date')
            ->orderByDesc('id')
            ->paginate(15);

        return view('purchases.index', ['purchases' => $purchases]);
    }

    public function create(): View
    {
        return view('purchases.create', $this->formOptions());
    }

    public function store(StorePaddyPurchaseRequest $request, PaddyPurchaseService $service): RedirectResponse
    {
        $purchase = $service->create($request->validated(), $request->user()->id);

        return to_route('purchases.show', $purchase)
            ->with('status', 'Purchase recorded successfully.');
    }

    public function show(PaddyPurchase $purchase): View
    {
        return view('purchases.show', [
            'purchase' => $purchase->load(['supplier', 'product']),
        ]);
    }

    public function edit(PaddyPurchase $purchase): View
    {
        return view('purchases.edit', [
            ...$this->formOptions(),
            'purchase' => $purchase,
        ]);
    }

    public function update(
        UpdatePaddyPurchaseRequest $request,
        PaddyPurchase $purchase,
        PaddyPurchaseService $service,
    ): RedirectResponse {
        $purchase = $service->update($purchase, $request->validated(), $request->user()->id);

        return to_route('purchases.show', $purchase)
            ->with('status', 'Purchase updated successfully.');
    }

    public function destroy(PaddyPurchase $purchase, PaddyPurchaseService $service): RedirectResponse
    {
        $service->delete($purchase, request()->user()->id);

        return to_route('purchases.index')
            ->with('status', 'Purchase voided and stock reversed.');
    }

    /** @return array<string, mixed> */
    private function formOptions(): array
    {
        return [
            'suppliers' => Supplier::query()->orderBy('name')->get(['id', 'name']),
            'products' => Product::query()
                ->where('type', ProductType::RawMaterial)
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'unit', 'current_stock', 'unit_price']),
        ];
    }
}
