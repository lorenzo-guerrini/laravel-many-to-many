<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Post;
use App\Category;
use App\Tag;

class PostController extends Controller
{
    protected $validation = [
        'title' => 'required|max:255',
        'content' => 'required',
        'category_id' => 'nullable|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,bmp,png|max:2040',
        'tags' => 'exists:tags,id'
    ];

    protected function getSlug($title = "", $id = "")
    {
        $tmpSlug = Str::slug($title);
        $count = 1;
        while (Post::where('slug', $tmpSlug)->where('id', '!=', $id)->first()) {
            $tmpSlug = Str::slug($title) . "-" . $count;
            $count++;
        }
        return $tmpSlug;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        $tags = Tag::all();

        return view('admin.posts.index', compact('posts', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->validation);

        $form_data = $request->all();

        if (isset($form_data['image'])) {
            $img_path = Storage::put('uploads', $form_data['image']);
            $form_data['image'] = $img_path;
        }

        // $slug = Str::slug($form_data['title']);
        // $count = 1;
        // while (Post::where('slug', $slug)->first()) {
        //     $slug = Str::slug($form_data['title']) . "-" . $count;
        //     $count++;
        // }

        // $form_data['slug'] = $slug;
        $form_data['slug'] = $this->getSlug($form_data["title"]);
        $new_post = new Post();

        $new_post->fill($form_data);
        $new_post->save();

        $new_post->tags()->sync($form_data['tags']);

        return redirect()->route('admin.posts.index')->with(['msg' => '<div class="alert alert-success" role="alert">Comic succefully added</div>']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if (!$post) {
            abort(404);
        }

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate($this->validation);

        $form_data = $request->all();

        if (isset($form_data['image'])) {
            $img_path = Storage::put('uploads', $form_data['image']);
            $form_data['image'] = $img_path;
        }

        // if ($post->title == $form_data['title']) {
        //     $slug = $post->slug;
        // } else {
        //     $slug = Str::slug($form_data['title']);
        //     $count = 1;
        //     while (Post::where('slug', $slug)->where('id', '!=', $post->id)->first()) {
        //         $slug = Str::slug($form_data['title']) . "-" . $count;
        //         $count++;
        //     }
        // }
        // $form_data['slug'] = $slug;
        $form_data["slug"] = ($post->title == $form_data['title']) ? $post->slug : $this->getSlug($form_data["title"], $post->id);

        $post->update($form_data);

        //Se $form_data['tags'] è settato ne fa il sync, altrimenti lo fa con un array vuoto
        $post->tags()->sync(isset($form_data['tags']) ? $form_data['tags'] : []);

        return redirect()->route('admin.posts.index')->with(['msg' => '<div class="alert alert-success" role="alert">Comic succefully updated</div>']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with(['msg' => '<div class="alert alert-success" role="alert">Comic succefully deleted</div>']);
    }
}
