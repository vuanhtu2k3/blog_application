<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách bài viết mới nhất
        $posts = Post::latest('id');

        // Tìm kiếm theo từ khóa
        if ($request->get('keyword')) {
            $posts = $posts->where('title', 'like', '%' . $request->keyword . '%'); // Sửa 'name' thành 'title'
        }

        // Phân trang kết quả
        $posts = $posts->paginate(10);

        // Trả về view với danh sách bài viết
        return view('admin.posts.list', compact('posts'));
    }


    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required|unique:posts',
            'published_at' => 'required|date' // Sửa 'pulished_at' thành 'published_at' và thêm xác thực cho kiểu dữ liệu
        ]);

        if ($validator->passes()) {
            $post = new Post();
            $post->title = $request->title; // Sửa 'tilte' thành 'title' và lấy dữ liệu từ $request
            $post->content = $request->content;
            $post->published_at = $request->published_at;

            $post->save();
            Session::flash('success', 'Post created successfully!');

            return response()->json([
                'status' => true,
                'message' => 'Post added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        $post = Post::find($id);
        if (empty($post)) { // Sửa từ 'brand' thành 'post'
            Session::flash('error', 'Record not found');
            return redirect()->route('post.index'); // Sửa 'posts.index' thành 'post.index'
        }

        $data['post'] = $post;
        return view('admin.posts.edit', $data);
    }

    public function update($id, Request $request)
    {
        $post = Post::find($id);
        if (empty($post)) { // Sửa từ 'brand' thành 'post'
            Session::flash('error', 'Record not found');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'published_at' => 'required|date' // Thêm xác thực cho kiểu dữ liệu
        ]);

        if ($validator->passes()) {
            $post->title = $request->title; // Sửa 'tilte' thành 'title' và lấy dữ liệu từ $request
            $post->content = $request->content;
            $post->published_at = $request->published_at;
            $post->save();
            Session::flash('success', 'Post updated successfully!');

            return response()->json([
                'status' => true,
                'message' => 'Post updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        $post = Post::find($id);
        if (empty($post)) { // Sửa từ 'brand' thành 'post'
            Session::flash('error', 'Record not found');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $post->delete();

        return response()->json([
            'status' => true,
            'message' => 'Post deleted successfully'
        ]);
    }
}
