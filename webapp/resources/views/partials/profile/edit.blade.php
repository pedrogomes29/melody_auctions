<div id="alter-info">
    <br>
    <form  id="edituser" method="POST" action="{{ route('user.update', $user->username) }}">
        {{ csrf_field() }}
        @method('PUT')
        <label for="email">E-Mail Address</label>
        <input id="email" type="email" name="email" value="{{ $user->email }}" required>
        @if ($errors->has('email'))
        <span class="error">
            {{ $errors->first('email') }}
        </span>
        @endif

        <label for="firstname">First Name</label>
        <input id="firstname" type="text" name="firstname" value="{{ $user->firstname }}" required>
        @if ($errors->has('firstname'))
        <span class="error">
            {{ $errors->first('firstname') }}
        </span>
        @endif

        <label for="lastname">Last Name</label>
        <input id="lastname" type="text" name="lastname" value="{{ $user->lastname }}" required>
        @if ($errors->has('lastname'))
        <span class="error">
            {{ $errors->first('lastname') }}
        </span>
        @endif

        <label for="username">Username</label>
        <input id="username" type="text" name="username" value="{{ $user->username }}" required>
        @if ($errors->has('username'))
        <span class="error">
            {{ $errors->first('username') }}
        </span>
        @endif
        
        <label for="description">Description</label>
        <textarea id="description" name="description"> {{ $user->description }} </textarea>
        @if ($errors->has('description'))
        <span class="error">
            {{ $errors->first('description') }}
        </span>
        @endif

        <label for="contact">Contact</label>
        <input id="contact" type="text" name="contact" value="{{ $user->contact  }}">
        @if ($errors->has('contact'))
        <span class="error">
            {{ $errors->first('contact') }}
        </span>
        @endif
        <button type="submit" class="btn btn-primary">
            Edit
        </button>
    </form>

</div>