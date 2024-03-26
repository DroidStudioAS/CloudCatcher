@extends("layouts.layout")

@section("content")
   <div class="search_container">
       <div class="searchDisplay">
           Display Filters
       </div>
            <div class="search_filters">
                <form ACTION="{{route("search")}}" class="search_form" id="searchForm">
                    <input autocomplete="off" name="date" class="date" type="text" id="datepicker" placeholder="Select A Date">
                    <input name="city_name" id="cityInput" class="date"  placeholder="Search By City">
                    <input name="country" id="countryInput" class="date"  placeholder="Search By Country">
                    <input type="submit" class="submit-button" value="search">
                </form>
        </div>
       @if(isset($date))
           <h3>Weather on: {{$date}} @if(isset($country)) In: {{ucfirst($country)}} @endif</h3>
       @else
           <h3>All Dates</h3>
       @endif



    </div>
    <div class="weather_cards_container">
        <h1 class="no-entries-msg">
            @if(isset($error))

                {{$error}}
            @endif
        </h1>
        @foreach($weathers->reverse() as $weather)
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
                    <p class="weather_date">{{\Carbon\Carbon::parse($weather->date)->format('d F Y')}}</p>
                </div>
                <div onclick="showCityForecast('{{$weather->city->city_name}}')" class="show_more_button">Forecast</div>
                <div onclick="addToFavourites({{$weather->city->id}})" id="likeButton" class="home_like_button">
                    <img id="like_image"
                         @if(in_array($weather->city->id,$favoriteCities))
                             src="{{asset("/res/icon_liked.svg")}}"/>
                        @else
                         src="{{asset("/res/icon_not_liked.svg")}}"/>
                         @endif
                </div>

            </div>
        @endforeach
    </div>
    @if(count($weathers)>=6)
        {{$weathers->links()}}
    @endif
    <script>
        let searchClickCount = 0;
        $(document).ready(function(){
            $('#datepicker').datepicker({
                dateFormat:'yy-mm-dd',
                autoclose:true,
                todayHighlight:true
            });
            $(".searchDisplay").on('click',function (){
                if(searchClickCount%2===0) {
                    $(".search_filters").slideDown();
                    $(".searchDisplay").text("Hide Filters")
                }else{
                    $(".search_filters").slideUp();
                    $(".searchDisplay").text("Display Filters")
                }
                searchClickCount++
            })

        });
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
        function searchCityForecast(city){
            if(city!==null || city!==""){
                window.location.href="/weather-search/"+city
            }
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

        function addToFavourites(id){
            $.ajax({
                url:"add-user-favourite/"+id,
                type:"POST",
                data:{
                    "_token": $('meta[name="csrf-token"]').attr('content')
                },
                success:function(response){
                    console.log(response);
                },
                error(err){
                    console.log(err.responseText);
                }

            })
        }
    </script>

@endsection
