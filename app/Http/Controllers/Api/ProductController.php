<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;

use App\Models\Product;

class ProductController extends Controller
{
    protected $product;
    protected $request;

    public function __construct(
        Request $request,
        Product $product
    ){
        $this->request = $request;
        $this->product = $product;
    }

    public function getProduct(Request $request)
    {
        $response = [
            'status' => true,
            'message' => 'success.'
        ];

        $product = $this->product->get();

        $response['result'] = $product;

        return response()->json($response);
    }

    public function getProductDetail(Request $request)
    {
        $response = [
            'status' => true,
            'message' => 'success.'
        ];

        $product = $this->product->get();

        $response['result'] = $product;

        return response()->json($response);
    }

    public function createProduct(Request $request)
    {
        $response = [
            'status' => true,
            'message' => 'success.'
        ];

        $params = $request->json()->all();

        $validator = \Validator::make($params, 
            [
                'name' => 'required',
                'price' => 'required'
            ]
        );

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => $validator->error()
            ];

            return response()->json($response);
        }

        $this->product->name = $params['name'];
        $this->product->price = $params['price'];
        $this->product->quantity = $params['quantity'];

        if(!$this->product->save()){
            $response = [
                'status' => false,
                'message' => 'error.'
            ];

            return response()->json($response);
        }

        $response['result'] = $this->product;

        return response()->json($response);

    }

    public function updateProduct(Request $request, $id)
    {
        $response = [
            'status' => true,
            'message' => 'success.'
        ];

        $product = $this->product->find($id);

        if(!$product){
            $response = [
                'status' => false,
                'message' => 'ถูกลบไปแล้ว.'
            ];

            return response()->json($response);
        }

        $params = $request->json()->all();

        $product->name = $params['name'];
        $product->price = $params['price'];
        $product->quantity = $params['quantity'];

        if(!$product->save()){
            $response = [
                'status' => false,
                'message' => 'Error.'
            ];

            return response()->json($response);
        }

        $response['result'] = $product;

        return response()->json($response);
    }

    public function deleteProduct(Request $request, $id)
    {
        $response = [
            'status' => true,
            'message' => 'success.'
        ];

        $product = $this->product->find($id);

        if(!$product->delete()){
            $response = [
                'status' => false,
                'message' => 'Error.'
            ];

            return response()->json($response);
        }

        return response()->json($response);
    }
}
