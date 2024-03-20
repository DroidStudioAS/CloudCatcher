<!--Todo
1)Weather Entries will by default be the current date- no reason to have a post weather entry form anymore- refactor it to enter city
2)Display all the forecasts for a certain all cities Done
3)Add an add forecast function -- DONE
-->
@extends("layouts.admin_layout")
@section("admin-content")
    <h1>Current Records:</h1>
    <div class="admin_row">
        <table>
            <thead>
            <tr>
                <th class="table_header">City</th>
                <th class="table_header">Description</th>
                <th class="table_header">Temperature</th>
                <th class="table_header">Date</th>
                <th class="table_header">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($weathers as $weather)
                <tr>
                    <td class="weather_table_data">{{$weather->city_name}}</td>
                    <td class="weather_table_data">{{$weather->description}}</td>
                    <td class="weather_table_data">{{$weather->temperature}}°</td>
                    <td class="weather_table_data">{{\Carbon\Carbon::now()->format('d F Y')}}</td>
                    <td class="weather_table_data">
                        <button onclick="displayEditForm({{json_encode($weather)}})" class="edit_button">Edit</button>
                        <button onclick="deleteWeatherRecord({{$weather->id}})" class="delete_button">Delete</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination_container">
        {{$weathers->links()}}
    </div>
    <h1>Enter Data</h1>
    <div class="admin_row">
        <!--Forecast Form-->
        <div class="entry_form_container">
            <h1>Enter A Forecast For <br>An Existing City:</h1>
            <form method="POST" action="/admin/post-forecast" class="entry-form">
                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        <p>{{$error}}</p>
                    @endforeach
                @endif
                {{csrf_field()}}
                <label for="city_name">Name:</label>
                    <select class="weather_input" name="city_id">
                    @foreach($cities as $city)
                        <option value="{{$city->id}}">{{$city->city_name}}</option>
                    @endforeach
                    </select>
                <label for="temperature">Current Temperature (Celsius)</label>
                <input class="weather_input" name="temperature" type="number">
                <label for="description">Description</label>
                <select class="entry-form-dropdown" name="description">
                    <option value="sunny">Sunny</option>
                    <option value="raining">Raining</option>
                    <option value="cloudy">Cloudy</option>
                    <option value="snowing">Snowing</option>
                </select>
                <label for="precipitation">Chance Of Precipitation (%)</label>
                <input class="weather_input" name="precipitation" type="number" min="0" max="100">
                <input autocomplete="off" name="date" class="date" type="text" id="datepicker"
                       placeholder="Select A Date">
                <input class="submit-button" type="submit">
            </form>
        </div>
        <!--city form-->
        <div class="entry_form_container">
            <h1>Enter A New City</h1>
            <form class="entry-form">
                {{csrf_field()}}
                <label for="country">Country:</label>
                <input class="weather_input" name="country"/>
                <label for="city_name">Name:</label>
                <input class="weather_input" name="city_name"/>
                <label for="temperature">Current Temperature (Celsius)</label>
                <input class="weather_input" name="temperature" type="number">
                <label for="description">Description</label>
                <select class="entry-form-dropdown" name="description">
                    <option value="sunny">Sunny</option>
                    <option value="raining">Raining</option>
                    <option value="cloudy">Cloudy</option>
                    <option value="snowing">Snowing</option>
                </select>
                <input class="submit-button" type="submit">
            </form>
        </div>
    </div>
    <!--Forecast Browse-->
    <h1>Forecasts For All Cities</h1>
        <div class="table_container">
            <table class="cities_table">
                <thead>
                <th class="table_header">Country:</th>
                <th class="table_header">Name:</th>
                <th class="table_header">View Forecast</th>
                </thead>
                <tbody>
                @foreach($cities as $city)
                    <tr>
                        <td class="weather_table_data">{{$city->country}}</td>
                        <td class="weather_table_data">{{$city->city_name}}</td>
                        <td class="weather_table_data">
                            <button onclick="displayForecastContainer('{{$city->city_name}}',{{json_encode($city->forecast)}})" class="edit_button">See Forecast</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>





    <!--EditPoput-->
    <div id="edit-form" class="entry_form_container-edit">
        <img onclick="closeEditContainer()" src="{{asset("/res/close.png")}}" alt="close">
        <h3>Edit Weather Record</h3>
        <form METHOD="POST" class="entry-form">
            {{csrf_field()}}
            <label for="description">Description</label>
            <select id="weather-edit-dropdown" class="entry-form-dropdown" name="description">
                <option value="sunny">Sunny</option>
                <option value="raining">Raining</option>
                <option value="cloudy">Cloudy</option>
                <option value="snowing">Snowing</option>
            </select>
            <label for="city">City</label>
            <select id="weather-edit-city" class="weather_input" name="city" type="text">
                @foreach($cities as $city)
                    <option value="{{$city->id}}">{{$city->city_name}}</option>
                @endforeach
            </select>
            <label for="temperature">Temperature (Celsius)</label>
            <input id="weather-edit-temp" class="weather_input" name="temperature" type="number">
            <input id="edit-submit" class="submit-button" type="submit">
        </form>
    </div>
    <!--forecasts popup-->
    <div id="forecast-popup" class="forecast_popup">
        <img onclick="closeForecastContainer()" src="{{asset("/res/close.png")}}" alt="close">
        <h1 id="forecast_title">City:</h1>
       <table>
           <thead>
            <th class="table_header">
                Date:
            </th>
           <th class="table_header">
               Temperature
           </th>
           <th class="table_header">
               Description:
           </th>
           <th class="table_header">
               Probability
           </th>
            <th class="table_header">
                Edit
            </th>
           </thead>
           <tbody id="tbody">
                <tr>
                    <td>hey</td>
                </tr>
           </tbody>
       </table>
    </div>
    <script>
        $(document).ready(function(){
            let currentDate = new Date();
            let sevenDaysFromNow = new Date();
            sevenDaysFromNow.setDate(currentDate.getDate() + 7);
            $('#datepicker').datepicker({
                dateFormat:'yy-mm-dd',
                minDate: 0,
                maxDate:sevenDaysFromNow
            });
        });
        function displayEditForm(weatherEntry) {
            $("#edit-form").css('display', 'flex');
            $("#weather-edit-dropdown").val(weatherEntry.description.toLowerCase());
            $("#weather-edit-city").val(weatherEntry.city_id);
            $("#weather-edit-temp").val(weatherEntry.temperature);
            console.log(weatherEntry.city_id);

            $("#edit-submit").off('click').on('click', function (e) {
                e.preventDefault();
                console.log(weatherEntry.id);
                editWeatherRecord(weatherEntry.id);
            })
        }
        function displayForecastContainer(name, forecastEntry){
            let tableBody = $("#tbody");
            tableBody.empty();

            $("#forecast_title").text(name);
            $("#forecast-popup").css("display", "flex");
            console.log(forecastEntry);


            forecastEntry.forEach(entry=> {
                const row = $("<tr>")

                console.log(entry)
                $("<td class='weather_table_data'>").text(entry.date).appendTo(row);
                $("<td class='weather_table_data'>").text(entry.temperature).appendTo(row);
                $("<td class='weather_table_data'>").text(entry.description).appendTo(row);
                if(entry.probability===null){
                    $("<td class='weather_table_data'>").text("/").appendTo(row);
                }else{
                    $("<td class='weather_table_data'>").text(entry.probability + "%").appendTo(row);
                }
                //append edit button and set its onclick listener
                let editField = $("<td id='bzju' class='weather_table_data'>");
                let editButton = $("<button class='edit_button'>").text("Edit");
                let deleteButton = $("<button class='delete_button'>").text("delete");
                editButton.on("click",function (){
                    console.log(entry.city_id + " "+ entry.id + " " + entry.temperature);
                })
                editButton.appendTo(editField);
                deleteButton.appendTo(editField);
                editField.appendTo(row);
                row.appendTo(tableBody);
            })


        }

        function closeEditContainer() {
            $("#edit-form").css('display', 'none');
        }
        function closeForecastContainer(){
            $("#forecast-popup").css("display","none")
        }

        //async functions
        //edit record
        function editWeatherRecord(id) {

            $.ajax({
                url: "/admin/edit-entry/" + id,
                type: "POST",
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "city": $("#weather-edit-city").val(),
                    "description": $("#weather-edit-dropdown").val(),
                    "temperature": $("#weather-edit-temp").val()
                },
                success: function (response) {
                    console.log(response.success);
                    if (response.success === true) {
                        location.reload();
                    }
                },
                error: function (xhr) {
                    alert("Something went wrong!");
                    console.log(xhr.responseText)
                }
            })
        }

        function deleteWeatherRecord(id) {
            $.ajax({
                url: "/admin/delete-entry/" + id,
                type: "post",
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success === true) {
                        location.reload();
                    }
                }
            })
        }
    </script>
@endsection
