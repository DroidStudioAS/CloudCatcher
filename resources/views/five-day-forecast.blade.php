@extends("layouts.layout")
@section("content")
    @if(count($weathers)===0)
        <div>No Forecasts For This City Right Now</div>
    @endif
    @foreach($weathers as $weather)
        <div class="weather_card">
            <p class="weather_city">{{$city}}</p>
            <div class="weather_column">
                <img class="weather_image" src="{{asset("res/cloudy.png")}}" alt="weather photo">
                <p class="weather_description"></p>
            </div>
            <svg class="divider">
                <rect x="0" y="0" width="1px" height="30vh" fill="white"></rect>
            </svg>

            <div class="weather_column">
                <div class="temperature_container">
                    <h1 class="weather_temperature">
                        {{$weather}}°
                    </h1>
                </div>
                <p class="weather_date"></p>
            </div>
        </div>
    @endforeach
@endsection
