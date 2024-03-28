@extends("layouts.layout")
@section("content")

    <div class="daily_forecast">
        <div style="background-color: {{\App\Helpers\WeatherHelper::dailyForecastBackgroundDeterminer($dailyData["description"])[0]}}"
            class="df_colum_general">
            <p class="forecast_text">Current Temperature:</p>
            <div class="weather_temperature">
                {{$dailyData["current_temp"]}}°
            </div>
            <div>
                <p class="forecast_text"><span class="important">Wind Strength:</span> {{$dailyData["wind_kph"]}} KPH</p>
            </div>
            <div style="background-color: {{\App\Helpers\WeatherHelper::aqiBackgroudDeterminer($dailyData['aqi'])}}" class="card">
                    <div class="front_content">
                        <p class="forecast_text">Air Quality:</p>
                        <p class="forecast_text">{{$dailyData["aqi"]}}</p>
                        <p class="forecast_text">{{\App\Helpers\WeatherHelper::aqiShortDescriptionDeterminer($dailyData['aqi'])}}</p>
                    </div>
                    <div class="back_content">
                        <p class="forecast_text">
                            {{\App\Helpers\WeatherHelper::aqiLongDescriptionDeterminer($dailyData["aqi"])}}
                        </p>
                    </div>
            </div>
            <div class="astro">
                <div class="astro_child">
                    <p class="forecast_text">Sunrise</p>
                    <img src="{{asset("/res/icon_sunrise.svg")}}"/>
                    <p class="forecast_text">{{$dailyData["sunrise"]}}</p>
                </div>
                <div class="astro_child">
                    <p class="forecast_text">Sunset</p>
                    <img src="{{asset("/res/icon_sunset.svg")}}"/>
                    <p class="forecast_text">{{$dailyData["sunset"]}}</p>
                </div>
            </div>
        </div>
        <div style="background-image: url('{{asset(\App\Helpers\WeatherHelper::dailyForecastBackgroundDeterminer($dailyData['description'])[1])}}');
                   background-size: cover " class="df_colum_data">
            <p style="color: {{\App\Helpers\WeatherHelper::dailyForecastBackgroundDeterminer($dailyData["description"])[2]}}" class="city_name">
                {{$dailyData["city_name"]}}, {{$dailyData["country"]}}
                <br>
                {{$dailyData["description"]}}
            </p>

            <p style="color: {{\App\Helpers\WeatherHelper::dailyForecastBackgroundDeterminer($dailyData["description"])[2]}}" class="forecast_text">
                {{\Carbon\Carbon::parse($dailyData["date"])->format("l, Y-m-d T")}},
            </p>
            <p style="color: {{\App\Helpers\WeatherHelper::dailyForecastBackgroundDeterminer($dailyData["description"])[2]}}" class="forecast_extended_description">
                {{\App\Helpers\WeatherHelper::presentationDescriptionDeterminer($dailyData["description"])}}
            </p>
            <div class="minmax">
                <div  class="minxmax_child">
                    <p style="color: {{\App\Helpers\WeatherHelper::dailyForecastBackgroundDeterminer($dailyData["description"])[2]}}" class="forecast_text">
                        Daily Minimum <br> Temperature: <br>
                        <span style="color: {{\App\Helpers\WeatherHelper::dailyForecastBackgroundDeterminer($dailyData["description"])[2]}}" class="weather_temp_small">{{$dailyData['min_temp']}}°</span>
                    </p>

                </div>
                <div class="minxmax_child">
                    <p style="color: {{\App\Helpers\WeatherHelper::dailyForecastBackgroundDeterminer($dailyData["description"])[2]}}" class="forecast_text">
                        Daily Maximum <br> Temperature: <br>
                        <span style="color: {{\App\Helpers\WeatherHelper::dailyForecastBackgroundDeterminer($dailyData["description"])[2]}}" class="weather_temp_small">{{$dailyData['max_temp']}}°</span>
                    </p>
                </div>
                <div class="minxmax_child">
                    <p style="color: {{\App\Helpers\WeatherHelper::dailyForecastBackgroundDeterminer($dailyData["description"])[2]}}" class="forecast_text">
                        Daily Average <br> Temperature: <br>
                        <span style="color: {{\App\Helpers\WeatherHelper::dailyForecastBackgroundDeterminer($dailyData["description"])[2]}}" class="weather_temp_small">{{$dailyData['avg_temp']}}°</span>
                    </p>
                </div>
            </div>
            <div class="title_container">
                <p style="color: {{\App\Helpers\WeatherHelper::dailyForecastBackgroundDeterminer($dailyData["description"])[2]}}" class="important">Hourly forecast</p>
            </div>
            <div class="hour_container">
                @for($i =0; $i<6; $i++)
                    <div class="hour">
                        <p style="color: {{\App\Helpers\WeatherHelper::dailyForecastBackgroundDeterminer($dailyData["description"])[2]}}" class="forecast_text">{{\Carbon\Carbon::parse($dailyData["h-t-h"][$i]["time"])->format("H")}}</p>
                        <img src="{{asset(\App\Helpers\WeatherHelper::realDataImagePathDeterminer($dailyData["h-t-h"][$i]["condition"]))}}"/>
                        <p style="color: {{\App\Helpers\WeatherHelper::dailyForecastBackgroundDeterminer($dailyData["description"])[2]}}" class="forecast_text">{{$dailyData["h-t-h"][$i]["temp_c"]}}°</p>
                    </div>
                @endfor
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
