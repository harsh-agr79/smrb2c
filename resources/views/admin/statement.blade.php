@extends('layouts/admin')

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
                        <th>15 days</th>
                        <th>25 days</th>
                        <th>35 days</th>
                        <th>45 days</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $a = 0;
                        $sum = 0;
                    @endphp
                    @for ($i = 0; $i < count($data); $i++)
                        <tr ondblclick="openbs('{{ $data[$i]['id'] }}')">
                            <td>
                                <div style="height: 20px; width:5px;"></div>
                            </td>
                            <td>{{ $a = $a + 1 }}</td>
                            <td class="name">{{ $data[$i]['name'] }}</td>
                            <td class="shop" style="display: none;">{{ $data[$i]['shopname'] }}</td>

                            <td
                                class="black-text  @if ($data[$i]['type'] == 'dealer') purple lighten-5 @elseif($data[$i]['type'] == 'wholesaler') lime lighten-5 @elseif($data[$i]['type'] == 'retailer') light-blue lighten-5 @else @endif">
                                {{ $data[$i]['type'] }}</td>
                            <td class="{{ $data[$i]['bal_type'] }} lighten-5 bal black-text ">
                                {{ money($data[$i]['balance']) }}</td>
                            @if ($data[$i]['bal_type'] == 'red')
                                <span class="hide">{{ $sum = $sum + $data[$i]['balance'] }}</span>
                            @endif
                            <td class="{{ $data[$i]['bal_type'] }} lighten-5 bal black-text ">
                                @if ($data[$i]['fif'] < 0)
                                    0
                                @else
                                    {{ money($data[$i]['fif']) }}
                                @endif
                            </td>
                            <td class="{{ $data[$i]['bal_type'] }} lighten-5 bal black-text ">
                                @if ($data[$i]['twe'] < 0)
                                    0
                                @else
                                    {{ money($data[$i]['twe']) }}
                                @endif
                            </td>
                            <td class="{{ $data[$i]['bal_type'] }} lighten-5 bal black-text ">
                                @if ($data[$i]['thir'] < 0)
                                    0
                                @else
                                    {{ money($data[$i]['thir']) }}
                                @endif
                            </td>
                            <td class="{{ $data[$i]['bal_type'] }} lighten-5 bal black-text ">
                                @if ($data[$i]['fou'] < 0)
                                    0
                                @else
                                    {{ money($data[$i]['fou']) }}
                                @endif
                            </td>
                        </tr>
                    @endfor
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td id="totalrows"></td>
                        <td>: Total Rows</td>
                        <td>Total credit:</td>
                        <td>{{ money($sum) }}</td>
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
            var type = `admin`;
            // console.log(type);
            if (type === 'marketer') {
                window.open('/marketer/balancesheet/' + name, "_self");
            } else {
                window.open('/balancesheet/' + name, "_self");
            }
        }
    </script>
@endsection
