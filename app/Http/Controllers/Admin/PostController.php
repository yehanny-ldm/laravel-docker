<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Webpatser\Uuid\Uuid;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::paginate(10);
        return response()->json($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        print_r("create");
        exit;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStoreRequest $request)
    {

        $post = new Post;
        $post->title = $request->title;
        $post->content = $request->content_post;
        $post->user_id = Auth::id();
        $response = [
            "message" => "Post generado correctamente",
            "code" => 200
        ];

        if (!$post->save()) {
            $response['message'] = "Ha ocurrido un error al guardar";
            $response['code'] = 502;
        }

        return Response($response, $response['code']);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Post::where("id", $id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'image' => 'required',
        ]);

        $image = $request->file('image');

        $imageContent = file_get_contents($image);
        $extension = $image->getClientOriginalExtension();

        $name = Uuid::generate()->string . '.' . $extension;
        Storage::disk('public')->put("images/$name", $imageContent);

        $post = Post::find($request->id);
        $post->image = $name;
        $post->save();

        $response = [
            "message" => "Post editado correctamente",
            "code" => 200,
            "fileName" => $name
        ];

        if (!$post->save()) {
            $response['message'] = "Ha ocurrido un error al editar";
            $response['status'] = 502;
        }

        return Response($response, $response['code']);


        return response()->json(['success' => 'You have successfully uploaded "' . $file_name . '"']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $post = Post::find($id);

        $post->title = $request->title;
        $post->content = $request->content_post;

        $response = [
            "message" => "Post editado correctamente",
            "code" => 200
        ];

        if (!$post->save()) {
            $response['message'] = "Ha ocurrido un error al editar";
            $response['status'] = 502;
        }

        return Response($response, $response['code']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        $response = [
            "message" => "Post editado correctamente",
            "code" => 200
        ];

        if (!$post->delete()) {
            $response['message'] = "Ha ocurrido un error al editar";
            $response['status'] = 502;
        }

        return Response($response, $response['code']);
    }
}
