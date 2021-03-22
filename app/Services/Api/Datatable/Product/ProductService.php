<?php

namespace App\Services\Api\Datatable\Product;

use Auth;
use Datatables;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductService
{
    public function handle(Request $request)
    {
        $get_product = Product::whereNull('deleted_at');
        if ($request->is_active) {
            if ($request->is_active == "true") {
                $get_product = Product::whereNull('deleted_at');
            } else {
                $get_product = Product::withTrashed();
            }
        }

        $get_product->where('company_id', $request->company_id ? $request->company_id : null);
        $products = $get_product->orderBy('created_at', 'desc')->get();

        return Datatables::of($products)
            ->addColumn('action', function ($products) {
                return view('datatable.product.link-action', compact('products'));
            })
            ->addColumn('product_name', function($row){
                return '<a class="title-link-table" style="color: #3ab5c6;"
                    title="Detail & Edit"
                    data-id="'.$row->id.'"
                    href="' . route('master.product.edit', ['product' => $row->id]) . '"
                >'
                    . $row->product_name .
                '</a>';
            })
            ->addColumn('type', function($row){
                $val_type = Product::getTypeOfProduct((int)$row->type);
                $class = Product::getTypeColorProduct((int)$row->type);
                return '<span class="badge badge-soft-'.$class.'">'.$val_type.'</span>';
            })
            ->addColumn('price', function($row){
                return '<div class="float-right">' . app('string.helper')->defFormatCurrency($row->price, "Rp ") . '</div>';
            })
            ->addColumn('status', function($row){
                $val_status = Product::getStatusOfProduct((int)$row->status);
                $class = Product::getStatusColorProduct((int)$row->status);
                return '<span class="badge badge-soft-'.$class.'">'.$val_status.'</span>';
            })
            ->addColumn('product_category', function($row){
                return app('data.helper')->getProductCategoryName($row->product_category_id, $row->company_id);
            })
            ->addColumn('company', function($row){
                return app('data.helper')->getCompanyName($row->company_id);
            })
            ->rawColumns(['action', 'product_name', 'price', 'type', 'status', 'product_category', 'company'])
            ->make(true);
    }
}
