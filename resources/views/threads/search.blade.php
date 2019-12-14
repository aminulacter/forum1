@extends('layouts.app')

@section('content')
 <search-res  :trending = "{{ json_encode($trending) }}" :question = {{ json_encode($question) }}></search-res>



@endsection
