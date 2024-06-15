@extends('layouts.user')
@section('content')
<!-- Remove everything INSIDE this div to a really blank page -->
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2md font-semibold text-gray-700 dark:text-gray-200 text-center">
        @if (Auth::check())
            Last TimeStamp: 
            {{ Auth::user()->last_login_at ? \Carbon\Carbon::parse(Auth::user()->last_login_at)->isoFormat('dddd , d MMMM , Y') : 'Unknown' }}
           ( {{ Auth::user()->last_login_at ? \Carbon\Carbon::parse(Auth::user()->last_login_at)->format('H:i') : 'Unknown' }}  )
           {{ Auth::user()->last_login_location ? Auth::user()->last_login_location : 'Unknown' }}
        @endif
    </h2>
</div>
@endsection
