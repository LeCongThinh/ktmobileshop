<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lst = Category::where('status', 1)->get();
        return view('admin.categories.category-index', ['lst' => $lst]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.category-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    private function generateSlug($string)
    {
        return Str::slug($string);
    }
    public function store(StoreCategoryRequest $request)
    {
        try {
            $slug = $this->generateSlug($request->name);
            $cate = Category::create([
                'name' => $request->name,
                'slug' => $slug,
            ]);
            $cate->save();
            return redirect()->route('categories.index')->with('success', 'Thêm danh mục thành công!');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Thêm danh mục không thành công!');
        }

    }

    /**
     * Display the specified resource.
     */
    //Show danh mục sản phẩm
    // public function show(Category $category)
    // {

    // }
    protected function fixImage(Product $p)
    {
        if ($p->image && Storage::disk("public")->exists($p->image)) {
            $p->image = Storage::url($p->image);
        } else {
            $p->image = '/image/no-pictures.png';
        }
    }
    public function show($categorySlug, Request $request)
    {
        // Lấy danh mục dựa trên slug
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        // Lấy các loại sản phẩm thuộc danh mục thông qua quan hệ trong Model
        $subCategories = $category->subCategories()->where('status', 1)->get();
        // Lấy tất cả categories để hiển thị danh sách
        $list = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1);
        }])->where('status', 1)->get();

        // Lấy sản phẩm theo danh mục và sắp xếp theo giá nếu có yêu cầu
        $query = $category->products()
            ->where('products.status', 1)
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
        return view('category-pages.category-show', compact('category', 'subCategories', 'list', 'listProduct'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.category-edit', ['cate' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $slug = $this->generateSlug($request->name);
            $category->update([
                'name' => $request->name,
                'slug' => $slug,
                'status' => $request->status,
            ]);
            $category->save();
            return redirect()->route('categories.index')->with('success', 'Cập nhật danh mục thành công!');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Cập nhật danh mục không thành công!');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
