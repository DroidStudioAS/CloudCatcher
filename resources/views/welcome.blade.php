<!--Todo:Refactor the weather card into a component to be reused -->
@extends("layouts.layout")

@section("content")
   <div class="search_container">
       <h3>Find Your City:</h3>
       <form ACTION="{{route("search")}}" class="search_form" id="searchForm">
           <input name="city_name" id="cityInput" class="date"  placeholder="Search By City">
           <div class="searchDisplay">
           Display Filters
       </div>
            <div class="search_filters">
                <input autocomplete="off" name="date" class="date" type="text" id="datepicker" placeholder="Select A Date">
                    <input name="country" id="countryInput" class="date"  placeholder="Search By Country">
            </div>
           <input type="submit" class="submit-button" value="search">
       </form>
   </div>

   <!--container for favorites  this is how we acess todays forecast through the usercity object  $city->cityModel->todaysForecast[0]-->
    @if(isset($favoriteCities) && count($favoriteCities)!==0)
        <h3 class="subtitle">Favorite Cities</h3>
        <div id="favorites_container" class="weather_cards_container">
            @foreach($favoriteCities as $city)
                <div class="weather_card">
                    <p class="weather_city">{{$city->cityModel->city_name}} , {{$city->cityModel->country}}</p>
                    <div class="weather_column">
                        <img class="weather_image"
                             src="{{asset($city->cityModel->todaysForecast[0]->path_to_image)}}"
                             alt="weather photo">
                        <p class="weather_description">{{$city->cityModel->todaysForecast[0]->description}}</p>
                    </div>
                    <svg class="divider">
                        <rect x="0" y="0" width="1px" height="30vh" fill="white"></rect>
                    </svg>
                    <div class="weather_column">
                        <div class="temperature_container">
                            <h1 class="weather_temperature">{{$city->cityModel->todaysForecast[0]->temperature}}°</h1>
                        </div>
                        <p class="weather_date">{{$city->cityModel->todaysForecast[0]->date}}</p>
                    </div>
                    <div onclick="showCityForecast('{{$city->cityModel->city_name}}')"
                         class="show_more_button">Forecast</div>
                    <div id="likeButton" class="home_like_button">
                        <img id="like_image"
                             src="{{asset("/res/icon_liked.svg")}}"
                             onclick="removeFromFavorites({{$city->city_id}})"/>
                    </div>
                </div>
            @endforeach
        </div>
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
                    if(response.success===true){
                        location.reload();
                    }
                },
                error(err){
                    console.log(err.responseText);
                }

            })
        }
        function removeFromFavorites(id){
            $.ajax({
                url:"remove-user-favourite/"+id,
                type:"POST",
                data:{
                    "_token": $('meta[name="csrf-token"]').attr('content')
                },
                success:function(response){
                    console.log(response);
                    if(response.success===true){
                        location.reload();
                    }
                },
                error(err){
                    console.log(err.responseText);
                }

            })
        }
    </script>

@endsection
