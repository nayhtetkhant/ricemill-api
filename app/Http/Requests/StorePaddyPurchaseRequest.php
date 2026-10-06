<?php

namespace App\Http\Requests;

use App\Enums\ProductType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StorePaddyPurchaseRequest extends FormRequest
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
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'product_id' => ['required', 'integer', Rule::exists('products', 'id')->where('type', ProductType::RawMaterial->value)],
            'purchase_date' => ['required', 'date'],
            'quantity' => ['required', 'numeric', 'decimal:0,2', 'gt:0', 'max:9999999999.99'],
            'moisture_percentage' => ['nullable', 'numeric', 'decimal:0,2', 'between:0,100'],
            'unit_price' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999999.99'],
            'paid_amount' => ['sometimes', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999999.99'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->keys() !== []) {
                return;
            }

            $total = round((float) $this->input('quantity') * (float) $this->input('unit_price'), 2);

            if ($total > 9999999999.99) {
                $validator->errors()->add('quantity', 'The calculated purchase total exceeds the supported maximum.');

                return;
            }

            if ((float) $this->input('paid_amount', 0) > $total) {
                $validator->errors()->add('paid_amount', 'The paid amount may not exceed the transaction total.');
            }
        }];
    }
}
