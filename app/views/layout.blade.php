<html>
<head>
    <title>Laravel Authentication Demo</title>
    {{ HTML::style('/css/style.css') }}
</head>


    <body>
        
        
<div id="container">
        <div id="nav">
            <ul>
                <li>{{ HTML::link('account', 'My Account') }}</li>
                @if(Auth::check())
                    <li>{{ HTML::link('profile', 'Profile' ) }}</li>
                    <li>{{ HTML::link('logout', 'Logout') }}</li>
                @else
                    <li>{{ HTML::link('login', 'Login') }}</li>
                    <li>{{ HTML::link('register', 'Register') }}</li>
                @endif
            </ul>
        </div><!-- end nav -->

        <!-- check for flash notification message -->
  		@if(Session::has('flash_notice'))
            <div id="flash_notice">{{ Session::get('flash_notice') }}</div>
        @endif

        @yield('content')
    </div><!-- end container -->
    </body>
</html>