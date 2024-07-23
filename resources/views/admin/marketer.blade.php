@extends('layouts.admin')

@section('main')
<div>
    
    <div class="mp-card" style="margin-top: 5vh;">
        <div>
            <h5 class="center">Marketer</h5>
        </div>
        <table>
            @foreach ($data as $item)
                <tr>
                    <td>{{$item->userid}}</td>
                    <td><a href="{{url('/addmarketer/'.$item->id)}}" class="amber black-text btn-small"><i class="material-icons textcol">edit</i></a></td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection