<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{

    public function index()
    {
        $user = Session::get('user');
        $types = Product::types();
        $products = Product::where('group_id', $user->group_id)->get();
        return view('product.index', compact('user', 'products', 'types'));
    }
    public function store(Request $request)
    {
        $groupId = Session::get('user')->group_id;
        $request->validate([
            'product_type' => 'required',
            'product_name' => 'required',
            'product_company' => 'required',
            'product_details' => 'required',
        ]);

        $product = new Product([
            'group_id' => $groupId,
            'product_type' => $request->input('product_type'),
            'product_name' => $request->input('product_name'),
            'product_company' => $request->input('product_company'),
            'product_details' => $request->input('product_details'),
        ]);
        $product->save();

        if ($request->ajax()) {
            return response()->json([
                'data' => $product,
            ]);
        } else {

            if ($product) {
                return redirect()->back()->with('success', $product->product_type . ' ' . $product->product_name . ' Created Successfuly');
            }
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'pk'    => 'required|integer|exists:products,id',
            'name'  => 'required|string',
            'value' => 'required',
        ]);

        if ($request->ajax()) {
            Product::findOrFail($request->pk)->update([
                $request->name => $request->value,
            ]);

            $formatName = ucwords(str_replace('_', ' ', $request->name));

            return response()->json([
                'name'  => $formatName,
                'value' => $request->value,
            ]);
        }
    }

    public function checkName(Request $request)
    {
        $exists = Product::where('product_company', $request->company)
            ->where('product_name', $request->name)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('product.index')->with('error', $product->product_type . ' ' . $product->product_name . ' Product Not Found.');
        }
        if ($product->purchases()->exists()) {
            return redirect()->route('product.index')->with('error', $product->prduct_type . ' ' . $product->product_name . ' cannot be deleted it has purchanse');
        }

        $product->delete();
        return redirect()
            ->route('product.index')
            ->with('success', $product->product_type . ' ' . $product->product_name . ' Deleted successfully.');
    }
    public function getProduct($type)
    {
        $products = Product::where('product_type', $type)->get(['id', 'product_name']);
        return response()->json($products);
    }
}
