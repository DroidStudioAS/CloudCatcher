@extends("layouts.layout")
@section("content")
    @if(count($weathers)==0)
        <h1>No Results For: {{$city_name}}</h1>
    @else
        <h1>
            @if(count($weathers)==1)
                1 Result For {{$city_name}}
            @else
                {{count($weathers)}} Results For: {{$city_name}}
            @endif
        </h1>
    @endif
    <div class="weather_cards_container">
        @foreach($weathers as $weather)
            <div class="weather_card">
                <p class="weather_city">{{$weather->city_name}}</p>
                <div class="weather_column">
                    <img class="weather_image" src="{{asset($weather->weather->path_to_image)}}" alt="weather photo">
                    <p class="weather_description">{{$weather->weather->description}}</p>
                </div>
                <svg class="divider">
                    <rect x="0" y="0" width="1px" height="30vh" fill="white"></rect>
                </svg>

                <div class="weather_column">
                    <div class="temperature_container">
                        <h1 class="weather_temperature" style="color: {{\App\Helpers\WeatherHelper::determineTemperatureColor($weather->temperature)}}">
                            {{$weather->weather->temperature}}°
                        </h1>
                    </div>
                    <p class="weather_date">{{\Carbon\Carbon::parse($weather->weather->created_at)->format('d F Y')}}</p>
                </div>
                <div onclick="showCityForecast('{{$weather->city_name}}')" class="show_more_button">Forecast</div>
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

