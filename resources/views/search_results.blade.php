@extends("layouts.layout")
@section("content")
@foreach($weathers as $weather)
    <p>{{$weather->city_name}}:{{$weather->weather->temperature}}</p>
@endforeach
@endsection
