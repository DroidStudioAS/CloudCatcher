@extends("layouts.layout")
@section("content")
    @if(count($weathers)===0)
        <div>{{$city}} Not Found In Our Records</div>
    @else
    @foreach($weathers->reverse() as $weather)
        <div class="weather_card">
            <p class="weather_city">{{$weather->city_name}}</p>
            <div class="weather_column">
                <img class="weather_image" src="{{asset($weather->path_to_image)}}" alt="weather photo">
                <p class="weather_description">{{$weather->description}}</p>
            </div>
            <svg class="divider">
                <rect x="0" y="0" width="1px" height="30vh" fill="white"></rect>
            </svg>

            <div class="weather_column">
                <div class="temperature_container">
                    <h1 class="weather_temperature">
                        {{$weather->temperature}}°
                    </h1>
                </div>
                <p class="weather_date">
                    {{$weather->date}}
                </p>
            </div>
        </div>
    @endforeach
    @endif
@endsection
