@extends("layouts.layout")

@section("content")

    <div class="search_container">
        @if(isset($date))
            <h3>Weather on: {{$date}}</h3>
        @else
            <h3>All Dates</h3>
        @endif

        <form id="searchForm">
        <input autocomplete="off" name="date" class="date" type="text" id="datepicker" placeholder="select another date">
        <input type="submit" class="submit-button" value="search">
        </form>
        <button onclick="showAllTime()" class="submit-button">Show All Records</button>


    </div>
    <div class="weather_cards_container">

        @if(count($weathers)==0)
        <h1 class="no-entries-msg">There Are No <br> Entries<br> For {{$date}}</h1>
        @endif
        @foreach($weathers as $weather)
            <div class="weather_card">
                <p class="weather_city">{{$weather->city}}</p>
                <div class="weather_column">
                    <img class="weather_image" src="{{asset($weather->path_to_image)}}" alt="weather photo">
                    <p class="weather_description">{{$weather->description}}</p>
                </div>
                <svg class="divider">
                    <rect x="0" y="0" width="1px" height="30vh" fill="white"></rect>
                </svg>

                <div class="weather_column">
                    <div class="temperature_container">
                        <h1 class="weather_temperature">
                            {{$weather->temperature}}°
                        </h1>
                    </div>
                    <p class="weather_date">{{\Carbon\Carbon::parse($weather->created_at)->format('d F Y')}}</p>
                </div>
            </div>
        @endforeach
    </div>
    <script>
        $(document).ready(function(){
            $('#datepicker').datepicker({
                format:'yyyy-mm-dd',
                endDate:new Date(),
                autoclose:true,
                todayHighlight:true
            });
        });

        $("#searchForm").on("submit", function (event){
            event.preventDefault();
            loadWeatherForDate();
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
    </script>

@endsection
