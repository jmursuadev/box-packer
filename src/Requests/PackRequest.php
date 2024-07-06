<?php

namespace Jmursuadev\BoxPacker\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Jmursuadev\BoxPacker\Product;

class PackRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'products' => 'required|array',
            'products.*.width' => 'required|numeric',
            'products.*.height' => 'required|numeric',
            'products.*.length' => 'required|numeric',
            'products.*.weight' => 'required|numeric',
            'products.*.quantity' => 'required|numeric'
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $products = $this->get('products', []);
                foreach ($products as $key => $product) {
                    try {
                        boxpacker()->getSmallestBoxForProduct(Product::fromArray($product));
                    } catch (\Exception $e) {
                        $validator->errors()->add(
                            'products.' . $key,
                            sprintf('Product Row %d: %s', $key + 1, $e->getMessage())
                        );
                    }
                }
            }
        ];
    }
}
