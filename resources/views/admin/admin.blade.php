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
             </tr>
           </thead>
           <tbody>
                @foreach($weathers as $weather)
                    <tr>
                        <td class="weather_table_data">{{$weather->city}}</td>
                        <td class="weather_table_data">{{$weather->description}}</td>
                        <td class="weather_table_data">{{$weather->temperature}}°</td>
                        <td class="weather_table_data">{{\Carbon\Carbon::parse($weather->created_at)->format('d F Y')}}</td>
                    </tr>

                @endforeach
           </tbody>


       </table>
    </div>

@endsection
