@extends('layouts/customer')


@section('main')
    <style>
        .collapsible-header {
            padding: 10px;
            margin: 0;
            font-size: 13px;
        }

        .collapsible,
        .collapsible li {
            border: none;
            box-shadow: none;
        }
    </style>
    <div>
        <div class="mp-card" style="margin-top: 30px;">
            <form>
                <div class="row">
                    <div class="input-field col l6 s6">
                        <label>From:</label>
                        <input type="date" name="date" value="{{ $date }}" class="inp browser-default black-text">
                    </div>
                    <div class="input-field col l6 s6">
                        <label>To:</label>
                        <input type="date" name="date2" value="{{ $date2 }}"
                            class="inp browser-default black-text">
                    </div>
                    <div class="input-field col s6 l4 m4">
                        <input type="text" name="product" id="product" value="{{ $product }}"
                            placeholder="Product" class="autocomplete browser-default inp black-text" autocomplete="off">
                    </div>
                    <div class='input-field col l4 m4 s12'>
                        <input class='validate browser-default inp search black-text z-depth-1' onkeyup="searchFun()" autocomplete="off"
                            type='search' id='search' />
                        <span class="field-icon" id="close-search"><span class="material-icons"
                                id="cs-icon">search</span></span>
                    </div>
                    <div class="col s6 m2 l2">
                        <button class="btn green accent-4">Apply</button>
                    </div>
                    <div class="col s6 m2 l2">
                        <a class="btn green accent-4" href="{{ url('/sortanalytics') }}">Clear</a>
                    </div>
                </div>
            </form>
        </div>

        <div>
            @if ($datatype == 'nodata')
                <div class="center" style="margin-top: 200px;">
                    <h5>Select Details to View Analytics</h5>
                </div>
            @elseif($datatype == 'np')
                <div class="green accent-4 center" style="padding: 5px; margin-top: 20px; border-radius: 10px;">
                    <h5 class="black-text" style="font-weight: 600;">Total Sales:
                        {{ money($nptotal[0]->samt) }}</h5>
                    <h5 class="black-text" style="font-weight: 600;">Total Quantity:
                        {{ $nptotal[0]->sum }}</h5>
                </div>
                <div class="mp-card" style="margin-top: 20px;">
                    <table class="sortable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Detail</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($npdata as $item)
                                <tr class=" @if ($item->seen == '') z-depth-2 @endif"
                                    ondblclick="opendetail({{ $item->order_id }}, '{{ $item->seen }}', '{{ $item->mainstatus }}')">
                                    <td>{{ $item->date }}</td>
                                    <td>
                                        <div class="row" style="padding: 0; margin: 0;">
                                            <div class="col s12" style="font-size: 12px; font-weight: 600;">
                                                {{ $item->name }}</div>
                                            <div class="col s12" style="font-size: 8px;">{{ $item->order_id }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $item->item }}</td>
                                    <td>{{ $item->approvedquantity }}</td>
                                    <td>{{ money(($item->approvedquantity * $item->price * (1 - 0.01 * $item->discount)) * (1-0.01*$item->sdis)) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif($datatype == 'n')
                 @php
                 $quantchart = [];
                 $amtchart = [];
             @endphp
             <div>
                
                 <div class="green accent-4 center" style="padding: 5px; margin-top: 20px; border-radius: 10px;">
                     <h5 class="black-text" style="font-weight: 600;">Total Sales:
                        @if (count($totalsales) > 0)
                        {{ money($totalsales[0]->samt) }}
                        @endif
                         </h5>
                 </div>  
                
                 <div class="mp-card" style="margin-top: 10px;">
                     <ul class="collapsible">
                         @foreach ($catsales as $item)
                             @php
                                 $amtchart[] = ['Category' => $item->brand, 'Amount' => intval($item->samt)];
                                 $quantchart[] = ['Category' => $item->brand, 'Quantity' => $item->sum + 0];
                             @endphp
                             <li>
                                 <div class="collapsible-header row">
                                     <div class="col s4 blue-text">{{ $item->brand }}</div>
                                     <div class="col s4">{{ $item->sum }}</div>
                                     <div class="col s4">{{ money($item->samt) }}</div>
                                 </div>
                                 <div class="collapsible-body"><span>
                                         <div>
                                             @php
                                                 $subcates = DB::table('categories')
                                                     ->pluck('category')
                                                     ->toArray();
                                             @endphp
                                             <form id="{{ $item->brand }}form">
                                                 @foreach ($subcates as $item3)
                                                     <label style="margin-right: 15px;">
                                                         <input type="checkbox" name="{{ $item3 }}"
                                                             value="{{ $item3 }}"
                                                             onclick="Filter('{{ $item->brand }}')" />
                                                         <span>{{ $item3 }}</span>
                                                     </label>
                                                 @endforeach
                                                 {{-- <label style="margin-right: 15px;">
                                                     <input type="checkbox" name="incall" value="incall"
                                                         onclick="Filter('{{ $item->category }}')" />
                                                     <span>Must Include All Selected Tags</span>
                                                 </label> --}}
                                             </form>
                                         </div>
                                         <table class="sortable">
                                             <thead>
                                                 <tr>
                                                     <th>Item</th>
                                                     <th>Quantity</th>
                                                     <th>Amount</th>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 @foreach ($data[$item->brand] as $item2)
                                                     <tr class="{{ $item->brand }} {{$item2->category}}"
                                                         ondblclick="openanadetail('{{ $date }}', '{{ $date2 }}', '{{ $item2->item }}')">
                                                         <td>{{ $item2->item }}</td>
                                                         <td>{{ $item2->sum }}</td>
                                                         <td>{{ money($item2->samt) }}</td>
                                                     </tr>
                                                 @endforeach
                                                 @foreach ($data2[$item->brand] as $item2)
                                                     <tr
                                                         class="{{ $item->brand }} {{$item2->category}}">
                                                         <td>{{ $item2->name }}</td>
                                                         <td>0</td>
                                                         <td>0</td>
                                                     </tr>
                                                 @endforeach
                                             </tbody>
                                         </table>
                                     </span></div>
                             </li>
                         @endforeach
                     </ul>
                 </div>
         
                 <div class="row mp-card" style="margin-top: 20px;">
                     <div class="col m6 s12">
                         <div class="mp-chart" id="piechart_3d" style="width: auto; height: 500px;"></div>
                     </div>
                     <div class="col m6 s12">
                         <div class="mp-chart" id="piechart_3d2" style="width: auto; height: 500px;"></div>
                     </div>
                 </div>
         
             </div>
             <script>
                 function openanadetail(date, date2, product) {
                     var type = ``;
                     // console.log(type);
                    //  if (type === 'marketer') {
                         var url = '/user/mainanalytics?date=' + date + '&date2=' + date2  + '&product=' + product
                         url = url.replace(/\(/g, "%28").replace(/\)/g, "%29").replace(/\+/g, '%2B'); 
                         window.open(url,
                             "_self");
                    //  } else {
                    //      var url = '/sortanalytics?date=' + date + '&date2=' + date2 + '&name=' + name + '&product=' + product
                    //      url = url.replace(/\(/g, "%28").replace(/\)/g, "%29").replace(/\+/g, '%2B'); 
                    //      window.open(url,
                    //          "_self");
                    //  }
         
                 }
         
                 function Filter(cat) {
                     $(`.${cat}`).hide();
                     var formData = $(`#${cat}form`).serializeArray()
                     if (formData.length == 0) {
                         $(`.${cat}`).show();
                     } else if (formData[formData.length - 1].name === 'incall') {
                         var clsnames = '';
                         for (let i = 0; i < formData.length - 1; i++) {
                             clsnames += "." + formData[i].name
                         }
                         $(`${clsnames}`).show();
                     } else {
                         if (formData.length > 0) {
                             for (let i = 0; i < formData.length; i++) {
                                 $(`.${formData[i].name}`).show();
                             }
                         } else {
                             $(`.${cat}`).show();
                         }
                     }
         
                 }
             </script>
         
             <script>
                 google.charts.load("current", {
                     packages: ["corechart"]
                 });
                 google.charts.setOnLoadCallback(drawChart);
                 google.charts.setOnLoadCallback(drawChart2);
         
                 function drawChart() {
         
                     var chartdata = @json($amtchart);
                     console.log(chartdata)
                     const mta = chartdata.map(d => Array.from(Object.values(d)))
                     var data = new google.visualization.DataTable();
                     data.addColumn('string', '');
                     data.addColumn('number', '');
         
                     data.addRows(mta);
         
                     var options = {
                         title: 'Sales By Amount',
                         is3D: true,
                         backgroundColor: {
                             fill: 'transparent'
                         }
                     };
         
                     var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                     chart.draw(data, options);
                 }
         
                 function drawChart2() {
         
                     var chartdata = @json($quantchart);
                     console.log(chartdata)
                     const mta = chartdata.map(d => Array.from(Object.values(d)))
                     var data = new google.visualization.DataTable();
                     data.addColumn('string', '');
                     data.addColumn('number', '');
         
                     data.addRows(mta);
         
                     var options = {
                         title: 'Sales By Quantity',
                         is3D: true,
                         backgroundColor: {
                             fill: 'transparent'
                         },
                         textStyle: {
                             color: '#FFF'
                         }
                     };
         
                     var chart = new google.visualization.PieChart(document.getElementById('piechart_3d2'));
                     chart.draw(data, options);
                 }
             </script>
             <script>
                 $(document).ready(function() {
                     $.ajax({
                         type: 'get',
                         url: '/findcustomer',
                         success: function(response2) {
         
                             var custarray2 = response2;
                             var datacust2 = {};
                             for (var i = 0; i < custarray2.length; i++) {
         
                                 datacust2[custarray2[i].name] = null;
                             }
                             // console.log(datacust2)
                             $('input#customer').autocomplete({
                                 data: datacust2,
                             });
                         }
                     })
                 })
             </script>
            @elseif($datatype == 'p')
                <div class="green accent-4 center" style="padding: 5px; margin-top: 20px; border-radius: 10px;">
                    <h5 class="black-text" style="font-weight: 600;">Total Sales:
                        {{ money($ptotal[0]->samt) }}</h5>
                    <h5 class="black-text" style="font-weight: 600;">Total Quantity:
                        {{ $ptotal[0]->sum }}</h5>
                </div>
                <div class="mp-card" style="margin-top: 20px">
                    <table class="sortable">
                        <thead>
                            <tr>
                                <th>|</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Type</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pdata as $item)
                                <tr
                                    ondblclick="openanadetail('{{ $date }}', '{{ $date2 }}', '{{ $item->item }}')">
                                    <td>
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{$item->contact}}</td>
                                    <td
                                class="black-text  @if ($item->type == 'dealer') purple lighten-5 @elseif($item->type == 'wholesaler') lime lighten-5 @elseif($item->type == 'retailer') light-blue lighten-5 @else @endif">
                                {{ $item->type }}</td>
                                    <td>{{ $item->item }}</td>
                                    <td>{{ $item->sum }}</td>
                                    <td>{{ money($item->samt) }}</td>
                                </tr>
                            @endforeach
                            @foreach ($pnodata as $item)
                                <tr>
                                    <td></td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->contact }}</td>
                                    <td
                                    class="black-text  @if ($item->type == 'dealer') purple lighten-5 @elseif($item->type == 'wholesaler') lime lighten-5 @elseif($item->type == 'retailer') light-blue lighten-5 @else @endif">
                                    {{ $item->type }}</td>
                                    <td>{{ $product }}</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        function opendetail(orderid, seen, ms) {
            // var admintype = ``;
            // if (admintype == "admin" || jQuery.inArray("detail/{id}", perms) > -1) {
            //     if (admintype == "admin" || seen == 'seen' || jQuery.inArray("firstorderview", perms) > -1) {
                    window.open('/detail/' + orderid, "_self");
            //     }
            // } else if (admintype == 'staff' && jQuery.inArray("chalan", perms) > -1 && ms == 'deep-purple') {
            //     window.open('/chalandetail/' + orderid, "_self");
            // }
            // else if(admintype == 'marketer'){
            //     window.open('/marketer/detail/' + orderid, "_self");
            // }
        }

        function openanadetail(date, date2, product) {
            var type = ``;
            // console.log(type);
            // if (type === 'marketer') {
                var url = '/user/mainanalytics?date=' + date + '&date2=' + date2 + '&product=' + product
                url =  url.replace(/\(/g, "%28").replace(/\)/g, "%29").replace(/\+/g, '%2B'); 
                window.open(url,
                    "_self");
            // } else {
            //     var url = '/sortanalytics?date=' + date + '&date2=' + date2 + '&name=' + name + '&product=' + product
            //     url = url.replace(/\(/g, "%28").replace(/\)/g, "%29").replace(/\+/g, '%2B'); 
            //     window.open(url,
            //         "_self");
            // }

        }
        $(document).ready(function() {
            $.ajax({
                type: 'get',
                url: '/user/finditem',
                success: function(response) {

                    var custarray = response;
                    var datacust = {};
                    for (var i = 0; i < custarray.length; i++) {

                        datacust[custarray[i].name] = null;
                    }
                    // console.log(datacust2)
                    $('input#product').autocomplete({
                        data: datacust,
                    });
                }
            })
        })
    </script>
    <script>
        const searchFun = () => {
            var filter = $('#search').val().toLowerCase();
            const a = document.getElementById('search');
            const clsBtn = document.getElementById('close-search');
            let table = document.getElementsByTagName('table');
            let tr = $('tr')
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

            for (var i = 0; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                // console.log(td);
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) {
                        let textvalue = td[j].textContent || td[j].innerHTML;
                        if (textvalue.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        } else {
                            tr[i].style.display = "none"
                        }
                    }
                }
            }
        }
    </script>
@endsection
