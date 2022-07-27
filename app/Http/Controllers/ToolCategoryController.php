<?php

namespace App\Http\Controllers;

use App\Models\ToolCategory;
use Illuminate\Http\Request;

class ToolCategoryController extends Controller
{
    public function index()
    {
        $categories = user()->company->toolCategories()->orderDefault()->paginate(25);

        return view('tool_categories.index', compact('categories'));
    }

    public function store()
    {
        $v = request()->validate([
            'name' => 'required|string|max:255',
        ]);

        ToolCategory::createCategory(user()->company, $v['name']);

        return back()->with('success', 'saved');
    }

    public function destroy($tool_category_id)
    {
        $toolCategory = user()->company->toolCategories()->find($tool_category_id);
        if ($toolCategory->trashed()) {
            abort(400, 'Already deleted');
        }
        $toolCategory->delete();

        return back()->with('success', 'deleted');
    }
}
