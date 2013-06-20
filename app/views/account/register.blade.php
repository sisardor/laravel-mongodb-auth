@extends('layout')

@section('content')

<h1>Sign-up Form</h1>
<!-- check for login error flash var -->

    @if ( $errors->count() > 0 )
      <div id="flash_error">
      <p>The following errors have occurred:</p>

      <ul>
        @foreach( $errors->all() as $message )
          <li>{{ $message }}</li>
        @endforeach
      </ul>
  	</div>
    @endif

{{ Form::open(array('url' => 'register', 'method' => 'post' )) }}
<p>
 <?php echo Form::label('email', 'Email');  ?>
 <?php echo Form::text('email'); ?>
</p>
<p>
 <?php echo Form::label('password', 'Password'); ?>
 <?php echo Form::password('password'); ?>
</p>
<p>
 <?php echo Form::label('password_confirmation', 'Password Confirmation'); ?>
 <?php echo Form::password('password_confirmation'); ?>
</p>
<p> <?php echo Form::submit('Register'); ?> </p>
{{ Form::close() }}

@stop

