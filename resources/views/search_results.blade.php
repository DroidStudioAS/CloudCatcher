@extends("layouts.layout")
@section("content")
    <h1>
        Found {{count($weathers)}} Results For Your Criteria;
    </h1>

    <div class="weather_cards_container">
        @foreach($weathers as $weather)
            <div class="weather_card">
                <p class="weather_city">{{$weather->city->city_name}}</p>
                <div class="weather_column">
                    <img class="weather_image" src="{{asset($weather->path_to_image)}}" alt="weather photo">
                    <p class="weather_description">{{$weather->description}}</p>
                </div>
                <svg class="divider">
                    <rect x="0" y="0" width="1px" height="30vh" fill="white"></rect>
                </svg>

                <div class="weather_column">
                    <div class="temperature_container">
                        <h1 class="weather_temperature" style="color: {{\App\Helpers\WeatherHelper::determineTemperatureColor($weather->temperature)}}">
                            {{$weather->temperature}}°
                        </h1>
                    </div>
                    <p class="weather_date">{{$weather->date}}</p>
                </div>
                <div onclick="showCityForecast('{{$weather->city->city_name}}')" class="show_more_button">Forecast</div>
            </div>
        @endforeach
    </div>
    <script>
        function showCityForecast(city){
            if(city!==null || city!==""){
                window.location.href="/weather-for/"+city
            }
        }
    </script>
@endsection

