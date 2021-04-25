<?php

namespace App\Http\Controllers\Admin;


use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentValidation;
use App\Post;
use Illuminate\Http\Request;

class CommentsController extends Controller
{

    /**
     * CommentsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentValidation $request)
    {
        $comment = new Comment();
        $comment->body = $request->body;
        $comment->user_id = $request->user_id;
        $comment->post_id = $request->post_id;

        $response = [
            "message" => "Comentario generado correctamente",
            "code" => 200
        ];

        if (!$comment->save()) {
            $response['message'] = "Ha ocurrido un error al guardar";
            $response['code'] = 502;
        }

        return Response($response, $response['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function show($id_post)
    {
        return Post::find($id_post)->comments;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
