@extends("layouts.layout")
@section("content")
    @if(count($weathers)===0)
        <div>{{$city}} Not Found In Our Records</div>
    @else
   <div class="forecast_card">
       <p class="weather_city-forecast">{{$weathers[4]->city_name}}</p>
       <div class="forecast_row_container">
           <div class="forecast_box">
               <img src="{{asset($weathers[4]->path_to_image)}}"/>
               <p>{{$weathers[4]->description}}</p>
           </div>
           <svg class="forecast-divider">
               <rect x="0" y="0" width="1px" height="40vh" fill="white"></rect>
           </svg>
           <div class="forecast_box">
               <div class="temperature_container">
                   <h1 class="weather_temperature">
                       {{$weathers[4]->temperature}}°
                   </h1>
               </div>
               <p class="weather_date">{{$weathers[4]->date}}</p>
           </div>
       </div>
       <div class="forecast_four_day_container">
           @for($i=3; $i>=0; $i--)
               <div class="forecast_day">
                   <p class="weather_date">{{$weathers[$i]->date}}</p>
                   <img src="{{asset($weathers[$i]->path_to_image)}}" alt="weather_photo" class="forecast_photo">
                   <p>{{$weathers[$i]->temperature}}°</p>
               </div>
           @endfor

       </div>
   </div>
    @endif
@endsection
