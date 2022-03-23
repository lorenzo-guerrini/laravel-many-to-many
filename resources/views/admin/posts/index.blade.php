@extends('layouts.admin')

@section('title', 'All Posts')

@section('content')
    <div class="col-md-12">
        <div class="pb-3">
            <a href="{{ route('admin.posts.create') }}" class="btn btn-success">New Post</a>
        </div>

        <table class="table">
            <thead>
                <tr class="text-center">
                    <th>ID</th>
                    <th class="col-3">Title - Slug</th>
                    <th class="col-1">Category</th>
                    <th class="col-1">Tags</th>
                    <th class="col-1">Image</th>
                    <th class="col-4">Content</th>
                    <th class="text-center col-2">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        {{-- ID --}}
                        <td>{{ $post->id }}</td>

                        {{-- Tittle --}}
                        <td>
                            <div><strong>{{ $post->title }}</strong></div>
                            <div>({{ $post->slug }})</div>
                        </td>

                        {{-- Category --}}
                        <td class="text-center">
                            {{ $post->category ? $post->category->name : '/' }}
                        </td>

                        {{-- Tag --}}
                        <td class="text-center">
                            @foreach ($post->tags as $tag)
                                <span style="color: white" class="badge bg-primary primary">{{ $tag->name }}</span>
                            @endforeach
                        </td>

                        {{-- Image --}}
                        <td class="text-center">
                            @if (isset($post->image))
                                <img style="width: 20px" src="{{ asset("storage/{$post->image}") }}"
                                    alt="{{ $post->title }}">
                            @else
                                /
                            @endif
                        </td>

                        {{-- Content --}}
                        <td>
                            {{ substr($post->content, 0, 150) }}
                            @if (strlen($post->content) > 150)
                                (...)
                            @endif
                        </td>

                        {{-- Buttons --}}
                        <td class="text-center">
                            <a class="btn btn-primary btn-sm mb-1"
                                href="{{ route('admin.posts.show', ['post' => $post->id]) }}">More Info</a>

                            <a class="btn btn-primary btn-sm mb-1"
                                href="{{ route('admin.posts.edit', ['post' => $post->id]) }}">Edit</a>

                            <form class="d-inline-block mb-1"
                                action="{{ route('admin.posts.destroy', ['post' => $post->id]) }}" method="POST"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
