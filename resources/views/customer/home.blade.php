@extends('layouts.customer')
{{-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> --}}
@section('main')
    <style>
        .bottom-sheet {
            max-height: 65vh !important;
            padding: 5px;
            border-radius: 20px 20px 0px 0px !important;
        }
    </style>
    <style>
        .prod-admin-img {
            max-height: 10vh;
            max-width: 13vh;
        }

        .prod-admin-title {
            font-size: 12px;
            font-weight: 600;
        }

        .prod-admin-det {
            font-size: 10px;
            padding: 0 !important;
            margin: 0 !important;
            font-weight: 500;
        }

        .price-line {
            position: relative;
            top: 7px;
        }

        .prod-admin-price {
            padding: 3px 10px;
            background: rgb(0, 194, 0);
            border-radius: 5px;
            color: black;
            font-size: 13px;
        }

        .prod-admin-inp {
            color: black;
            outline: none;
            padding: 5px;
            border-radius: 5px;
            outline: none;
            border: 1px solid rgb(170, 170, 170);
            width: 10vh;
        }

        .prod-admin-container::-webkit-scrollbar {
            display: none;
        }

        .prod-admin-container {
            margin-left: 20vw;
            margin-right: 20vw;
            height: 65vh;
            overflow-y: scroll;
            margin-top: 10px;
        }

        @media screen and (max-width: 720px) {
            .prod-admin-container {
                margin: 0;
            }

            .mp-caro-item {
                height: 56vw;
                width: 100vw;
            }
        }

        @media screen and (max-width: 900px) {
            .mp-caro-item {
                height: 50vh;
                width: 100vw;
            }
        }

        .table-prod {
            height: 30px;
        }

        .cart-m {
            width: 90vw !important;
        }

        .cart-m ::-webkit-scrollbar {
            width: 15px;
        }
    </style>
    @php
        $cart = $user->cart;
        if ($cart != null) {
            $break = explode(':', $cart);
            $prod = explode(',', $break[0]);
            $qty = explode(',', $break[1]);
        } else {
            $break = [];
            $prod = [];
            $qty = [];
        }
    @endphp
    {{-- <div class='input-field' style="margin-top:10px; padding: 5px;">
        <input class='validate browser-default search inp black-text z-depth-1' onkeyup="searchFun()" autocomplete="off"
            type='search' id='search' />
        <span class="field-icon" id="close-search"><span class="material-icons" id="cs-icon">search</span></span>
    </div>

    <div class="mp-card" style="padding: 10px;">
        <form id="filterform">
            @foreach ($brands as $item)
                <span style="margin-right: 10px">
                    <label>
                        <input type="checkbox" name="{{ $item->id }}brd" value="{{ $item->id }}brd"
                            onclick="Filter()" />
                        <span>{{ $item->name }}</span>
                    </label>
                </span>
            @endforeach
        </form>
            <br>
        <form id="filformcat">
            @foreach ($category as $item)
                <span style="margin-right: 10px">
                    <label>
                        <input type="checkbox" name="{{ $item->id }}cat" value="{{ $item->id }}cat"
                            onclick="Filter()" />
                        <span>{{ $item->category }}</span>
                    </label>
                </span>
            @endforeach
      <form>
    </div> --}}
    <div class="row center" style="margin: 0; padding: 0;">
        <div class="col s2">
            <div class="btn green accent-4" style="margin-top: 16px;" onclick="getcart()"><i class="material-icons">shopping_cart</i></div>
        </div>
        <div class='col s8 input-field' style="margin-top: 14px;">
            <input class='validate browser-default search inp black-text z-depth-1' onkeyup="searchFun()" autocomplete="off"
                type='search' id='search' />
            <span class="field-icon" id="close-search" onclick="$('#search').val(''); searchFun();"><span class="material-icons" style="font-size: 15px;"
                    id="cs-icon">search</span></span>
        </div>
        <div class="col s2">
            <div class="btn green accent-4 modal-trigger" href="#modal1" style="margin-top: 16px;"><i
                    class="material-icons">filter_list</i></div>
        </div>
        <div class="col s12 center" style="padding: 0 !important; margin: -10px !important;">
            <div style="font-size: 10px;" class="center">Bill Amount: <span id="totalamtout">0</span></div>
        </div>
    </div>

    <div id="modal1" class="modal">
        
        <div class="center">
            <h5>Filter company/category</h5>
        </div>
        <div class="row" style="padding: 10px;">
            <div class="col s6">
                <form id="filterform">
                    @foreach ($brands as $item)
                        <div>
                            <label>
                                <input type="checkbox" name="{{ $item->id }}brd" value="{{ $item->id }}"
                                    onclick="Filter()" />
                                <span><div class="valign-wrapper"><span>{{ $item->name }}</span><img src="{{asset($item->logo)}}" style="height: 23px; margin-left: 3px; border-radius: 50%;" alt=""></div></span>
                            </label>
                        </div>
                    @endforeach
                </form>
            </div>
            <div class="col s6">
                <form id="filformcat">
                    @foreach ($category as $item)
                        <div>
                            <label>
                                <input type="checkbox" name="{{ $item->id }}cat" value="{{ $item->id }}"
                                    onclick="Filter()" />
                                <span>{{ $item->category }}</span>
                            </label>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>
    </div>
    <div class="fixed-action-btn">
        <a class="btn red" onclick="getcart()">Order<i class="material-icons right">send</i></a>
    </div>
    <div id="cart-modal" class="modal cart-m"
        style="padding: 0 !important; margin: 0 !important; max-width: 95vw !important;">
        <div class="modal-content" style="padding: 5px !important;">
            <h4 class="center">Cart</h4>
            <table>
                <thead>
                    <th>SN</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </thead>
                <tbody id="cart-table-body">

                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: 600; font-size: 12px;">Total</td>
                        <td style="font-weight: 600; font-size: 12px;" id="cart-total"></td>
                    </tr>
                </tfoot>
            </table>
            <div class="modal-footer row" style="margin: 0; padding: 0;">
                <div class="col s6">
                    <a href="{{ url('/user/savecart') }}" class="btn-small amber darken-2">Save Basket</a>
                </div>
                <div class="col s6">
                    <button class="btn-small green accent-4" id="confirmOrder">Confirm Order</button>
                </div>

            </div>
        </div>
    </div>
    <form id="form-main-cart">
        <div style="height: 75vh; overflow-y: scroll; margin-top: 10px;" class="prod-admin-container">
            {{-- @foreach ($prods as $item)
                <div class="prod-box searchable center {{ $item->brand_id }}brd {{ $item->category_id }}cat">
                    <div class="prod-img" onclick="details({{ $item->id }})"
                        style="background: url('@if ($item->images != '' || $item->images != null) {{ asset(explode('|', $item->images)[0]) }}@else{{ asset('images/prod.jpg') }} @endif') no-repeat center center; background-size: cover;">
                        <div>
                            <span class="company-title left" style="margin: 3px;">
                                {{ $item->brand }}
                            </span>
                            <span class="company-title right" style="margin: 3px;">
                                {{ $item->category }}
                            </span>
                        </div>

                    </div>
                    <div class="prod-det">
                        <span
                            style="margin: 0; padding: 0; font-weight: 600; font-size: 13px">{{ $item->name }}</span><br>
                        <span style="margin: 0; padding: 0; font-weight: 600; font-size: 11px">Rs.
                            {{ $item->price }}</span>

                    </div>
                    <div class="add-to-cart container" style="margin-top: 5px;">
                        <div class="row container">
                            <span class="col s3 prod-btn" style="border-radius: 5px 0 0 5px;"
                                onclick="minus('{{ $item->id }}')"><i class="material-icons">remove</i></span>
                            <input type="hidden" class="prodids" name="prodid[]" value="{{ $item->id }}">
                            <input type="number" class="col s6 browser-default inp qtys" id="{{ $item->id }}cartip"
                                onkeyup="updatecart()" style="height: 32px; text-align:center; border-radius:0;"
                                name="qty[]"
                                @if (in_array($item->id, $prod)) value="{{ getqty($item->id, $prod, $qty) }}"
                                @else
                                    value="0" @endif>
                            <span class="col s3 prod-btn" style="border-radius: 0 5px 5px 0; "
                                onclick="plus('{{ $item->id }}')"><i class="material-icons">add</i></span>
                        </div>
                    </div>
                </div>
            @endforeach --}}
            @foreach ($prods as $item)
                <div class="mp-card row prod-admin searchable {{ $item->brand_id }}brd {{ $item->category_id }}cat"
                    style="margin: 3px; padding: 10px;">
                    <div class="col s4" style="padding: 0;  margin: 0;">
                        @php
                            $a = explode('|', $item->images);
                        @endphp
                        <img src="@if ($item->images != '' || $item->images != null) {{ asset(explode('|', $item->images)[count($a) - 1]) }}@else{{ asset('images/prod.jpg') }} @endif"
                            class="prod-admin-img materialboxed" alt="">
                    </div>
                    <div class="col s8 row" style="padding: 0; margin: 0;">
                        <div class="col s12" style=" margin: 0; padding: 0;" onclick="details({{ $item->id }})">
                            <span class="prod-admin-title">{{ $item->name }}</span>
                        </div>
                       
                        <div class="col s12 row" style="padding: 0;  margin: 0;" onclick="details({{ $item->id }})">
                            <span class="prod-admin-det col s8">{{ $item->brand }} {{ $item->category }}</span>
                            <span class="prod-admin-det col s4">
                                @if ($item->stock == 'on')
                                    <span class="red-text right">Out of Stock</span>
                                @else
                                    <span class="green-text right">In Stock</span>
                                @endif
                            </span>
                        </div>
                        @if ($item->featured != NULL || $item->offer != NULL)
                        <div class="col s12" style="margin: 0 !important; padding: 0;">
                            @if ($item->featured != NULL)  <span style="padding:1px 3px; border-radius: 3px;" class="red white-text">NEW !!</span>@endif @if ($item->offer != NULL)<span class="amber darken-4 white-text" style="padding:1px 3px; border-radius: 3px;">{{$item->offer}}</span>@endif 
                        </div>
                        @endif
                        <div class="row col s12 price-line" style="padding: 0;  margin: 0;">
                            <div class="col s4 center" style="margin-top: 5px; padding: 0;"><span
                                    class="prod-admin-price">Rs.{{ $item->price }}</span></div>
                            {{-- <div class="col s7 add-to-cart container right">
                            <div class="row container">
                                <span class="col s3 prod-btn" style="border-radius: 5px 0 0 5px;"
                                    onclick="minus('{{ $item->id }}')"><i class="material-icons">remove</i></span>
                                <input type="hidden" class="prodids" name="prodid[]" value="{{ $item->id }}">
                                <input type="number" min="0" class="col s6 browser-default inp qtys" id="{{ $item->id }}cartinp"
                                    onkeyup="updatecart()" style="height: 32px; text-align:center; border-radius:0;"
                                    name="qty[]"
                                    @if (in_array($item->id, $prod)) value="{{ getqty($item->id, $prod, $qty) }}"
                                    @else
                                        value="0" @endif>
                                <span class="col s3 prod-btn" style="border-radius: 0 5px 5px 0; "
                                    onclick="plus('{{ $item->id }}')"><i class="material-icons">add</i></span>
                            </div>
                        </div> --}}
                            <div class="col s8">
                                <input type="hidden" class="prodids" name="prodid[]" value="{{ $item->id }}">
                                <input type="number" id="{{ $item->id }}cartinp" inputmode="numeric" pattern="[0-9]*"
                                    name="qty[]" onkeyup="updatecart()" onchange="updatecart()" placeholder="Quantity"
                                    class="browser-default prod-admin-inp right qtys"
                                    @if (in_array($item->id, $prod)) value="{{ getqty($item->id, $prod, $qty) }}" @endif>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
                <div style="margin-top: 100px;">

                </div>
            <div class="hide">
                <button>Submit</button>
            </div>
        </div>
    </form>
    <div id="details" class="modal bottom-sheet bg-content">
        <div class="modal-content bg-content">
            <div class="row bg-content">
                <div id="mod-caro" style="width: 100%; overflow-x: scroll; display:flex;">

                </div>
                <div class="col s12">
                    <h6 id="mod-name" style="font-weight: 600;"></h6>
                </div>
                <div class="col s4">
                    <span id="mod-brand" style="font-weight: 600;"></span>
                </div>
                <div class="col s4">
                    <span id="mod-category" style="font-weight: 600;"></span>
                </div>
                <div class="col s12">
                    <span id="mod-price" style="font-weight: 600;"></span>
                </div>
                <div class="col s12" style="margin-top: 10px;">
                    <span style="font-weight: 600;">Details:</span>
                    <div style="white-space: pre-wrap" id="mod-details"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        gettotal();

        function Filter() {
            $('.prod-admin').hide()
            $('.prod-admin').removeClass('searchable');
            clsnames = "";
            var formData = $('#filterform').serializeArray()
            var formData2 = $('#filformcat').serializeArray()
            console.log(formData)
            if (formData.length > 0) {
                for (let i = 0; i < formData.length; i++) {
                    if (formData2.length > 0) {
                        for (let j = 0; j < formData2.length; j++) {
                            clsname = ""
                            clsname = "." + formData[i].name + "." + formData2[j].name
                            // console.log(clsname)
                            $(`${clsname}`).addClass('searchable')
                            $(`${clsname}`).show();
                        }
                    } else {
                        $(`.${formData[i].name}`).addClass('searchable')
                        $(`.${formData[i].name}`).show();
                    }
                }
            } else {
                if (formData2.length > 0) {
                    for (let j = 0; j < formData2.length; j++) {
                        $(`.${formData2[j].name}`).addClass('searchable')
                        $(`.${formData2[j].name}`).show();
                    }
                } else {
                    $('.prod-admin').addClass('searchable')
                    $('.prod-admin').show();
                }
            }

        }
        const searchFun = () => {
            var filter = $('#search').val().toLowerCase();
            const a = document.getElementById('search');
            const clsBtn = document.getElementById('close-search');
            let cont = document.getElementsByClassName('product-container');
            var prod = $('.searchable')
            if (filter === '') {
                $('#cs-icon').text('search')
            } else {
                $('#cs-icon').text('close')
            }
            for (var i = 0; i < prod.length; i++) {
                let span = prod[i].getElementsByTagName('span');
                // console.log(td);
                for (var j = 0; j < span.length; j++) {
                    if (span[j]) {
                        let textvalue = span[j].textContent || span[j].innerHTML;
                        if (textvalue.toLowerCase().indexOf(filter) > -1) {
                            prod[i].style.display = "";
                            break;
                        } else {
                            prod[i].style.display = "none"
                        }
                    }
                }
            }
        }

        function plus(id) {
            a = parseInt($(`#${id}cartinp`).val())
            a = a + 1
            $(`#${id}cartinp`).val(a)
            updatecart()
        }

        function minus(id) {
            a = parseInt($(`#${id}cartinp`).val())
            if (a != 0) {
                a = a - 1
                $(`#${id}cartinp`).val(a)
            }
            updatecart()
        }

        function updatecart() {
            var prodid = $('.prodids')
            var qty = $('.qtys')
            prod = []
            qt = []
            for (let i = 0; i < prodid.length; i++) {
                prod.push(parseInt(prodid[i].value))
                qt.push(parseInt(qty[i].value))
            }
            var formdata = new FormData()
            formdata.append('prod', prod)
            formdata.append('qt', qt)
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/user/updatecart",
                data: formdata,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    // console.log(response)
                    gettotal();
                }
            })
        }

        function gettotal() {
            $.ajax({
                url: "/user/gettotal",
                type: "GET",
                success: function(response) {
                    // console.log(response)
                    $("#totalamtout").text(response)
                }
            })
        }

        function getcart() {
            $.ajax({
                url: "/user/getcart",
                type: "GET",
                success: function(response) {
                    console.log(response);
                    $('#cart-modal').modal("open");
                    $('#cart-table-body').text('');
                    a = 0
                    t = 0
                    $.each(response, function(key, item) {
                        if (item.image == null) {
                            image = '/images/prod.jpg'
                        } else {
                            image = "/" + item.image
                        }
                        a = a + 1
                        t = t + item.total
                        $('#cart-table-body').append(`
                        <tr id="${item.id}carttd">
                            <td>${a}</td>
                            <td><img src="${image}" class="table-prod"></td>
                            <td style="font-size: 10px;">${item.name}
                                <br>
                        <span style="font-size: 7px; margin-top:-10px;">
                            ${item.brand} ${item.category}</span></td>
                           
                            <td class="center"><input type="number" id="${item.id}cartlist"
                                        inputmode="numeric" pattern="[0-9]*" placeholder="Quantity"
                                        class="browser-default prod-admin-inp gtquantity"
                                        onkeyup="changequantity(${item.id})" min="0" onchange="changequantity(${item.id})"
                                        onfocusout="changequantity2({{ $item->id }})" style="width: 7vh !important;" value="${item.quantity}"></td>
                                        <td>${item.price}</td>
                            <td>${item.total}</td>
                        </tr>
                        `)
                    })
                    $('#cart-total').text(t);
                }
            })
        }

        function details(id) {
            $.ajax({
                type: "GET",
                url: "/user/finditem/" + id,
                dataType: "json",
                success: function(response) {
                    $("#mod-caro").text("")
                    var images = response.images.split("|")
                    for (let i = 0; i < images.length; i++) {
                        $("#mod-caro").append(
                            `<div><img style="height:200px; margin: 10px;" src="/${images[i]}"></div>`)
                    }
                    $('#mod-name').text(response.name)
                    $('#mod-price').text('Rs.' + response.price)
                    $('#mod-category').text(response.category)
                    $('#mod-brand').text(response.brand)
                    $('#mod-details').text(response.details)
                    $('#mod-img1').attr('src', '/storage/media/' + response.img)
                    $('#mod-img2').attr('src', '/storage/media/' + response.img2)
                    $('#details').modal('open');
                    history.pushState(null, document.title, location.href);
                    $('.carousel.carousel-slider').carousel({
                        fullWidth: true,
                        indicators: true
                    });
                    $('.materialboxed').materialbox();
                }
            })
        }
        $(document).ready(function() {
            $('.carousel.carousel-slider').carousel({
                fullWidth: true,
                indicators: true
            });
        });

        function changequantity(id) {
            $(`#${id}cartinp`).val($(`#${id}cartlist`).val())
            updatecart()
            // getcart()
        }

        function changequantity2(id) {
            updatecart()
            getcart()
        }

       
    </script>
     <script src="https://cdn.socket.io/4.4.0/socket.io.min.js" integrity="sha384-1fOn6VtTq3PWwfsOrk45LnYcGosJwzMHv+Xh/Jx5303FVOXzEnw0EpLv30mtjmlj" crossorigin="anonymous"></script>
     <script>
         $(function(){
                 let ip_address = 'smr.startuplair.com';
                 // let socket_port = '3000';
                 let socket = io(ip_address);
     
                 var name = `{{$user->name}}`
 
                 var message = "New Order From " + name
                $('#confirmOrder').on('click', function() {
                     socket.emit('sendNotifToAdmin', message);
                     window.location.href = "/user/confirmcart";
                })
             })
     </script>
@endsection
