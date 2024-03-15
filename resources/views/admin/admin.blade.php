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
                            <button onclick="displayEditForm()" class="edit_button">Edit</button>
                            <button class="delete_button">Delete</button>
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
<script>
    function displayEditForm(){
       $("#edit-form").css('display','flex');
    }
    function closeEditContainer(){
        $("#edit-form").css('display','none');
    }
</script>
@endsection
