<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = SubCategory::where('status', 1)->get();
        return view('admin.subcategories.subcategory-index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $list = Category::where('status', 1)->get();
        return view('admin.subcategories.subcategory-create', compact('list'));
    }

    /**
     * Store a newly created resource in storage.
     */
    private function generateSlug($string)
    {
        return Str::slug($string);
    }
    public function store(StoreSubCategoryRequest $request)
    {
        try {
            $slug = $this->generateSlug($request->name);
            $getCategory = Category::findOrFail($request->category_id);
            $subcate = SubCategory::create([
                'name' => $request->name,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'category_name' => $getCategory->name,
            ]);
            $subcate->save();
            //Khi loại sản phẩm tăng thì trường "subcategory_count" trong bảng categories tăng +1
            $getCategory->subcategory_count += 1;
            $getCategory->save();
            return redirect()->route('subcategories.index')->with('success', 'Thêm loại sản phẩm thành công!');
        } catch (\Exception $e) {
            return redirect()->route('subcategories.index')->with('error', 'Thêm loại sản phẩm không thành công!');
        }
    }

    /**
     * Display the specified resource.
     */
    //Show loại sản phẩm trên trang bán hàng
    // public function show(SubCategory $subcategory)
    // {
    //     //
    // }
    protected function fixImage(Product $p)
    {
        if ($p->image && Storage::disk("public")->exists($p->image)) {
            $p->image = Storage::url($p->image);
        } else {
            $p->image = '/image/no-pictures.png';
        }
    }
    //Show lên trang bán hàng
    public function show($categorySlug, $subcategorySlug, Request $request)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $subcategory = SubCategory::where('slug', $subcategorySlug)->where('category_id', $category->id)->firstOrFail();
        $list = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1);
        }])->where('status', 1)->get();
        // Lấy sản phẩm theo danh mục và sắp xếp theo giá nếu có yêu cầu
        $query = Product::where('subcategory_id', $subcategory->id)
            ->where('status', 1)
            ->join('product_details', 'products.id', '=', 'product_details.product_id')
            ->distinct();

        if ($request->has('sort')) {
            if ($request->sort == 'price_asc') {
                $query->orderBy('product_details.sale_price', 'asc');
            } elseif ($request->sort == 'price_desc') {
                $query->orderBy('product_details.sale_price', 'desc');
            }
        }

        $listProduct = $query->select('products.*')->get();

        foreach ($listProduct as $p) {
            $this->fixImage($p);
        }

        return view('subcategory-pages.subcategory-show', compact('category', 'subcategory', 'listProduct', 'list'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subcategory)
    {
        $list = Category::all();
        return view('admin.subcategories.subcategory-edit', ['subcate' => $subcategory, 'list' => $list]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubCategoryRequest $request, SubCategory $subcategory)
    {
        try {
            $slug = $this->generateSlug($request->name);
            $getCategory = Category::findOrFail($request->category_id);
            $subcategory->update([
                'name' => $request->name,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'category_name' => $getCategory->name,
                'status' => $request->status,
            ]);
            $subcategory->save();
            return redirect()->route('subcategories.index')->with('success', 'Cập nhật loại sản phẩm thành công!');
        } catch (\Exception $e) {
            // dd($subcategory);
            dd('Failed to update SubCategory', ['error' => $e->getMessage(), 'subCategory' => $subcategory]);
            return redirect()->route('subcategories.index')->with('error', 'Cập nhật loại sản phẩm không thành công!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        //
    }
}
