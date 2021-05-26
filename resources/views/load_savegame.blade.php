@extends('layout')

@section('title', 'FS19 - Webstats: Test')

@section('header_text','Testing PHP')

@section('content')
    <div class="w3-container" style="font-size:12px;color:#E7D1B0;background-color:#191919;">
    	<pre style="text-indent:0;">
	      	@php
	      		echo "\n";
				// load
				require base_path() . '/database/csv_load/load/load_savegame/start_savegame_load.php';
			@endphp
		</pre>
    </div>
@endsection