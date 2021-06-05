@extends('layout')

@section('title', 'FS19 - Webstats: About')

@section('header_text','About')

@section('content')
    <div class="w3-container">
      <p>Hi,</br>
      goal of this project is to create FS19 - LataStats (FS19 - Webstats) powered by Maria DB and Laravel PHP framework.
  	  </br>It will load your savegame's xml files from Farming Simulator 19 each midnight or so and store all relevant information in database.</p>
      <p>Then the Laravel PHP framework will provide those stored data to you in nice structured template. You should be able to select specific savegame and see your stats in that particular game day.
      </br> You will be also able to see all information about your property and husbandries for the given savegame / day in the game.</p>
      <p>You also should be able to see your financial history trough all the days in the game. You would also be able to see history of your property and animal husbandries.</p>
      <p>This project should provide you lots of interesting information and statistics about your vehicles, animals and finances in nice structured tables and graphs.</p>
  	  <p>It will suport single player savegame and also multiplayer savegame as well.</p>
  	  <p>Project is in early stage of development as you may notice.</p>
      <p>Best Regards, </br>the.geremy</p>
    </div>
@endsection