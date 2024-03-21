@extends("layouts.layout")

@section("content")

    <div class="search_container">
        @if(isset($date))
            <h3>Weather on: {{$date}} @if(isset($country)) In: {{ucfirst($country)}} @endif</h3>
        @else
            <h3>All Dates</h3>
        @endif

        <form id="searchForm">
        <input autocomplete="off" name="date" class="date" type="text" id="datepicker" placeholder="Select A Date">
        <input type="submit" class="submit-button" value="search">
        </form>
        <div class="geo_search_container">
            <form id="searchByName">
                <input id="cityInput" class="date"  placeholder="Search By City">
                <input type="submit" class="submit-button" value="search">
            </form>
            <form id="searchByCountry">
                <input id="countryInput" class="date"  placeholder="Search By Country">
                <input type="submit" class="submit-button" value="search">
            </form>
        </div>



    </div>
    <div class="weather_cards_container">
        @if(count($weathers)==0)
        <h1 class="no-entries-msg">There Are No <br> Entries<br> For {{$date}} @if(isset($country)) in {{$country}} @endif</h1>
        @endif
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
                        <h1 class="weather_temperature" style="color: {{\App\Helpers\WeatherHelper::determineTemperatureColor($weather->temperature)}}">
                            {{$weather->temperature}}°
                        </h1>
                    </div>
                    <p class="weather_date">{{\Carbon\Carbon::parse($weather->date)->format('d F Y')}}</p>
                </div>
                <div onclick="showCityForecast('{{$weather->city_name}}')" class="show_more_button">Forecast</div>

            </div>
        @endforeach
    </div>
    {{$weathers->links()}}
    <script>
        $(document).ready(function(){
            $('#datepicker').datepicker({
                format:'yyyy-mm-dd',
                autoclose:true,
                todayHighlight:true
            });
        });

        $("#searchForm").on("submit", function (event){
            event.preventDefault();
            loadWeatherForDate();
        })
        $("#searchByName").on('submit',function(event){
            event.preventDefault();
            let city = $("#cityInput").val()
            if(city===null || city===""){
                alert("please enter a city")
                return;
            }
            showCityForecast(city);
        })
        $("#searchByCountry").on("submit",function (e){
            e.preventDefault();
            let country = $("#countryInput").val();
            if(country==="" || country===null){
                alert("Please Enter A Country");
                return;
            }
            showCountryWeather(country);

        })


        function loadWeatherForDate(){
            let date = $("#datepicker").val();
            console.log(date)
            if(date===""){
                alert("Please Pick A Date")
                return;
            }
            window.location.href="/weather/"+date

        }
        function showAllTime(){
            window.location.href="/all-time-weather";
        }
        function showCityForecast(city){
           if(city!==null || city!==""){
               window.location.href="/weather-for/"+city
           }
        }
        function showCountryWeather(country){
            if(country!==null || country!==""){
                window.location.href="/weather-for-country/"+country;
            }
        }
    </script>

@endsection
