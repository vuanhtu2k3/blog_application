@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Posts</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('post.create') }}" class="btn btn-primary">New Post</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <form action="" method="get">
                    <div class="card-header">
                        <div class="card-title">
                            <button type="button" onclick="window.location.href='{{ route('post.index') }}'"
                                class="btn btn-default btn-sm">Reset</button>
                        </div>
                        <div class="card-tools">
                            <div class="input-group" style="width: 250px;">
                                <input value="{{ Request::get('keyword') }}" type="text" name="keyword"
                                    class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Published At</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($posts->isNotEmpty())
                                @foreach ($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>{{ $post->title }}</td>
                                        <td>{{ $post->content }}</td>
                                        <td>{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('Y-m-d H:i') : 'Not published' }}
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('post.edit', $post->id) }}" class="btn btn-edit"
                                                    aria-label="Edit">
                                                    <span class="action-text">Sửa</span> <!-- Text for edit -->
                                                </a>
                                                <a href="#" onclick="deletePost({{ $post->id }})"
                                                    class="btn btn-delete" aria-label="Delete">
                                                    <span class="action-text">Xóa</span> <!-- Text for delete -->
                                                </a>
                                            </div>
                                        </td>

                                        <style>
                                            .action-buttons {
                                                display: flex;
                                                gap: 10px;
                                                /* Space between the buttons */
                                            }

                                            .btn {
                                                display: inline-flex;
                                                align-items: center;
                                                /* Center the icon vertically */
                                                justify-content: center;
                                                width: 36px;
                                                /* Width for uniform button size */
                                                height: 36px;
                                                /* Height for uniform button size */
                                                border-radius: 4px;
                                                /* Rounded corners */
                                                transition: background-color 0.3s, transform 0.3s;
                                                /* Smooth transition */
                                            }

                                            .btn-edit {
                                                background-color: #007bff;
                                                /* Bootstrap primary color */
                                                color: white;
                                            }

                                            .btn-edit:hover {
                                                background-color: #0056b3;
                                                /* Darker blue on hover */
                                                transform: scale(1.05);
                                                /* Slight scale effect on hover */
                                            }

                                            .btn-delete {
                                                background-color: #dc3545;
                                                /* Bootstrap danger color */
                                                color: white;
                                            }

                                            .btn-delete:hover {
                                                background-color: #c82333;
                                                /* Darker red on hover */
                                                transform: scale(1.05);
                                                /* Slight scale effect on hover */
                                            }
                                        </style>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">Records not found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        function deletePost(id) {
            var url = '{{ route('post.delete', 'ID') }}';
            var newUrl = url.replace("ID", id);
            if (confirm("Are you sure you want to delete this post?")) {
                $.ajax({
                    url: newUrl,
                    type: 'delete',
                    data: {},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status) {
                            window.location.href = "{{ route('post.index') }}";
                        } else {
                            alert('An error occurred while deleting the post.');
                        }
                    }
                });
            }
        }
    </script>
@endsection
