@extends('auth.layouts.default')

@section('content_left')
    <div>
        <h2 class="text-4xl font-bold text-white">{{ env('APP_NAME') }}</h2>

        <p class="max-w-xl mt-3 text-gray-300">{{ __('You need an account to use this website. Please login to your account or
            register a new one.') }}
        </p>
    </div>
@endsection

@section('title', 'Welcome to '.env('APP_NAME'))

@section('form_title')
    Sign in
@endsection

@section('form_subtitle')
    Sign in to access your account
@endsection

@section('content')
    <div class="mt-8">
        @include('layouts.parts.auth.notifications')
        <form method="POST" action="{{ route('auth.login') }}">
            {!! csrf() !!}
            <div>
                <label for="email"
                       class="block mb-2 text-sm text-gray-600 dark:text-gray-200 ">{{ __('Email Address') }}
                </label>
                <input
                        type="email"
                        name="email"
                        tabindex="1"
                        id="email"
                        value="{{ old('email')  }}"
                        placeholder="{{ __("example@example.com") }}"
                        autocomplete="username webauthn"
                        class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40"/>
            </div>

            <div class="mt-6">
                <div class="flex justify-between mb-2">
                    <label for="password"
                           class="text-sm text-gray-600 dark:text-gray-200">{{ __('Password') }}</label>
                </div>
                <input
                        type="password"
                        name="password"
                        id="password"
                        tabindex="2"
                        value="{{ old('password') }}"
                        placeholder="Your Password"
                        class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40"/>
            </div>


            <div class="mt-6">
                <input class="form-check-input" type="checkbox" name="remember"
                       id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label
                        class="form-check-label text-sm text-gray-600 dark:text-gray-200 justify-between mb-2"
                        for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>


                @passkey
                <div class="mt-6">
                    <h2 class="text-sm text-2xl font-bold text-center text-gray-700 text-white">Sign in with</h2>
                </div>
                <div class="mt-6 flex">
                    <button
                            class="w-1/2 mr-2 flex-none  text-sm tracking-wide text-white transition-colors rounded-xl duration-200 transform bg-blue-500 rounded-md hover:bg-blue-400 focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                        {{ __('Password') }}
                    </button>

                    <div class="flex-grow h-16">
                        <!-- This item will grow -->
                    </div>
                    <button id="passkey"
                            class="w-1/2 flex-none text-sm bg-blue-500 hover:bg-blue-600 text-gray-800 px-4 rounded-xl inline-flex items-center text-white cursor-pointer">


                        <div class="mx-auto flex">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 01-3.6 9.75m6.633-4.596a18.666 18.666 0 01-2.485 5.33" />
                            </svg>
                        {{ __('Passkey') }}
                        </div>
                    </button>
                </div>
                @else
                    <div class="mt-6">
                        <button
                                class="w-full px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-blue-500 rounded-md hover:bg-blue-400 focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                            {{ __('Sign in') }}
                        </button>
                    </div>
                @endif
        </form>
        <p class="mt-6 text-sm text-center text-gray-400">Don&#x27;t have an account yet?
            <a
                    href="{{ route('auth.register') }}"
                    class="text-blue-500 focus:outline-none focus:underline hover:underline">{{ __('Sign up') }}</a>.
        </p>
        <p class="mt-6 text-sm text-center text-gray-400">
            <a
                    href="{{ route('auth.password.request') }}"
                    class="text-blue-500 focus:outline-none focus:underline hover:underline">{{ __('Forgotten password?') }}</a>.
        </p>
    </div>

    <script type="text/javascript">


      async function doPasskey(event) {
        event.preventDefault()

        // Get this from a form, etc.
        const email = document.getElementById('email').value

        // This can be any format you want, as long as it works with the above code
        const response = await fetch('/passkey/find', {
          method: 'POST',
          body: 'email=' + email,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
          },
        })

        const data = await response.json()

        // TODO: On Error

        // Format for WebAuthn API
        const getOptions = {
          publicKey: {
            challenge: Uint8Array.from(atob(data.challengeB64), c => c.charCodeAt(0)),
            allowCredentials: data.credential_ids.map(id => ({
              id: Uint8Array.from(atob(id), c => c.charCodeAt(0)),
              type: 'public-key',
            }))
          },
        }

        // Similar to registration step 2

        // Call the WebAuthn browser API and get the response. This may throw, which you
        // should handle. Example: user cancels or never interacts with the device.
        const credential = await navigator.credentials.get(getOptions)

        // Format the credential to send to the server. This must match the format
        // handed by the ResponseParser class. The formatting code below can be used
        // without modification.
        const dataForResponseParser = {
          rawId: Array.from(new Uint8Array(credential.rawId)),
          type: credential.type,
          authenticatorData: Array.from(new Uint8Array(credential.response.authenticatorData)),
          clientDataJSON: Array.from(new Uint8Array(credential.response.clientDataJSON)),
          signature: Array.from(new Uint8Array(credential.response.signature)),
          userHandle: Array.from(new Uint8Array(credential.response.userHandle)),
        }

        // Send this to your endpoint - adjust to your needs.
        const request = new Request('/passkey/login', {
          body: JSON.stringify(dataForResponseParser),
          headers: {
            'Content-type': 'application/json',
          },
          method: 'POST',
        })
        const result = await fetch(request)
        const loginData = await result.json()

        if (loginData.status === 'OK') {
          window.location.href =  loginData.redirect_to
        }
        // handle result - if it went ok, perform any client needs to finish auth process
        // const responseData = await response.json()
       // if (result.)
       //  console.log('result', await result.json())
        return false;
      }
      // dd

      const elm = document.getElementById('passkey');
      elm.addEventListener('click', doPasskey)
    </script>

@endsection
