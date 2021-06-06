@extends('layout')

@section('title', 'Savegames')

@section('header_text','Stored savegames')

@section('stylesheet')
<style type="text/css">
  td.format, th.format {
    text-align: right;
  }
</style>
@endsection

@section('content')
      <div class="w3-container">
        <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
          <tr>
            <th>#</th>
            <th>Game name</th>
            <th>Player</th>
            <th class="format">Money</th>
            <th class="format">Time played</th>
            <th class="format">Game day</th>
            <th class="format">Game time</th>
            <th class="format">Saved</th>
          </tr>
        @foreach($savegames as $savegame)
          <tr>
            <td>{{ $loop->remaining + 1 }}</td>
            <td>{{ $savegame->name }}</td>
            <td>{{ $savegame->player_name }}</td>            
            <td class="format">{{ number_format($savegame->money, 0, ',', ' ') }}</td>
            <td class="format">{{ round($savegame->play_time, 0, PHP_ROUND_HALF_UP) }}</td>
            <td class="format">{{ $savegame->game_day }}</td>
            <td class="format">{{ (strlen($savegame->day_hour) == 1 ? '0' . $savegame->day_hour : $savegame->day_hour) . ':' . (strlen($savegame->day_min) == 1 ? '0' . $savegame->day_min : $savegame->day_min) }}</td>
            <td class="format">{{ date_format(date_create($savegame->save_date),"d-m-Y") }}</td>
          </tr>
        @endforeach
        </table>
      </div>
@endsection                        