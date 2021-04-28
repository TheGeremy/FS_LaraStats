@extends('layout')

@section('title', 'FS19 - Webstats: Missions')

@section('header_text','Available missions')

@section('content')
<div class="w3-container">
   <table class="w3-table w3-striped w3-white">
      <tr>
         <td><i class="fa fa-user w3-text-blue w3-large"></i></td>
         <td>New record, over 90 views.</td>
         <td><i>10 mins</i></td>
      </tr>
      <tr>
         <td><i class="fa fa-bell w3-text-red w3-large"></i></td>
         <td>Database error.</td>
         <td><i>15 mins</i></td>
      </tr>
      <tr>
         <td><i class="fa fa-users w3-text-yellow w3-large"></i></td>
         <td>New record, over 40 users.</td>
         <td><i>17 mins</i></td>
      </tr>
      <tr>
         <td><i class="fa fa-comment w3-text-red w3-large"></i></td>
         <td>New comments.</td>
         <td><i>25 mins</i></td>
      </tr>
      <tr>
         <td><i class="fa fa-bookmark w3-text-blue w3-large"></i></td>
         <td>Check transactions.</td>
         <td><i>28 mins</i></td>
      </tr>
      <tr>
         <td><i class="fa fa-laptop w3-text-red w3-large"></i></td>
         <td>CPU overload.</td>
         <td><i>35 mins</i></td>
      </tr>
      <tr>
         <td><i class="fa fa-share-alt w3-text-green w3-large"></i></td>
         <td>New shares.</td>
         <td><i>39 mins</i></td>
      </tr>
   </table>
</div>
@endsection                        