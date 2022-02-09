@extends('layout')

@section('title')
    Домашняя страница
@endsection

@section('body')
    Вы дома
    <a href="{{ route('logout') }}">Выйти</a>
    <h1>{{$user->name}}, {{ $user->email }}</h1>
    <form action="{{route('category.create')}}" method="post">
        @csrf
        <input type="text" name="name">
        <button type="submit">Создать категорию</button>
    </form>
    @error('category')
    <div style="color: red">{{ $message }}</div>
    @enderror

    @foreach($user->category as $category)
        <h2>{{ $category->name }}</h2>
        <form action="{{route('dial.create', ['category' => $category->id])}}" method="post">
            @csrf
            <input type="text" name="doc">
            <button type="submit">Добавить dial</button>
        </form>
        @error("dial{$category->id}")
        <div style="color: red">{{ $message }}</div>
        @enderror
        <form action="{{route('category.delete', ['category' => $category->id])}}" method="post">
            @csrf
            <button type="submit">Удалить категорию</button>
        </form>

        <ul>
            @foreach($category->dial as $dial)
                <form action="{{route('dial.activity', ['dial' => $dial->id])}}" method="post">
                    @csrf

                    @if($dial->active)
                        <li>{{ $dial->title }}: {{$dial->description}}</li>
                    @else
                        <li><s>{{ $dial->title }}: {{$dial->description}}</s></li>
                    @endif
                    <button type="submit">{{ $dial->active ? 'Сделать неактивным' : 'Сделать активным' }}</button>
                </form>
                <form action="{{route('dial.delete', ['dial' => $dial->id])}}" method="post">
                    @csrf
                    <button type="submit">Удалить</button>
                </form>
            @endforeach
        </ul>

    @endforeach

@endsection
