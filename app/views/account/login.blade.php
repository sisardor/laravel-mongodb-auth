@extends('layout')

@section('content')
<h1>Login</h1>
<!-- check for login error flash var -->
    @if (Session::has('flash_error'))
        <div id="flash_error">{{ Session::get('flash_error') }}</div>
    @endif

{{ Form::open(array('url' => 'login', 'method' => 'post' )) }}
<p>
 <?php echo Form::label('email', 'E-Mail Address');  ?>
 <?php echo Form::text('email'); ?>
</p>
<p>
 <?php echo Form::label('password', 'Password'); ?>
 <?php echo Form::password('password'); ?>
</p>
<p> <?php echo Form::submit('Login'); ?> </p>
{{ Form::close() }}

@stop

