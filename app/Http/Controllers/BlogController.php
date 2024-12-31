<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(Auth::check()) {
            $categories = Category::get();
            return view('theme.blogs.create', compact('categories'));
        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request)
    {
        $data = $request->validated();
        // Image Uploading
        // 1- get image
        $image = $request->image;
        // 2- change it's current name
        $newImageName = time() . '-' . $image->getClientOriginalName();
        // 3- move image to my project folder: storage/app/public/blogs
        $image->move('storage/blogs', $newImageName);
        // 4- save new name to database record
        $data['image'] = $newImageName;
        $data['user_id'] = Auth::user()->id;
        
        // create new blog record
        Blog::create($data);

        return back()->with('blogCreateStatus', 'Blog Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        return view('theme.single-blog', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        if($blog->user_id == Auth::user()->id) {
            $categories = Category::get();
            return view('theme.blogs.edit', compact('categories', 'blog'));
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        if($blog->user_id == Auth::user()->id) {
            $data = $request->validated();
    
            if($request->hasFile('image')) {
                // Image Uploading
                // 0- delete old image
                Storage::delete("public/storage/blogs/$blog->image");
                // 1- get image
                $image = $request->image;
                // 2- change it's current name
                $newImageName = time() . '-' . $image->getClientOriginalName();
                // 3- move image to my project folder: storage/app/public/blogs
                $image->move('storage/blogs', $newImageName);
                // 4- save new name to database record
                $data['image'] = $newImageName;
            }
            // create new blog record
            $blog->update($data);
    
            return back()->with('blogUpdateStatus', 'Your Blog has been Updated Successfully!');
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        if($blog->user_id == Auth::user()->id) {
            Storage::delete("public/blogs/$blog->image");
            $blog->delete();
            return back()->with('blogDeleteStatus', 'Your Blog has been Deleted Successfully!');
        }
        abort(403);
    }

    
    /**
     * Display all user blogs.
     */
    public function myBlogs(Blog $blog)
    {
        if(!Auth::check()) {
            abort(403);
        }
        
        $blogs = Blog::where('user_id', Auth::user()->id)->paginate(10);
        return view('theme.blogs.my-blogs', compact('blogs'));
    }
}
