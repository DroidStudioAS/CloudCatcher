@extends("layouts.layout")
@section("content")

    <div class="daily_forecast">
        <div class="df_colum_general">
            <p class="forecast_text">Current Temperature:</p>
            <div class="weather_temperature">
                {{$dailyData["current_temp"]}}°
            </div>
            <div>
                <p class="forecast_text"><span class="important">Wind Strength:</span> {{$dailyData["wind_kph"]}} KPH</p>
            </div>
            <div class="card">
                    <div class="front_content">
                        <p class="forecast_text">Aqi</p>
                        <p class="forecast_text">Safe</p>
                    </div>
                    <div class="back_content">
                        <p class="forecast_text">Description</p>
                    </div>
            </div>
            <div class="astro">
                <div class="astro_child">
                    <p class="forecast_text">Sunrise</p>
                    <p class="forecast_text">{{$dailyData["sunrise"]}}</p>
                </div>
                <div class="astro_child">
                    <p class="forecast_text">Sunset</p>
                    <p class="forecast_text">{{$dailyData["sunset"]}}</p>
                </div>
            </div>
        </div>
        <div class="df_colum_data">
            <p class="city_name">
                {{$dailyData["city_name"]}}, {{$dailyData["country"]}}
                <br>
                {{$dailyData["description"]}}
            </p>

            <p class="forecast_text">
                {{\Carbon\Carbon::parse($dailyData["date"])->format("l, Y-m-d T")}},
            </p>
            <p class="forecast_extended_description">
                {{\App\Helpers\WeatherHelper::presentationDescriptionDeterminer($dailyData["description"])}}
            </p>
            <div class="minmax">
                <div class="minxmax_child">
                    <p class="forecast_text">
                        Daily Minimum <br> Temperature: <br>
                        <span class="weather_temp_small">{{$dailyData['min_temp']}}°</span>
                    </p>

                </div>
                <div class="minxmax_child">
                    <p class="forecast_text">
                        Daily Maximum <br> Temperature: <br>
                        <span class="weather_temp_small">{{$dailyData['max_temp']}}°</span>
                    </p>
                </div>
                <div class="minxmax_child">
                    <p class="forecast_text">
                        Daily Average <br> Temperature: <br>
                        <span class="weather_temp_small">{{$dailyData['avg_temp']}}°</span>
                    </p>
                </div>
            </div>

        </div>

    </div>




    <script>
        function addToFavourites(id){
            console.log(id)
            $.ajax({
                url:"/add-user-favourite/"+id,
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
                url:"/remove-user-favourite/"+id,
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
