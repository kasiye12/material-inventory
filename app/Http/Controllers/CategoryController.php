<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('categories.index');
    }

    public function create()
    {
        return redirect()->route('categories.index');
    }

    public function getData()
    {
        $categories = Category::query();
        
        return DataTables::of($categories)
            ->addColumn('items_count', function($cat) {
                return $cat->items()->count();
            })
            ->addColumn('actions', function($cat) {
                return $cat->id;
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:categories,code',
        ]);

        Category::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully!'
        ]);
    }

    public function show($id)
    {
        return response()->json(Category::findOrFail($id));
    }

    public function edit($id)
    {
        return response()->json(Category::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:categories,code,'.$id,
        ]);
        $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted!']);
    }
}
