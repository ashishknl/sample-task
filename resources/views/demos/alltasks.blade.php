@extends('template')

@section('content')

  <h3 class="text-center title-color">Trello Replica</h3>
  <div class="well" id="app">
    <task-draggable  :all-contents="{{ $allContents }}" :all-columns="{{ $allColumns }}" :all-tasks="{{ $allTasks }}" :tasks-completed="{{ $tasksCompleted }}" :tasks-not-completed="{{ $tasksNotCompleted }}"></task-draggable>
  </div> <!-- end app -->
  <h5 class="export-db"><a class="export-db__link" href="https://phplaravel-596836-2068084.cloudwaysapps.com/getdump">Export DB</a> </h5>
 <!-- Modal -->

<script src="{{ asset('js/app.js') }}"></script> 
    
@stop