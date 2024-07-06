<?php

namespace Jmursuadev\BoxPacker\Controllers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Jmursuadev\BoxPacker\Product;
use Jmursuadev\BoxPacker\Requests\PackRequest;

class BoxPackerController extends Controller
{
    public function index()
    {
        return view('boxpacker::index');
    }

    public function pack(PackRequest $request)
    {
        try {
            $products = $request->get('products', []);
            $products = collect($products)->map(function ($product) {
                return new Product($product['length'], $product['width'], $product['height'], $product['weight'], $product['quantity']);
            });

            return response()->json([
                'data' => boxpacker()->setProducts(collect($products))->pack()->getData()
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'products' => [
                        $e->errors()
                    ]
                ]
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'products' => [
                        $e->getMessage()
                    ]
                ]
            ], 422);
        }
    }
}
