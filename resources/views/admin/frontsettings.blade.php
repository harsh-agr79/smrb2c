@extends('layouts/admin')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 10px;">
            <form action="{{ route('addimg') }}" method="post" enctype="multipart/form-data" class="col s12 row">
                @csrf
                <div class="input-field col s12 m12">
                    <div class="file-field input-field">
                        <div class="btn green accent-4 black-text">
                            <span>File</span>
                            <input id="image" type="file" name="img[]" multiple required>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path" placeholder="Upload cover photo" type="text">
                        </div>
                    </div>
                </div>
                <div class="center col s12">
                    <button class="btn waves-effect waves-light green accent-4 black-text"
                        onclick="M.toast({html: 'Adding Customer, Please wait...'})" type="submit" name="action">Submit
                        <i class="material-icons right black-text">send</i>
                    </button>
                </div>
            </form>
        </div>
        <div class="mp-card" style="margin-top: 10px">
            <table>
                <thead>
                    <th>Image</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td><img src="{{ asset($item->image) }}" class="materialboxed" height="60" alt="">
                            </td>
                            <td><a href="{{ url('delete/frontimg/' . $item->image) }}"
                                    class="btn-large red white-text">Delete</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mp-card" style="margin-top: 10px;">
            <div class="center">Announcement Message</div>
            <form style="margin-top: 10px;" action="{{ route('addmsg') }}" method="POST">
                @csrf
                <input type="text" name="message" class="browser-default inp" @if (count($data2) > 0)
                disabled
            @endif autocomplete="off" placeholder="Write Message">
                <div class="center" style="margin-top: 10px">
                   
                    <button class="btn green accent-4  @if (count($data2) > 0)
                            disabled
                        @endif">
                        Add Message
                    </button>
                </div>
            </form>
        </div>
        <div class="mp-card" style="margin-top: 10px;">
            <table>
                <thead>
                    <th>Message</th>
                    <th>Delete</th>
                </thead>
                <tbody>
                    @if (!$data2->isEmpty())
                        @foreach ($data2 as $item)
                            <tr>
                                <td>{{ $item->message }}</td>
                                <td><a href="{{url('delete/frontmsg/'.$item->id)}}" class="btn red">Delete</a></td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        </div>

    </div>
@endsection
