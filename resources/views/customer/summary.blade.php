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
    <div class="mp-container">


        @php
            $bal = explode('|', $user->balance);
        @endphp
        <style>
            label {
                font-size: 8px;
            }
        </style>
        <div class="center">
            <h5>Detailed Summary</h5>
        </div>
        <div class="green accent-4 center" style="padding: 5px; margin-top: 10px; border-radius: 10px;">
            <h5 class="black-text" style="font-weight: 600;">Balance -@if ($bal[0] == 'red')
                    To Pay:
                @else
                    To Recieve:
                @endif
                {{ money($bal[1]) }}</h5>
        </div>
        <div class="col l6 m12 s12 center hide-on-med-and-down" id="balpop-pc" onclick="closefunc()">
            @php
                $bal = explode('|', $user->balance);
            @endphp
            <div class="center mp-card">
                <div class="center green accent-4 white-text" style="border-radius: 10px; padding: 10px;">
                    @if ($bal[0] == 'red')
                        <h5>Amount To Pay: {{ money($bal[1]) }}</h5>
                    @else
                        <h5>Amount To Recieve: {{ money($bal[1]) }}</h5>
                    @endif
                </div>
                <div>
                    <table>
                        <thead>
                            <th>Outstanding Amount In Days</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight: 700;">Total Balance @if ($bal[0] == 'red')
                                    To Recieve:</h5>
                                @else
                                    To Pay:</h5>
                                @endif
                                </td>
                                <td style="font-weight: 700;">{{ money($bal[1]) }}</td>
                            </tr>
                           
                                @if (count($thirdays) > 0)
                                    @if ($bal[0] == 'red' && $bal[1] > 0)
                                        <tr>
                                            <td>15 days</td>
                                            @if ($bal[1] - $thirdays[0]->sl > 0)
                                            <td>{{ money($bal[1] - $thirdays[0]->sl) }}</td>
                                            @else
                                            <td>0</td>
                                            @endif
                                        </tr>
                                    @else
                                        <tr>
                                            <td>15 days</td>
                                            <td>0</td>
                                        </tr>
                                    @endif
                                @else
                                    @if ($bal[0] == 'red'  && $bal[1] > 0)
                                        <tr>
                                            <td>15 days</td>
                                            <td>{{ money($bal[1]) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>15 days</td>
                                            <td>0</td>
                                        </tr>
                                    @endif
                                @endif
                                @if (count($fourdays) > 0)
                                    @if ($bal[0] == 'red' && $bal[1] > 1)
                                        <tr>
                                            <td>25 days</td>
                                            @if ($bal[1] - $fourdays[0]->sl > 0)
                                            <td>{{ money($bal[1] - $fourdays[0]->sl) }}</td>
                                            @else
                                            <td>0</td>
                                            @endif
                                        </tr>
                                    @else
                                        <tr>
                                            <td>25 days</td>
                                            <td>0</td>
                                        </tr>
                                    @endif
                                @else
                                    @if ($bal[0] == 'red'  && $bal[1] > 0)
                                        <tr>
                                            <td>25 days</td>
                                            <td>{{ money($bal[1]) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>25 days</td>
                                            <td>0</td>
                                        </tr>
                                    @endif
                                @endif
                                @if (count($sixdays) > 0)
                                    @if ($bal[0] == 'red')
                                        <tr>
                                            <td>35 days</td>
                                            @if ($bal[1] - $sixdays[0]->sl > 0)
                                            <td>{{ money($bal[1] - $sixdays[0]->sl) }}</td>
                                            @else
                                            <td>0</td>
                                            @endif
                                        </tr>
                                    @else
                                        <tr>
                                            <td>35 days</td>
                                            <td>0</td>
                                        </tr>
                                    @endif
                                @else
                                    @if ($bal[0] == 'red')
                                        <tr>
                                            <td>35 days</td>
                                            <td>{{ money($bal[1]) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>35 days</td>
                                            <td>0</td>
                                        </tr>
                                    @endif
                                @endif
                                @if (count($nindays) > 0)
                                    @if ($bal[0] == 'red')
                                        <tr>
                                            <td>45 days</td>
                                            @if ($bal[1] - $nindays[0]->sl > 0)
                                            <td>{{ money($bal[1] - $nindays[0]->sl) }}</td>
                                            @else
                                            <td>0</td>
                                            @endif
                                        </tr>
                                    @else
                                        <tr>
                                            <td>45 days</td>
                                            <td>0</td>
                                        </tr>
                                    @endif
                                @else
                                    @if ($bal[0] == 'red')
                                        <tr>
                                            <td>45 days</td>
                                            <td>{{ money($bal[1]) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>45 days</td>
                                            <td>0</td>
                                        </tr>
                                    @endif
                                @endif
                            
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div>


        </div>
        <div class="mp-card" style="margin-top: 10px;">
            <form class="row" style="padding: 0; margin: 0;">
                <div class="col s2" style="padding: 0; margin: 0;">
                    <label>Start Month:</label>
                    <select name="startmonth" class="browser-default selectinp">
                        <option value="">Select Start Month</option>
                        <option value="{{ $smonth }}" selected>{{ $smonth }}</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="8">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                </div>
                <div class="col s4" style="padding: 0; margin: 0;">
                    <label>Start Year:</label>
                    <select name="startyear" class="browser-default selectinp">
                        <option value="">Select Start Year</option>
                        <option value="{{ $syear }}" selected>{{ $syear }}</option>
                        <option value="2078">2078</option>
                        <option value="2079">2079</option>
                        <option value="2080">2080</option>
                        <option value="2081">2081</option>
                    </select>
                </div>
                <div class="col s2" style="padding: 0; margin: 0;">
                    <label>End Month:</label>
                    <select name="endmonth" value="{{ $emonth }}" class="browser-default selectinp">
                        <option value="">Select End Month</option>
                        <option value="{{ $emonth }}" selected>{{ $emonth }}</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="8">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                </div>
                <div class="col s4" style="padding: 0; margin: 0;">
                    <label>End Year:</label>
                    <select name="endyear" value="{{ $eyear }}" class="browser-default selectinp">
                        <option value="">Select End Year</option>
                        <option value="{{ $eyear }}" selected>{{ $eyear }}</option>
                        <option value="2078">2078</option>
                        <option value="2079">2079</option>
                        <option value="2080">2080</option>
                        <option value="2081">2081</option>
                    </select>
                </div>
                <div class="input-field col l1">
                    <button class="btn green accent-4">Apply</button>
                </div>
                <div class="input-field col l1">
                    <a class="btn green accent-4" href="{{ url('user/summary') }}">Clear</a>
                </div>
            </form>
        </div>

        <div class="mp-card" style="margin-top: 10px;">
            @php
                $numbills = 0;
                $totalsales = 0;
                $nummonths = 0;
                $months = [
                    'first',
                    'Baisakh',
                    'Jeth',
                    'Asar',
                    'Shrawan',
                    'Bhadra',
                    'Asoj',
                    'Kartik',
                    'Mangsir',
                    'Poush',
                    'Magh',
                    'Falgun',
                    'Chaitra',
                ];
                $forchart = [];
            @endphp

            <ul class="collapsible">
                <div>
                    <h6 class="center">Monthly Sales</h6>
                </div>
                <div class="container">
                    <hr style="background-color: rgb(211, 211, 211); border: none; height: 1px;">
                </div>
                <div class="row">
                    <div class="col s6" style="font-weight: 700;">Month-Year</div>
                    <div class="col s6" style="font-weight: 700;">Sales</div>
                </div>
                @foreach ($dtas as $item)
                    @if ($item->nepyear > $syear && $item->nepyear < $eyear)
                        @php
                            $forchart[] = [
                                'date' => $item->nepmonth . '-' . $item->nepyear,
                                'amount' => (int)$item->sl,
                            ];
                        @endphp
                        <li>
                            <div class="collapsible-header row"
                                onclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}')">
                                <span
                                    class="left col s6 blue-text">{{ $months[$item->nepmonth] }}-{{ $item->nepyear }}</span><span
                                    class="right col s6">{{ money($item->sl) }}</span>
                            </div>

                            @php
                                $totalsales = $totalsales + ($item->sl);
                                $nummonths = $nummonths + 1;
                                $numbills =
                                    $numbills +
                                    DB::table('orders')
                                        ->where('name', $user->name)
                                        ->where([
                                            'deleted_at' => null,
                                            'status' => 'approved',
                                            'save' => null,
                                            'nepmonth' => $item->nepmonth,
                                            'nepyear' => $item->nepyear,
                                        ])
                                        ->groupBy('order_id')
                                        ->get()
                                        ->count();
                            @endphp
                        </li>
                    @elseif($syear == $eyear)
                        @if ($smonth <= $item->nepmonth && $emonth >= $item->nepmonth && $item->nepyear == $syear)
                            @php
                                $forchart[] = ['date' => $item->nepmonth . '-' . $item->nepyear, 'amount' => (int)$item->sl];
                            @endphp
                            <li>
                                <div class="collapsible-header row"
                                    onclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}')">
                                    <span
                                        class="left col s6 blue-text">{{ $months[$item->nepmonth] }}-{{ $item->nepyear }}</span><span
                                        class="right col s6">{{ money($item->sl) }}</span>
                                </div>

                                @php
                                    $totalsales = $totalsales + $item->sl;
                                    $nummonths = $nummonths + 1;
                                    $numbills =
                                        $numbills +
                                        DB::table('orders')
                                            ->where('name', $user->name)
                                            ->where([
                                                'deleted_at' => null,
                                                'status' => 'approved',
                                                'save' => null,
                                                'nepmonth' => $item->nepmonth,
                                                'nepyear' => $item->nepyear,
                                            ])
                                            ->groupBy('order_id')
                                            ->get()
                                            ->count();
                                @endphp
                            </li>
                        @endif
                    @elseif($item->nepyear == $syear)
                        @if ($smonth <= $item->nepmonth)
                            @php
                                $forchart[] = ['date' => $item->nepmonth . '-' . $item->nepyear, 'amount' => (int)$item->sl];
                            @endphp
                            <li>
                                <div class="collapsible-header row"
                                    onclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}')">
                                    <span
                                        class="left col s6 blue-text">{{ $months[$item->nepmonth] }}-{{ $item->nepyear }}</span><span
                                        class="right col s6">{{ money($item->sl) }}</span>
                                </div>

                                @php
                                    $totalsales = $totalsales + $item->sl;
                                    $nummonths = $nummonths + 1;
                                    $numbills =
                                        $numbills +
                                        DB::table('orders')
                                            ->where('name', $user->name)
                                            ->where([
                                                'deleted_at' => null,
                                                'status' => 'approved',
                                                'save' => null,
                                                'nepmonth' => $item->nepmonth,
                                                'nepyear' => $item->nepyear,
                                            ])
                                            ->groupBy('order_id')
                                            ->get()
                                            ->count();
                                @endphp
                            </li>
                        @endif
                    @elseif($item->nepyear == $eyear)
                        @if ($emonth >= $item->nepmonth)
                            @php
                                $forchart[] = ['date' => $item->nepmonth . '-' . $item->nepyear, 'amount' => (int)$item->sl];
                            @endphp
                            <li>
                                <div class="collapsible-header row"
                                    onclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}')">
                                    <span
                                        class="left col s6 blue-text">{{ $months[$item->nepmonth] }}-{{ $item->nepyear }}</span><span
                                        class="right col s6">{{ money($item->sl) }}</span>
                                </div>

                                @php
                                    $totalsales = $totalsales + $item->sl;
                                    $nummonths = $nummonths + 1;
                                    $numbills =
                                        $numbills +
                                        DB::table('orders')
                                            ->where('name', $user->name)
                                            ->where([
                                                'deleted_at' => null,
                                                'status' => 'approved',
                                                'save' => null,
                                                'nepmonth' => $item->nepmonth,
                                                'nepyear' => $item->nepyear,
                                            ])
                                            ->groupBy('order_id')
                                            ->get()
                                            ->count();
                                @endphp
                            </li>
                        @endif
                    @endif
                @endforeach
            </ul>
        </div>

        <div class="mp-card" style="margin-top: 10px;">
            <ul class="collapsible">
                @foreach ($catsales as $item)
                    @php
                        $amtchart[] = ['Category' => $item->brand, 'Amount' => (int)$item->samt];
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
                                        $subcates = DB::table('categories')->pluck('category')->toArray();
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
                                            <tr class="{{ $item->brand }} {{ $item2->category }}"
                                                ondblclick="openanadetail('{{ $date }}', '{{ $date2 }}', '{{ $item2->item }}')">
                                                <td>{{ $item2->item }}</td>
                                                <td>{{ $item2->sum }}</td>
                                                <td>{{ money($item2->samt) }}</td>
                                            </tr>
                                        @endforeach
                                        @foreach ($data2[$item->brand] as $item2)
                                            <tr class="{{ $item->brand }} {{ $item2->category }}">
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

        @if ($numbills > 0)
            <div style="margin-top: 10px;">
                <div class="mp-card">
                    <div>
                        <h6 class="center">Average Purchase Report</h6>
                    </div>
                    <div class="container">
                        <hr style="background-color: rgb(211, 211, 211); border: none; height: 1px;">
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Particular</th>
                                <th>Purchase</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>Total Purchase</td>
                            <td>{{ money($totalsales) }}</td>
                        </tr>
                        <tr>
                            <td>Average Purchase Per Bill </td>
                            <td>{{ money($totalsales / $numbills) }}</td>
                        </tr>
                        <tr>
                            <td>Average Purchase Per Month</td>
                            <td>{{ money($totalsales / $nummonths) }}</td>
                        </tr>
                        <tr>
                            <td>Average Purchase Per Day</td>
                            <td>{{ money($totalsales / getNepaliDays($smonth, date('y', strtotime($syear)), $emonth, date('y', strtotime($eyear)), getNepaliDay(today()), getNepaliMonth(today()), date('y', strtotime(getNepaliYear(today()))))) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Bill Count</td>
                            <td>{{ $numbills }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @else
            <div>
                <h5 class="center">
                    No Sales Data for Given Date Range
                </h5>
            </div>
        @endif
        <div style="margin-top: 10px;">
            <div class="mp-card">
                <div>
                    <h6 class="center">Quaterly Purchase</h6>
                </div>
                <div class="container">
                    <hr style="background-color: rgb(211, 211, 211); border: none; height: 1px;">
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Quater</th>
                            <th>Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $quatdata = [];

                            foreach ($fquat as $item) {
                                $quatdata[] = [
                                    'id' => $item->date,
                                    'year' => $item->nepyear,
                                    'quat' => 'First Quater -' . $item->nepyear,
                                    'amount' => $item->sl,
                                ];
                            }
                            foreach ($squat as $item) {
                                $quatdata[] = [
                                    'id' => $item->date,
                                    'year' => $item->nepyear,
                                    'quat' => 'Second Quater -' . $item->nepyear,
                                    'amount' => $item->sl,
                                ];
                            }
                            foreach ($tquat as $item) {
                                $quatdata[] = [
                                    'id' => $item->date,
                                    'year' => $item->nepyear,
                                    'quat' => 'Third Quater -' . $item->nepyear,
                                    'amount' => $item->sl,
                                ];
                            }
                            foreach ($frquat as $item) {
                                $quatdata[] = [
                                    'id' => $item->date,
                                    'year' => $item->nepyear,
                                    'quat' => 'Fourth Quater -' . $item->nepyear,
                                    'amount' => $item->sl,
                                ];
                            }
                            usort($quatdata, function ($a, $b) {
                                return strtotime($a['id']) - strtotime($b['id']);
                            });

                            $qdata = collect($quatdata);
                            $forqdata = [];
                        @endphp
                        @for ($i = 0; $i < count($qdata); $i++)
                            @if ($qdata[$i]['year'] >= $syear && $qdata[$i]['year'] <= $eyear)
                                @php
                                    $forqdata[] = ['quater' => $qdata[$i]['quat'], 'sales' => (int)$qdata[$i]['amount']];
                                @endphp
                                <tr>
                                    <td>{{ $qdata[$i]['quat'] }}</td>
                                    <td>{{ money($qdata[$i]['amount']) }}</td>
                                </tr>
                            @endif
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mp-card container" style="margin-top: 10px;">
            <div class="bar" id="top_x_div" style="width: auto; height: 500px;"></div>
        </div>
        <div class="mp-card container" style="margin-top: 10px;">
            <div class="bar" id="top_x_div2" style="width: auto; height: 500px;"></div>
        </div>

        <script>
            // function openanadetail(date, date2, name) {
            //     window.open('/user/analytics?date=' + date + '&date2=' + date2 + '&name=' + name,
            //         "_self");
            // }
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                // console.log("hello")
                google.charts.load('current', {
                    'packages': ['bar']
                });
                google.charts.setOnLoadCallback(drawStuff);
                google.charts.setOnLoadCallback(drawStufftwo);
            })


            function drawStuff() {
                var chartdata = @json($forchart);
                const mta = chartdata.map(d => Array.from(Object.values(d)))
                var data = new google.visualization.DataTable();
                data.addColumn('string', '');
                data.addColumn('number', '');
                data.addRows(mta);

                var options = {
                    title: 'Monthly Sales',
                    backgroundColor: {
                        fill: 'transparent'
                    },
                    legend: {
                        position: 'none'
                    },
                    chart: {
                        title: 'Monthly Sales',
                        subtitle: 'By Amount'
                    },
                    bars: 'vertical',
                    axes: {
                        x: {
                            0: {
                                side: 'bottom',
                                label: 'Months'
                            } // Top x-axis.
                        },
                        y: {
                            0: {
                                side: 'left',
                                label: 'Sales'
                            } // left y axis
                        }
                    },
                    bar: {
                        groupWidth: "80%"
                    }
                };

                var chart = new google.charts.Bar(document.getElementById('top_x_div'));
                chart.draw(data, options);
            };

            function drawStufftwo() {
                var chartdata = @json($forqdata);
                const mta = chartdata.map(d => Array.from(Object.values(d)))
                var data = new google.visualization.DataTable();
                data.addColumn('string', '');
                data.addColumn('number', '');

                data.addRows(mta);

                var options = {
                    title: 'Quaterly Sales',
                    backgroundColor: {
                        fill: 'transparent'
                    },
                    legend: {
                        position: 'none'
                    },
                    chart: {
                        title: 'Quaterly Sales',
                        subtitle: 'By Amount'
                    },
                    bars: 'vertical',
                    axes: {
                        x: {
                            0: {
                                side: 'bottom',
                                label: 'Quaters'
                            } // Top x-axis.
                        },
                        y: {
                            0: {
                                side: 'left',
                                label: 'Sales'
                            } // left y axis
                        }
                    },
                    bar: {
                        groupWidth: "80%"
                    }
                };

                var chart = new google.charts.Bar(document.getElementById('top_x_div2'));
                chart.draw(data, options);
            };
        </script>

        <script>
            function openanadetail(date, date2, product) {
                var type = ``;
                // console.log(type);
                //  if (type === 'marketer') {
                var url = '/user/mainanalytics?date=' + date + '&date2=' + date2
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
    </div>
@endsection
