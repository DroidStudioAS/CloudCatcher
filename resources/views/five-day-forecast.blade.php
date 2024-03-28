@extends("layouts.layout")
@section("content")

    <div class="daily_forecast">
        <div class="df_colum_general"></div>
        <div class="df_colum_data"></div>

    </div>



   <!-- Old Layoutdiv class="forecast_card">
       <p class="weather_city-forecast">
           {{$forecast[0]->city_name}}
       </p>
       <div class="forecast_row_container">
           <div class="forecast_box">
               <img src="{{$forecast[0]->forecast[0]->path_to_image}}"/>
               <p> {{$forecast[0]->forecast[0]->description}} </p>
           </div>
           <svg class="forecast-divider">
               <rect x="0" y="0" width="1px" height="40vh" fill="white"></rect>
           </svg>
           <div class="forecast_box">
               <div class="temperature_container">
                   <h1 class="weather_temperature">
                       {{$forecast[0]->forecast[0]->temperature}}°
                   </h1>
               </div>
               <p class="weather_date">
                   {{$forecast[0]->forecast[0]->date}}
               </p>
           </div>
       </div>
       <div class="forecast_four_day_container">
           @for($i=1; $i<=2; $i++)
               <div class="forecast_day">
                   <div class="card">
                       <div class="front_content">
                           <p class="weather_date">
                               {{$forecast[0]->forecast[$i]->date}}
                           </p>
                           <img src="{{asset($forecast[0]->forecast[$i]->path_to_image)}}" alt="weather_photo" class="forecast_photo">
                           <p>{{$forecast[0]->forecast[$i]->description}}</p>
                       </div>
                       <div class="back_content">
                           <p>Chance Of Rain/Snow:</p>
                           <p>
                               @if($forecast[0]->forecast[$i]->probability===null)
                                    0
                               @else
                                   {{$forecast[0]->forecast[$i]->probability}}
                               @endif
                               %
                           </p>
                       </div>
                   </div>
               </div>
           @endfor
       </div>
       <div class="follow_button">
           @if(!$isFollowed)
              <img onclick="addToFavourites({{$forecast[0]->id}})"
                  id="likeButton"
                  src="{{asset("/res/icon_not_liked.svg")}}"/>
              <p>Follow City</p>
            @else
               <img onclick="removeFromFavorites({{$forecast[0]->id}})"
                   id="likeButton"
                   src="{{asset("/res/icon_liked.svg")}}"/>
               <p>Unfollow City</p>
           @endif
       </div>
   </div-->
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
