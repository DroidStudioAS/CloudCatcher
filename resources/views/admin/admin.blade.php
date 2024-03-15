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
                        <td class="weather_table_data">{{$weather->city}}</td>
                        <td class="weather_table_data">{{$weather->description}}</td>
                        <td class="weather_table_data">{{$weather->temperature}}°</td>
                        <td class="weather_table_data">{{\Carbon\Carbon::parse($weather->created_at)->format('d F Y')}}</td>
                        <td class="weather_table_data">
                            <button onclick="displayEditForm({{json_encode($weather)}})" class="edit_button">Edit</button>
                            <button onclick="deleteWeatherRecord({{$weather->id}})" class="delete_button">Delete</button>
                        </td>
                    </tr>
                @endforeach
           </tbody>
       </table>
    </div>
    <div class="admin_row">
        <div class="entry_form_container">
            <h1>Enter A Weather Record</h1>
            <form METHOD="POST" action="{{route('post-weather')}}" class="entry-form">
                {{csrf_field()}}
                <label for="description">Description</label>
                <select class="entry-form-dropdown" name="description">
                    <option value="sunny">Sunny</option>
                    <option value="raining">Raining</option>
                    <option value="cloudy">Cloudy</option>
                </select>
                <label for="city">City</label>
                <input class="weather_input" name="city" type="text">
                <label for="temperature">Temperature (Celsius)</label>
                <input class="weather_input" name="temperature" type="number">
                <input class="submit-button" type="submit">
            </form>
        </div>
    </div>

    <!--EditPoput-->
    <div id="edit-form" class="entry_form_container-edit">
        <img onclick="closeEditContainer()" src="{{asset("/res/close.png")}}" alt="close">
        <h3>Edit Weather Record</h3>
        <form METHOD="POST" action="{{route('post-weather')}}" class="entry-form">
            {{csrf_field()}}
            <label for="description">Description</label>
            <select id="weather-edit-dropdown" class="entry-form-dropdown" name="description">
                <option value="sunny">Sunny</option>
                <option value="raining">Raining</option>
                <option value="cloudy">Cloudy</option>
            </select>
            <label for="city">City</label>
            <input id="weather-edit-city" class="weather_input" name="city" type="text">
            <label for="temperature">Temperature (Celsius)</label>
            <input id="weather-edit-temp" class="weather_input" name="temperature" type="number">
            <input id="edit-submit" class="submit-button" type="submit">
        </form>
    </div>
<script>
    function displayEditForm(weatherEntry){
       $("#edit-form").css('display','flex');
       $("#weather-edit-dropdown").val(weatherEntry.description);
       $("#weather-edit-city").val(weatherEntry.city);
       $("#weather-edit-temp").val(weatherEntry.temperature);
        console.log(weatherEntry.id);

       $("#edit-submit").off('click').on('click', function (e) {
            e.preventDefault();
            console.log(weatherEntry.id);
            editWeatherRecord(weatherEntry.id);
       })
    }
    function closeEditContainer(){
        $("#edit-form").css('display','none');
    }

    //async functions
    //edit record
    function editWeatherRecord(id){
        $.ajax({
            url:"/admin/edit-entry/"+id,
            type:"POST",
            data:{
              "_token": $('meta[name="csrf-token"]').attr('content'),
               "city":$("#weather-edit-city").val(),
                "description": $("#weather-edit-dropdown").val(),
                "temperature":$("#weather-edit-temp").val()
            },
            success:function (response){
                console.log(response.success);
                if(response.success===true){
                   location.reload();
                }
            },
            error:function(xhr) {
                console.log(xhr.responseText)
            }
        })
    }
    function deleteWeatherRecord(id){
        $.ajax({
            url:"/admin/delete-entry/"+id,
            type:"post",
            data:{
                "_token": $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success===true){
                    location.reload();
                }
            }
        })
    }
</script>
@endsection
