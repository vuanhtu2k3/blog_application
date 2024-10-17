<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class UserController extends Controller
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
        return view('admin.posts.index', compact('posts'));
    }
}
