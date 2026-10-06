<?php

namespace App\Http\Controllers\Web;

use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(): View
    {
        $sales = Sale::query()
            ->with('customer:id,name')
            ->withCount('items')
            ->orderByDesc('sale_date')
            ->orderByDesc('id')
            ->paginate(15);

        return view('sales.index', ['sales' => $sales]);
    }

    public function create(): View
    {
        return view('sales.create', $this->formOptions());
    }

    public function store(StoreSaleRequest $request, SaleService $service): RedirectResponse
    {
        $sale = $service->create($request->validated(), $request->user()->id);

        return to_route('sales.show', $sale)
            ->with('status', 'Sale recorded successfully.');
    }

    public function show(Sale $sale): View
    {
        return view('sales.show', [
            'sale' => $sale->load(['customer', 'items.product']),
        ]);
    }

    public function edit(Sale $sale): View
    {
        return view('sales.edit', [
            ...$this->formOptions(),
            'sale' => $sale->load('items.product'),
        ]);
    }

    public function update(UpdateSaleRequest $request, Sale $sale, SaleService $service): RedirectResponse
    {
        $sale = $service->update($sale, $request->validated(), $request->user()->id);

        return to_route('sales.show', $sale)
            ->with('status', 'Sale updated successfully.');
    }

    public function destroy(Sale $sale, SaleService $service): RedirectResponse
    {
        $service->delete($sale, request()->user()->id);

        return to_route('sales.index')
            ->with('status', 'Sale voided and stock restored.');
    }

    /** @return array<string, mixed> */
    private function formOptions(): array
    {
        return [
            'customers' => Customer::query()->orderBy('name')->get(['id', 'name']),
            'products' => Product::query()
                ->whereIn('type', [ProductType::Finished, ProductType::ByProduct])
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'unit', 'current_stock', 'unit_price']),
        ];
    }
}
