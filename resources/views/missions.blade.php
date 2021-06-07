@extends('layout')

@section('title', 'FS19 - Webstats: Missions')

@section('header_text')
@if ($savegame->id == $current_savegame->id)
   Available missions for most recent savegame
@else
   Available missions for savegame {{ $savegame->id }} saved {{ date_format(date_create($savegame->save_date),"d.m.Y") }}
@endif
@endsection

@section('stylesheet')
<style type="text/css">
  td.format, th.format {
    text-align: right;
  }
  td.center, th.center {
    text-align: center;
  }
</style>
@endsection

@section('content')
<div class="w3-container">
   <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
      <tr>
         <th class="center">#</th>                  
         <th>Type</th>
         <th class="format">Field</th>
         <th>Product</th>         
         <th class="format">Reward</th>
         <th class="format">Rental</th>    
         <th class="center"><i class="fa fa-info w3-text-blue w3-large"></i></th>              
         <th>Farm</th>
         <th>Status</th>                  
         <th>Success</th>       
      </tr>
      @foreach($savegame->missions as $mission)
         <tr>
            <td class="center">{{ $loop->iteration }}</td>               
            <td>{{ ucfirst($mission->type) }}</td>
            <td class="format">{{ $mission->field_id ? $mission->field_id : '---' }}</td>            
            <td>{{ ucfirst(strtolower($mission->type == 'transport' ? $mission->trans_product : $mission->fruit_type)) }}</td> <!-- will be tranlated -->            
            <td class="format">{{ number_format($mission->reward, 0, ',', ' ') }}</td>
            <td class="format">{{ number_format($mission->rental, 0, ',', ' ') }}</td>            
            <td class="center"><i class="fa fa-user {{ $mission->farm_id ? 'w3-text-red' : 'w3-text-green' }} w3-large"></i></td>
            <td>{{ $mission->farm_id ? $mission->farm_id : '---' }}</td>                        
            <td>{{ $mission->status->name }}</td>            
            <td>{{ $mission->success ? 'Yes' : 'No' }}</td>
         </tr>
      @endforeach
   </table>
</div>
@endsection                        