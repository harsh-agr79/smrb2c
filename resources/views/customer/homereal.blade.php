@extends('layouts/customer')

@section('main')
    <style>
        @media screen and (max-width: 720px) {
            .prod-container {
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

        .home-btn {
            /* width: 200px !important; */
            background: #00c853;
            color: black;
            border-radius: 10px;
            padding: 15px;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .home-btn i {
            margin-left: 3vw;
            color: black !important;
        }

        .home-btn:hover {
            background: #00e676;
        }

        .spc {
            transform: scale(1.04);
        }

        .mp-caro-item {
            height: 30vh;
            width: 100%;
        }

        .scroll-text {
            display: flex;
            flex-wrap: nowrap;
            white-space: nowrap;
            min-width: 100%;
            overflow: hidden;
        }

        .news-message {
            display: flex;
            flex-shrink: 0;
            height: 30px;
            align-items: center;
            animation: slide-left 15s linear infinite;
        }

        .news-message p {
            font-size: 1.5em;
            font-weight: 600;
            padding-left: 1em;
            color: var(--textcol);
        }

        @keyframes slide-left {
            from {
                -webkit-transform: translateX(0);
                transform: translateX(0);
            }

            to {
                -webkit-transform: translateX(-100%);
                transform: translateX(-100%);
            }
        }

        .bal-popup {
            position: fixed;
            z-index: 1005;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.649);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bal-popcard {
            width: 90vw;
        }

        .overlay {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            /* background: #000000; */
            opacity: .6;
            filter: grayscale(100%);
            z-index: 0;
            overflow: hidden;
        }
    </style>

    <div class="row" style="padding: 0; margin: 0;">
        <div class="col l6 m12 s12" style="padding: 0; margin: 0;">
            <div class="mp-caro-cont">
                @for ($i = 0; $i < count($data); $i++)
                    <div class="mp-caro-item valign-wrapper @if ($i != 0) hide @endif"
                        style="background: url('{{ asset($data[$i]->image) }}'); background-size: cover; background-position: center; background-repeat: no-repeat; ">
                        <div style="width: 100vw;">
                            <div class="btn-floating left"
                                style="margin: 5px; background: rgba(0, 0, 0, 0.219); border-radius: 50%" onclick="prev()">
                                <i class="material-icons white-text center">arrow_back</i>
                            </div>
                            <div class="btn-floating right"
                                style="margin: 5px; background: rgba(0, 0, 0, 0.219); border-radius: 50%" onclick="next()">
                                <i class="material-icons white-text center">arrow_forward</i>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            <div class="scroll-text">
                <section class="news-message bg-content">
                    @foreach ($data2 as $item)
                        <p>{{ $item->message }}</p>
                    @endforeach
                </section>
                <section class="news-message bg-content">
                    @foreach ($data2 as $item)
                        <p>{{ $item->message }}</p>
                    @endforeach
                </section>
            </div>
        </div>
        <div class="col l6 m12 s12 row center" style="margin-top: 5px;">
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/createorder') }}" class="home-btn spc">Create A New Order<i
                        class="material-icons">add</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/oldorders') }}" class="home-btn">Old Orders/Bills<i
                        class="material-icons">shopping_basket</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/savedorders') }}" class="home-btn">Saved Baskets<i class="material-icons">save</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/mainanalytics') }}" class="home-btn">Analytics<i
                        class="material-icons">equalizer</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/summary') }}" class="home-btn">Summary <i
                        class="material-icons">multiline_chart</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/statement') }}" class="home-btn">Statement <i class="material-icons">web</i></a>
            </div>
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
    </div>
@endsection

@if (time() - session()->get('USER_TIME') < 20)
    <div class="bal-popup hide-on-large-only" id="balpop" onclick="closefunc()">
        @php
            $bal = explode('|', $user->balance);
        @endphp
        <div class="center mp-card bal-popcard">
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
@endif

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
    function closefunc() {
        $('#balpop').remove();
    }
</script>
