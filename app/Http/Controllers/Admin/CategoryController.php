<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.categories.index')->only('index');
        $this->middleware('can:admin.categories.create')->only('create', 'store');
        $this->middleware('can:admin.categories.edit')->only('edit', 'update');
        $this->middleware('can:admin.categories.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index() //: Response
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() //:Response
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //: RedirectResponse
    {
        $request->validate([
            'name'  =>  'required',
            'slug'  =>  'required|unique:categories',
        ]);

        $category = Category::create($request->all());

        return redirect()->route('admin.categories.edit', $category)->with('info', 'Category saved successfully!');
    }


    public function edit(Category $category) //: Response
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category) //: RedirectResponse
    {
        $request->validate([
            'name'  =>  'required',
            'slug'  =>  'required|unique:categories,slug,' . $category->id, //slug field unique no repeat!
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories.edit', $category)->with('info', 'Category updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category) //: RedirectResponse
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('Category deleted successfully!');
    }
}
