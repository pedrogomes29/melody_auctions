<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.category_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'photo' => 'required|image|mimes:jpg,png,jpeg',
            'name' => 'required'
        ]);
        $category = new Category();
        $category->id = Category::max('id') + 1;
        $category->name = $request->input('name');
        $category->photo = 'storage/' . $request->file('photo')->store('image', 'public');;
        $category->save();
        return redirect()->route('adminDashboard', Auth::guard('admin')->user()->username);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('pages.category_update')
               ->with('name',Category::find($id)->name)
               ->with('id',$id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'photo' => 'sometimes|image|mimes:jpg,png,jpeg',
            'name' => 'required'
        ]);
        $category = Category::find($id);
        $category->name = $request->input('name');
        $category->photo = 'storage/' . $request->file('photo')->store('image', 'public');;
        $category->save();
        return redirect()->route('adminDashboard', Auth::guard('admin')->user()->username);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
 
        $category->delete();

        return redirect()->route('adminDashboard', Auth::guard('admin')->user()->username);

    }
}
