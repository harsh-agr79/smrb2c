@extends('layouts/marketer')

@section('main')
<div>
    <div class="center">
        <h5>Statements</h5>
    </div>
    <div class="mp-card switch row" style="margin: 20px;">
        <div class='input-field col s12 m4'>
            <input class='validate browser-default inp search black-text' onkeyup="searchFun()" autocomplete="off"
                type='search' name='search' id='search' />
            <span class="field-icon" id="close-search"><span class="material-icons" id="cs-icon">search</span></span>
        </div>
        <div class="col s12 m8" style="margin-top: 20px;">
            <label>
                Name
                <input onchange="tog()" type="checkbox">
                <span class="lever"></span>
                Shop Name
            </label>
        </div>
    </div>
    <div class="mp-card" style="overflow-x: scroll;">
        <table class="sortable">
            <thead>
                <tr>
                    <th>|</th>
                    <th>SN</th>
                    <th class="name">Name</th>
                    <th class="shop" style="display: none;">Shop</th>
                    
                    <th class="">type</th>
                    <th class="bal ">Balance</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $a = 0;
                @endphp
                @foreach ($data as $item)
                    @php
                        $bal = explode('|', $item->balance);
                    @endphp
                    <tr ondblclick="openbs('{{ $item->id }}')">
                        <td>
                            <div
                                style="height: 20px; width:5px;"></div>
                        </td>
                        <td>{{ $a = $a + 1 }}</td>
                        <td class="name">{{ $item->name }}</td>
                        <td class="shop" style="display: none;">{{ $item->shopname }}</td>
                        
                        <td
                            class="black-text  @if ($item->type == 'dealer') purple lighten-5 @elseif($item->type == 'wholesaler') lime lighten-5 @elseif($item->type == 'retailer') light-blue lighten-5 @else @endif">
                            {{ $item->type }}</td>
                        <td class="{{ $bal[0] }} lighten-5 bal black-text ">{{ money($bal[1]) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td id="totalrows"></td>
                    <td>Total</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<script>
    function tog() {
        var name = document.getElementsByClassName('name');
        var shop = document.getElementsByClassName('shop');
        $(name).toggle();
        $(shop).toggle();
    }

    function bal() {
        $('.bal').toggle();
    }

    function tdy() {
        $('.tdy').toggle();
    }

    function fdy() {
        $('.fdy').toggle();
    }

    function sdy() {
        $('.sdy').toggle();
    }

    function ndy() {
        $('.ndy').toggle();
    }
</script>
<script>
    const searchFun = () => {
        var filter = $('#search').val().toLowerCase();
        const a = document.getElementById('search');
        const clsBtn = document.getElementById('close-search');
        let table = document.getElementsByTagName('table');
        let tr = $('tbody tr')
        clsBtn.addEventListener("click", function() {
            a.value = '';
            a.focus();
            var filter = '';
            for (var i = 0; i < tr.length; i++) {
                tr[i].style.display = "";
            }
            $('#cs-icon').text('search')
        });
        if (filter === '') {
            $('#cs-icon').text('search')
        } else {
            $('#cs-icon').text('close')
        }
        let sum = 0;
        for (var i = 0; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName('td');
            // console.log(td);
            for (var j = 0; j < td.length; j++) {
                if (td[j]) {
                    let textvalue = td[j].textContent || td[j].innerHTML;
                    if (textvalue.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        sum = sum + 1;
                        break;
                    } else {
                        tr[i].style.display = "none"
                    }
                }
            }
        }
        $('#totalrows').text(sum);
    }

    function openbs(name) {
      
            window.open('/marketer/balancesheet/' + name, "_self");
    }
</script>
@endsection