@extends('layouts.app')

@section('content')

<!-- ログインユーザー登録 -->

<!-- 認証処理 -->
    @if (Auth::check())
    {{ Auth::user()->name }}
        
<!-- タスク一覧を表示 -->
    <div>
        @include("tasks.tasks")
    </div>
<!-- タスク一覧表示ここまで -->

    @else
    <div class="center jumbotron">
        <div class="text-center">
            <h1>Welcome to the Tasklist</h1>
            {{-- ユーザ登録ページへのリンク --}}
            {!! link_to_route('signup.get', 'Sign up now!', [], ['class' => 'btn btn-lg btn-primary']) !!}
        </div>
    </div>
    @endif
@endsection