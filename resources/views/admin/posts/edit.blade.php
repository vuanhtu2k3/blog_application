@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Post</h1>
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
                <form id="editPostForm" method="POST">
                    @csrf
                    @method('PUT') <!-- Specify that this is a PUT request -->
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control"
                                value="{{ $post->title }}" required>
                            <div class="text-danger" id="titleError"></div>
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea name="content" id="content" class="form-control" rows="5" required>{{ $post->content }}</textarea>
                            <div class="text-danger" id="contentError"></div>
                        </div>
                        <div class="form-group">
                            <label for="published_at">Published At</label>
                            <input type="datetime-local" name="published_at" id="published_at" class="form-control"
                                value="{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('Y-m-d\TH:i') : '' }}">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Post</button>
                        <button type="reset" class="btn btn-default" onclick="confirmReset(event)">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        function confirmReset(event) {
            if (!confirm("Are you sure you want to reset the form? This will clear all fields.")) {
                event.preventDefault(); // Prevent the reset if the user cancels
            }
        }

        $("#editPostForm").submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting normally
            var form = $(this);
            $("button[type=submit]").prop('disabled', true); // Disable the submit button

            $.ajax({
                url: '{{ route('post.update', $post->id) }}', // Make sure this route exists
                type: 'POST',
                data: form.serialize(), // Serialize form data
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Include CSRF token in headers
                },
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false); // Re-enable the submit button
                    if (response.status) {
                        alert('Post updated successfully!');
                        window.location.href = "{{ route('post.index') }}"; // Redirect to posts index
                    } else {
                        // Handle errors if any
                        alert('An error occurred: ' + response.message);
                    }
                },
                error: function(xhr) {
                    $("button[type=submit]").prop('disabled', false); // Re-enable the submit button
                    var errors = xhr.responseJSON.errors;
                    $('#titleError').text(''); // Clear previous errors
                    $('#contentError').text(''); // Clear previous errors
                    // Handle validation errors and other issues
                    if (errors.title) {
                        $('#titleError').text(errors.title[0]); // Display title error
                    }
                    if (errors.content) {
                        $('#contentError').text(errors.content[0]); // Display content error
                    }
                }
            });
        });
    </script>
@endsection
