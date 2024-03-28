@extends("layouts.layout")
@section("content")
    <h1 class="subtitle">
        Found {{count($weathers)}} Results For Your Criteria;
    </h1>

    <div class="weather_cards_container">
        @foreach($weathers as $weather)
            <div class="weather_card">
                <p class="weather_city">{{$weather->city->city_name}} , {{$weather->city->country}}</p>
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
                <div onclick="showCityForecast('{{$weather->city->id}}', '{{$weather->date}}')" class="show_more_button">Forecast</div>
                <div id="likeButton" class="home_like_button">
                    @if(in_array($weather->city->id, $userFavorites))
                        <img id="like_image"
                             src="{{asset("/res/icon_liked.svg")}}"
                             onclick="removeFromFavorites({{$weather->city->id}})"/>
                    @else
                        <img
                            id="unlike_image"
                            src="{{asset("/res/icon_not_liked.svg")}}"
                            onclick="addToFavourites({{$weather->city->id}})"/>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <script>
        function showCityForecast(city, date){

            if(city!==null || city!==""){
                window.location.href="/search/"+city+"/"+date;
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

