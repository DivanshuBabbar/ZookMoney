@extends('layouts.app')

@section('content')
{{-- @include('partials.nav') --}}
<div class="row">
    @include('partials.sidebar')
    <div class="col-md-9">
        <div class="card">
            <div class="header">
                <h2><strong>#{{ $ticket->ticket_id }}</strong> - {{ $ticket->title }}</h2>
            </div>
            <div class="body">

                <!-- Internal CSS -->
                <style>
                    .card-textarea {
                        border: 1px solid #e0e0e0;
                        border-radius: 4px;
                        padding: 15px;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        background-color: #ffffff;
                    }

                    .card-textarea textarea {
                        border: none;
                        outline: none;
                        width: 100%;
                        height: 100%;
                        resize: none;
                        padding: 10px;
                        font-size: 16px;
                        box-shadow: none;
                    }
                </style>

                <div class="ticket-info">
                    <p>{{ $ticket->message }}</p>
                    <p>Category: {{ $category->name }}</p>
                    <p>
                        @if ($ticket->status === 'Open')
                        Status: <span class="btn btn-success">{{ $ticket->status }}</span>
                        @else
                        Status: <span class="btn btn-danger">{{ $ticket->status }}</span>
                        @endif
                    </p>
                    <p>Created on: {{ $ticket->created_at->diffForHumans() }}</p>
                </div>
                <hr>

                <div class="comments">
                    @foreach ($comments as $comment)
                    <div class="panel panel-@if($ticket->user->id === $comment->user_id) default @else success @endif">
                        <div class="panel panel-heading">
                            <span class="text-primary">{{ $comment->user->name ?? '' }}</span>
                            <span class="pull-right">{{ $comment->created_at->format('Y-m-d') }}</span>
                        </div>
                      
                        <div class="panel panel-body">
                            <strong>{{ $comment->comment }}</strong>
                            <br>
                            @if ($comment->attachment)
                            <strong><a href="{{ asset('application/' . $comment->attachment) }}" target="_blank" style="color: blue;">View Attachment</a></strong>
                            @endif
                        </div>
                    </div>
                    <hr>
                    @endforeach
                </div>

                @if($ticket->status != 'Closed')
                <hr>
                <div class="comment-form">
                    <form action="{{ route('comment', app()->getLocale()) }}" method="POST" class="form" enctype="multipart/form-data">
                        {!! csrf_field() !!}

                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                        <input type="hidden" name="clickup_ticket_id" value="{{ $ticket->ticket_id }}">

                        <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
                            <div class="card-textarea">
                                <textarea rows="10" id="comment" name="comment" class="form-control" placeholder="type your comment" required></textarea>
                            </div>

                            @if ($errors->has('comment'))
                            <span class="help-block">
                                <strong>{{ $errors->first('comment') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="file">Upload file:</label>
                            <input type="file" id="file" name="file" class="form-control">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

                @endif

            </div>
        </div>
    </div>
</div>
@endsection
