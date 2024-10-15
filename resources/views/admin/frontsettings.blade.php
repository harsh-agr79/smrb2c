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

        <div>
            @foreach ($banners as $banner)
                <div class="mp-card" style="margin-top: 10px;">
                    <div class="center">
                        <img src="{{asset($banner->image)}}" style="height: 100px;" alt="">
                    </div>
                    <form action="{{ route('updateBanner', $banner->id) }}" method="post" enctype="multipart/form-data"
                        class="col s12 row">
                        @csrf
                        @method('PUT')

                        <div class="input-field col s12 m12">
                            <div class="file-field input-field">
                                <div class="btn green accent-4 black-text">
                                    <span>File</span>
                                    <input id="image" type="file" name="image" accept="image/*">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path" placeholder="Upload new image" type="text"
                                        value="{{ $banner->image }}">
                                </div>
                            </div>
                        </div>

                        <div class="input-field col s12">
                            <input type="text" name="url" value="{{ $banner->url }}" required>
                            <label for="url">Banner URL</label>
                        </div>

                        <div class="center col s12">
                            <button class="btn waves-effect waves-light green accent-4 black-text"
                                onclick="M.toast({html: 'Updating Banner, Please wait...'})" type="submit"
                                name="action">Update
                                <i class="material-icons right black-text">send</i>
                            </button>
                        </div>
                    </form>
                </div>
            @endforeach

        </div>
    </div>
@endsection
