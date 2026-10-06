<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'sale_date' => ['required', 'date'],
            'paid_amount' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999999.99'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'array', 'list', 'min:1', 'max:100'],
            'items.*' => ['required', 'array:product_id,quantity,unit_price'],
            'items.*.product_id' => ['required', 'integer', 'distinct', Rule::exists('products', 'id')->whereIn('type', ['FINISHED', 'BY_PRODUCT'])],
            'items.*.quantity' => ['required', 'numeric', 'decimal:0,2', 'gt:0', 'max:9999999999.99'],
            'items.*.unit_price' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999999.99'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->keys() !== []) {
                return;
            }

            $total = round(array_sum(array_map(
                fn (array $item): float => round((float) $item['quantity'] * (float) $item['unit_price'], 2),
                $this->input('items', []),
            )), 2);

            if ($total > 9999999999.99) {
                $validator->errors()->add('items', 'The calculated sale total exceeds the supported maximum.');

                return;
            }

            if ((float) $this->input('paid_amount', 0) > $total) {
                $validator->errors()->add('paid_amount', 'The paid amount may not exceed the transaction total.');
            }
        }];
    }
}
