@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create New Post</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('post.index') }}" class="btn btn-secondary">Back to Posts</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <form id="createPostForm" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="published_at">Published At</label>
                            <input type="datetime-local" name="published_at" id="published_at" class="form-control">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Create Post</button>
                        <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $("#createPostForm").submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting normally
            var form = $(this);
            $("button[type=submit]").prop('disabled', true); // Disable the submit button

            $.ajax({
                url: '{{ route('post.store') }}',
                type: 'POST',
                data: form.serialize(), // Serialize form data
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false); // Re-enable the submit button
                    if (response.status) {
                        // Show success message and redirect if needed
                        alert('Post created successfully!');
                        window.location.href = "{{ route('post.index') }}"; // Redirect to posts index
                    } else {
                        // Handle errors if any
                        alert('An error occurred: ' + response.message);
                    }
                },
                error: function(xhr) {
                    $("button[type=submit]").prop('disabled', false); // Re-enable the submit button
                    // Handle validation errors and other issues
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '\n'; // Concatenate error messages
                    });
                    alert(errorMessage || 'An unexpected error occurred.');
                }
            });
        });
    </script>
@endsection
