@extends("layouts.layout")
@section("content")
    @if(count($weathers)===0)
        <div>{{$city}} Not Found In Our Records</div>
    @else
   <div class="forecast_card">
       <p class="weather_city-forecast">{{$weathers[0]->city_name}}</p>
       <div class="forecast_row_container">
           <div class="forecast_box">
               <img src="{{asset($weathers[0]->path_to_image)}}"/>
               <p>{{$weathers[0]->description}} @if($weathers[0]->probability!==null) <span>{{$weathers[0]->probability}}%</span> @endif</p>
           </div>
           <svg class="forecast-divider">
               <rect x="0" y="0" width="1px" height="40vh" fill="white"></rect>
           </svg>
           <div class="forecast_box">
               <div class="temperature_container">
                   <h1 class="weather_temperature">
                       {{$weathers[0]->temperature}}°
                   </h1>
               </div>
               <p class="weather_date">{{$weathers[0]->date}}</p>
           </div>
       </div>
       <div class="forecast_four_day_container">
           @for($i=1; $i<=4; $i++)
               <div class="forecast_day">
                   <div class="card">
                       <div class="front_content">
                           <p class="weather_date">{{$weathers[$i]->date}}</p>
                           <img src="{{asset($weathers[$i]->path_to_image)}}" alt="weather_photo" class="forecast_photo">
                           <p>{{$weathers[$i]->temperature}}°</p>
                       </div>
                       <div class="back_content">
                            <p>Chance Of Rain/Snow:</p>
                            <p>@if($weathers[$i]->probability===null)0% @else{{$weathers[$i]->probability}}%@endif</p>
                       </div>
                   </div>


               </div>
           @endfor

       </div>
   </div>
    @endif
@endsection
