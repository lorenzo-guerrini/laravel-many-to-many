@extends('layouts.admin')

@section('title')
    Edit: {{ $post->title }}
@endsection

@section('content')
    <div class="col-12">
        <div class="pb-3">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-danger">Back</a>
        </div>

        {{-- <div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div> --}}

        <form action="{{ route('admin.posts.update', ['post' => $post->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                    placeholder="Post's title" value="{{ old('title', $post->title) }}">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Category --}}
            <div class="form-group">
                <label for="category">Category</label>
                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                    <option value="">No Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $category->id == old('category_id', $post->category_id) ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tag --}}
            <div class="form-group">
                <label for="tag">Tags</label><br>
                @foreach ($tags as $tag)
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="tags[]" id="{{ $tag->slug }}" class="form-check-input"
                            value="{{ $tag->id }}"
                            
                            {{-- Se la validation fallisce recupera i valori old --}}
                            @if ($errors->any())
                            {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}

                            {{-- Altrimenti recupera i tag del post --}}
                            @else
                            {{ $post->tags->contains($tag) ? 'checked' : '' }}

                            @endif
                            >
                            
                            <label for="{{ $tag->slug }}" class="form-check-label">{{ $tag->name }}</label>
                    </div>
                @endforeach
                @error('tags')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Image --}}
            <div class="form-group">
                <label for="image">Image (jpeg, bmp, png) - max: 2MB</label>
                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Content --}}
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror" rows="10"
                    placeholder="Post's content">{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
@endsection
