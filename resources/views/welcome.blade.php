@extends("layouts.layout")
@section("content")
    <div class="weather_cards_container">
            <div class="weather_card">
                <p class="weather_city">Thessaloniki</p>
                 <div class="weather_column">
                     <img class="weather_image" src="{{asset("/res/sunny.png")}}" alt="weather photo">
                     <p class="weather_description">Sunny</p>
                 </div>
                <svg class="divider">
                    <rect x="0" y="0" width="1px" height="30vh" fill="white"></rect>
                </svg>

                 <div class="weather_column">
                     <div class="temperature_container">
                         <h1 class="weather_temperature">
                             16
                         </h1>
                     </div>
                     <p class="weather_date">03/14</p>
                 </div>
        </div>
        <div class="weather_card">
            <p class="weather_city">Belgrade</p>
            <div class="weather_column">
                <img class="weather_image" src="{{asset("/res/rainy.png")}}" alt="weather photo">
                <p class="weather_description">Raining</p>
            </div>
            <svg class="divider">
                <rect x="0" y="0" width="1px" height="30vh" fill="white"></rect>
            </svg>

            <div class="weather_column">
                <div class="temperature_container">
                    <h1 class="weather_temperature">
                        16
                    </h1>
                </div>
                <p class="weather_date">03/14</p>
            </div>
        </div>
        <div class="weather_card">
            <p class="weather_city">Eindhoven</p>
            <div class="weather_column">
                <img class="weather_image" src="{{asset("/res/cloudy.png")}}" alt="weather photo">
                <p class="weather_description">Cloudy</p>
            </div>
            <svg class="divider">
                <rect x="0" y="0" width="1px" height="30vh" fill="white"></rect>
            </svg>

            <div class="weather_column">
                <div class="temperature_container">
                    <h1 class="weather_temperature">
                        16
                    </h1>
                </div>
                <p class="weather_date">03/14</p>
            </div>
        </div>


    </div>
@endsection
