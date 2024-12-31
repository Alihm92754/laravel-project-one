<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function Store(StoreCommentRequest $request)
    {
        $data = $request->validated();
        Comment::create($data);
        return back()->with('CommentCreateStatus', 'Your Comment published successfully');
    }
}
