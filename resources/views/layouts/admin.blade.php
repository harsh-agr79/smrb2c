<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="description" content="">
    <meta name="theme-color" content="#00c200" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <title>Admin</title>
    <link rel="icon" href="{{ asset('smrlogo.png') }}">
    <link rel="stylesheet" href="{{ asset('/assets/style.css') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/icons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/icons/favicon-16x16.png') }}">
    <link rel="mask-icon" href="{{ asset('/icons/safari-pinned-tab.svg') }}" color="#5bbad5">
    <link href="//cdn.shopify.com/s/files/1/1775/8583/t/1/assets/admin-materialize.min.css?v=8850535670742419153"
        rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Exo' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body class="has-fixed-sidenav">
    <style>
        .modal-cont {
            padding: 10px 0px;
        }

        .mod-btn {
            padding: 5px 15px;
            width: 100%;
            font-size: 20px;
            cursor: pointer;
        }

        .mod-btn i {
            font-size: 30px;
        }

        .modal-top-des {
            height: 5px;
            border-radius: 5px;
            background: gray;
        }

        .mod-btn span {
            padding: 10px;
        }

        .bottom-sheet {
            padding: 5px;
            border-radius: 20px 20px 0px 0px !important;
        }
    </style>
    <header>
        <div class="navbar-fixed">
            <nav class="navbar white">
                <div class="nav-wrapper">
                    <a href="{{ url('/') }}" class="brand-logo grey-text text-darken-4"
                        style="padding-top: 10px;"><img src="{{ asset('logo/logo2.png') }}" style="height: 40px;"
                            alt=""></a>
                    <ul id="nav-mobile" class="right">
                        <li class="hide-on-med-and-down">{{ getNepaliDate(date('Y-m-d')) }}</li>
                        <li class="hide-on-med-and-down"><a href="#!" data-target="dropdown1"
                                class="dropdown-trigger "><i class="material-icons">notifications</i></a>
                            <div id="dropdown1" class="dropdown-content notifications" tabindex="0">
                                <div class="notifications-title" tabindex="0">notifications</div>
                                <div class="card" tabindex="0">
                                    <div class="card-content"><span class="card-title">Joe Smith made a
                                            purchase</span>
                                        <p>Content</p>
                                    </div>
                                    <div class="card-action"><a href="#!">view</a><a href="#!">dismiss</a>
                                    </div>
                                </div>
                                <div class="card" tabindex="0">
                                    <div class="card-content"><span class="card-title">Daily Traffic Update</span>
                                        <p>Content</p>
                                    </div>
                                    <div class="card-action"><a href="#!">view</a><a href="#!">dismiss</a>
                                    </div>
                                </div>
                                <div class="card" tabindex="0">
                                    <div class="card-content"><span class="card-title">New User Joined</span>
                                        <p>Content</p>
                                    </div>
                                    <div class="card-action"><a href="#!">view</a><a href="#!">dismiss</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><a onclick=" window.history.back();"><i class="material-icons">arrow_back</i></a>
                        <li><a href="#!" data-target="settings-dropdown" class="dropdown-trigger "><i
                                    class="material-icons">settings</i></a>

                            <ul id='settings-dropdown' class='dropdown-content center'>
                                <li class="center"><a class="center" href="{{ url('/admin/profile') }}">Profile</a>
                                </li>
                                <li class="center"><a class="center" href="{{ url('/logout') }}">Logout</a></li>
                            </ul>
                        </li>
                    </ul><a href="#!" data-target="sidenav-left" class="sidenav-trigger left"><i
                            class="material-icons black-text">menu</i></a>
                </div>
            </nav>
        </div>
        <ul id="sidenav-left" class="sidenav sidenav-fixed white" style="transform: translateX(0%);">
            <li><a href="{{ url('/') }}" class="logo-container">{{ $admin->name }}<i
                        class="material-icons left">spa</i></a></li>
            <li class="no-padding">
                <ul class="collapsible collapsible-accordion">
                    <li><a href="{{ url('dashboard') }}" class=" active">Dashboard<i
                                class="material-icons">web</i></a>
                    </li>
                    @if (
                        $admin->type != 'staff' ||
                            in_array('orders', $perms) ||
                            in_array('pendingorders', $perms) ||
                            in_array('deliveredorders', $perms) ||
                            in_array('rejectedorders', $perms) ||
                            in_array('approvedorders', $perms) ||
                            in_array('chalan', $perms) ||
                            in_array('createorder', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Orders<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type != 'staff' || in_array('orders', $perms))
                                        <li><a href="{{ url('/orders') }}" class="textcol">View Orders<i
                                                    class="material-icons textcol">visibility</i></a></li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('approvedorders', $perms))
                                        <li class="amber darken-1"><a href="{{ url('/approvedorders') }}"
                                                class="textcol">Approved Orders<i
                                                    class="material-icons textcol">check</i></a></li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('pendingorders', $perms))
                                        <li class="blue"><a href="{{ url('/pendingorders') }}"
                                                class="textcol">Pending
                                                Orders<i class="material-icons textcol">warning</i></a></li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('rejectedorders', $perms))
                                        <li class="red"><a href="{{ url('/rejectedorders') }}"
                                                class="textcol">Rejected Orders<i
                                                    class="material-icons textcol">clear</i></a></li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('deliveredorders', $perms))
                                        <li class="green"><a href="{{ url('/deliveredorders') }}"
                                                class="textcol">Delivered Orders<i
                                                    class="material-icons textcol">local_shipping</i></a></li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('chalan', $perms))
                                        <li class="deep-purple"><a href="{{ url('/chalan') }}"
                                                class="textcol">Chalan<i class="material-icons textcol">check</i></a>
                                        </li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('bulkprint', $perms))
                                        <li class="cyan ligthen-3"><a href="{{ url('/bulkprintorders') }}"
                                                class="black-text">Bulk Print<i
                                                    class="material-icons black-text">print</i></a>
                                        </li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('addorder', $perms))
                                        <li class="amber lighten-4"><a href="{{ url('/addorder') }}"
                                                class="black-text">Create Order<i
                                                    class="material-icons black-text">add</i></a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    {{-- @if ($admin->type != 'staff' || in_array('statements', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Statements<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>

                                    <li><a href="{{ url('/statements') }}" class="textcol">Statements<i
                                                class="material-icons textcol">account_balance</i></a></li>
                                </ul>
                            </div>
                        </li>
                    @endif --}}
                    {{-- @if ($admin->type != 'staff' || in_array('mainanalytics', $perms) || in_array('sortanalytics', $perms) || in_array('summary', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Analytics<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type != 'staff' || in_array('mainanalytics', $perms))
                                        <li><a href="{{ url('/mainanalytics') }}" class="textcol">Main Analytics<i
                                                    class="material-icons textcol">pie_chart</i></a></li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('sortanalytics', $perms))
                                        <li><a href="{{ url('/sortanalytics') }}" class="textcol">Sort Analytics<i
                                                    class="material-icons textcol">multiline_chart</i></a></li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('summary', $perms))
                                        <li><a href="{{ url('/summary') }}" class="textcol">Detailed Report<i
                                                    class="material-icons textcol">show_chart</i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif --}}
                    @if ($admin->type != 'staff')
                        <li class="bold  green"><a class="collapsible-header" tabindex="0">Company<i
                                    class="material-icons chevron">chevron_left</i></a>
                            <div class="collapsible-body" style="">
                                <ul>
                                    @if (session()->get('ADMIN_TYPE') == 'superuser')
                                        <li><a href="{{ url('admins') }}" class=" active">Admins<i
                                                    class="material-symbols-outlined">
                                                    shield_person
                                                </i></a></li>
                                    @endif
                                    @if ($admin->type != 'staff')
                                        {{-- <li><a href="{{ url('marketer') }}" class=" active">Marketer<i
                                                    class="material-symbols-outlined">
                                                    person
                                                </i></a></li>
                                        <li><a href="{{ url('addmarketer') }}" class=" active"> Add Marketer<i
                                                    class="material-symbols-outlined">
                                                    person_add
                                                </i></a></li> --}}
                                        <li><a href="{{ url('staff') }}" class=" active">Staffs<i
                                                    class="material-symbols-outlined">
                                                    person
                                                </i></a></li>
                                        <li><a href="{{ url('addstaff') }}" class=" active"> Add Staffs<i
                                                    class="material-symbols-outlined">
                                                    person_add
                                                </i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if ($admin->type != 'staff' || in_array('customers', $perms) || in_array('customers/add', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Customers<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type != 'staff' || in_array('customers', $perms))
                                        <li><a href="{{ url('/customers') }}" class="textcol">View Customers<i
                                                    class="material-icons textcol">people</i></a></li>
                                    @endif
                                    {{-- @if ($admin->type != 'staff' || in_array('customers/add', $perms))
                                        <li><a href="{{ url('/customers/add') }}" class="textcol">Add Customer<i
                                                    class="material-icons textcol">person_add</i></a></li>
                                    @endif --}}
                                </ul>
                            </div>
                        </li>
                    @endif
                    {{-- @if ($admin->type != 'staff' || in_array('payments', $perms) || in_array('addpayments', $perms))
                        <li class="bold"><a class="collapsible-header textcol"
                                tabindex="0">Payments/Salesreturn<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type != 'staff' || in_array('payments', $perms))
                                        <li><a href="{{ url('/payments') }}" class="textcol">View
                                                Payments/Salesreturn<i
                                                    class="material-icons textcol">attach_money</i></a></li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('addpayment', $perms))
                                        <li><a href="{{ url('/addpayment') }}" class="textcol">Add
                                                Payment/Salesreturn<i class="material-icons textcol">add</i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif --}}
                    {{-- @if ($admin->type != 'staff' || in_array('slr', $perms) || in_array('createslr', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Sales Return<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type != 'staff' || in_array('slr', $perms))
                                        <li><a href="{{ url('/slr') }}" class="textcol">View Sales Return<i
                                                    class="material-icons textcol">autorenew</i></a></li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('createslr', $perms))
                                        <li><a href="{{ url('/createslr') }}" class="textcol">Add Sales Return<i
                                                    class="material-icons textcol">add</i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif --}}
                    {{-- @if ($admin->type != 'staff' || in_array('expenses', $perms) || in_array('addexpense', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Expenses<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type != 'staff' || in_array('expenses', $perms))
                                        <li><a href="{{ url('/expenses') }}" class="textcol">View Expenses<i
                                                    class="material-icons textcol">credit_card</i></a></li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('addexpense', $perms))
                                        <li><a href="{{ url('/addexpense') }}" class="textcol">Add Expense<i
                                                    class="material-icons textcol">add</i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif --}}
                    @if (
                        $admin->type != 'staff' ||
                            in_array('brands', $perms) ||
                            in_array('category', $perms) ||
                            in_array('products', $perms) ||
                            in_array('products/add', $perms))
                        <li class="bold "><a class="collapsible-header" tabindex="0">Inventory<i
                                    class="material-icons chevron">chevron_left</i></a>
                            <div class="collapsible-body" style="">
                                <ul>
                                    @if ($admin->type != 'staff' || in_array('brands', $perms))
                                        <li><a href="{{ url('brands') }}" class=" active">Brands<i
                                                    class="material-symbols-outlined">store</i></a></li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('category', $perms))
                                        <li><a href="{{ url('category') }}" class=" active">Categories<i
                                                    class="material-symbols-outlined">category</i></a></li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('products', $perms))
                                        <li><a href="{{ url('products') }}" class=" active">Products<i
                                                    class="material-symbols-outlined">inventory</i></a></li>
                                    @endif
                                    @if ($admin->type != 'staff' || in_array('products/add', $perms))
                                        <li><a href="{{ url('products/add') }}" class=" active">Add Product<i
                                                    class="material-symbols-outlined">add_circle</i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if ($admin->type != 'staff' || in_array('frontsettings', $perms))
                        <li class="bold"><a href="{{ url('/frontsettings') }}" class="textcol">Front Settings<i
                                    class="material-icons textcol">settings</i></a></li>
                        <li class="bold"><a href="{{ url('/terms/edit') }}" class="textcol">Terms and conditions<i
                                    class="material-icons textcol">settings</i></a></li>
                        <li class="bold"><a href="{{ url('/policy/edit') }}" class="textcol">Privacy Policy<i
                                    class="material-icons textcol">settings</i></a></li>
                        {{-- <li class="bold"><a href="{{ url('/trash') }}" class="textcol">Recycle Bin<i
                                    class="material-icons textcol">delete</i></a></li> --}}
                    @endif
                </ul>
            </li>
        </ul>



    </header>

    <main>
        @yield('main')
    </main>
    <div id="menumodal" class="modal bottom-sheet iphone">
        <div class="modal-cont">
            <div class="row">
                <div class="col s5"></div>
                <div class="col s2 modal-top-des"></div>
                <div class="col s5"></div>
            </div>
            <a id="menu-editlink">
                <div class="mod-btn row">
                    <div class="col s1">
                        <i class="material-icons black-text">edit</i>
                    </div>
                    <div class="col s11">
                        <span class="black-text">Edit</span>
                    </div>
                </div>
            </a>
            <a id="menu-dellink">
                <div class="mod-btn row">
                    <div class="col s1">
                        <i class="material-icons red-text">delete</i>
                    </div>
                    <div class="col s11">
                        <span class="red-text">
                            Delete
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div id="flash" class="popup section bgunder"
        style="margin-bottom: -2em; display: block; height: 214px; transform: translateY(0px);">
        <div class="container pWrapper">
            <div class="row">
                <div class="col s12 m8 offset-m2">
                    <div class="card hoverable">
                        <div class="card-content flow-text">
                            {{-- <i class="close material-icons right" onclick="closeThis()" style="cursor: pointer;">close</i> --}}
                            <p id="install-message">
                                You can install this app for easy access.
                                <button id="install" class="btn green accent-4 black-text"
                                    style="margin: .5em auto auto auto; display: block;">Install APP</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // document.addEventListener('contextmenu', event => event.preventDefault());

        let deferredPrompt;
        const addBtn = document.querySelector('#install');
        const card = document.querySelector('#flash');
        addBtn.style.display = 'none';
        card.style.display = 'none';

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI to notify the user they can add to home screen
            addBtn.style.display = 'block';
            card.style.display = 'block';

            addBtn.addEventListener('click', (e) => {
                // hide our user interface that shows our A2HS button
                addBtn.style.display = 'none';
                card.style.display = 'none';
                // Show the prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the A2HS prompt');
                    } else {
                        console.log('User dismissed the A2HS prompt');
                    }
                    deferredPrompt = null;
                });
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="{{ asset('/assets/script.js') }}"></script>
    <script src="{{ asset('/assets/select.js') }}"></script>
    <script src="{{ asset('/assets/sorttable.js') }}"></script>
    {{-- <script src="{{ asset('/assets/sorttable.js') }}"></script> --}}
    <script src="https://cdn.socket.io/4.4.0/socket.io.min.js"
        integrity="sha384-1fOn6VtTq3PWwfsOrk45LnYcGosJwzMHv+Xh/Jx5303FVOXzEnw0EpLv30mtjmlj" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="{{ asset('/assets/script.js') }}"></script>
    <script src="{{ asset('/assets/select.js') }}"></script>
    <script src="{{ asset('/assets/sorttable.js') }}"></script>
    <script>
        $(document).ready(function() {
            p = navigator.platform;
            if (p == 'iPhone' || p == 'iPod' || p == 'iPad') {
                $('.iphone').css('display', 'block');
                // $('#rightmenu').remove();
                // $('.iphone').remove();
            } else {
                $('.iphone').remove();
            }
        });

        function changelink(edlink, dellink) {
            $('#menu-editlink').attr('href', edlink);
            $('#menu-dellink').attr('href', dellink);
            $('#menu-editlink').attr('onclick', '');
            $('#menu-dellink').attr('onclick', '');
        }

        function changelinkajax(edlink, dellink) {
            $('#menu-editlink').attr('onclick', edlink);
            $('#menu-dellink').attr('onclick', dellink);
            $('#menu-editlink').attr('href', "");
            $('#menu-dellink').attr('href', "");
        }
    </script>

    <script src="https://cdn.socket.io/4.4.0/socket.io.min.js"
        integrity="sha384-1fOn6VtTq3PWwfsOrk45LnYcGosJwzMHv+Xh/Jx5303FVOXzEnw0EpLv30mtjmlj" crossorigin="anonymous">
    </script>
</body>

</html>
