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

        @oauth
        <div class="mb-4 ">
            <div class="w-24 mx-auto flex">
                @oauth_google
                <a href="{{ route('auth.oauth.login', ['service' => 'google']) }}" title="{{__('Login with Google')}}">

                    <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                    <svg class="w-6 h-6 mx-1" viewBox="-3 0 262 262" version="1.1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid">
                        <g>
                            <path d="M255.878,133.451 C255.878,122.717 255.007,114.884 253.122,106.761 L130.55,106.761 L130.55,155.209 L202.497,155.209 C201.047,167.249 193.214,185.381 175.807,197.565 L175.563,199.187 L214.318,229.21 L217.003,229.478 C241.662,206.704 255.878,173.196 255.878,133.451"
                                  fill="#4285F4">

                            </path>
                            <path d="M130.55,261.1 C165.798,261.1 195.389,249.495 217.003,229.478 L175.807,197.565 C164.783,205.253 149.987,210.62 130.55,210.62 C96.027,210.62 66.726,187.847 56.281,156.37 L54.75,156.5 L14.452,187.687 L13.925,189.152 C35.393,231.798 79.49,261.1 130.55,261.1"
                                  fill="#34A853">

                            </path>
                            <path d="M56.281,156.37 C53.525,148.247 51.93,139.543 51.93,130.55 C51.93,121.556 53.525,112.853 56.136,104.73 L56.063,103 L15.26,71.312 L13.925,71.947 C5.077,89.644 0,109.517 0,130.55 C0,151.583 5.077,171.455 13.925,189.152 L56.281,156.37"
                                  fill="#FBBC05">

                            </path>
                            <path d="M130.55,50.479 C155.064,50.479 171.6,61.068 181.029,69.917 L217.873,33.943 C195.245,12.91 165.798,0 130.55,0 C79.49,0 35.393,29.301 13.925,71.947 L56.136,104.73 C66.726,73.253 96.027,50.479 130.55,50.479"
                                  fill="#EB4335">

                            </path>
                        </g>
                    </svg>
                </a>
                @endoauth_google

                @oauth_discord
                <a href="{{ route('auth.oauth.login', ['service' => 'discord']) }}" title="{{__('Login with Discord')}}">

                    <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                    <svg class="w-6 h-6 mx-1" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="512" cy="512" r="512" style="fill:#5865f2"/>
                        <path d="M689.43 349a422.21 422.21 0 0 0-104.22-32.32 1.58 1.58 0 0 0-1.68.79 294.11 294.11 0 0 0-13 26.66 389.78 389.78 0 0 0-117.05 0 269.75 269.75 0 0 0-13.18-26.66 1.64 1.64 0 0 0-1.68-.79A421 421 0 0 0 334.44 349a1.49 1.49 0 0 0-.69.59c-66.37 99.17-84.55 195.9-75.63 291.41a1.76 1.76 0 0 0 .67 1.2 424.58 424.58 0 0 0 127.85 64.63 1.66 1.66 0 0 0 1.8-.59 303.45 303.45 0 0 0 26.15-42.54 1.62 1.62 0 0 0-.89-2.25 279.6 279.6 0 0 1-39.94-19 1.64 1.64 0 0 1-.16-2.72c2.68-2 5.37-4.1 7.93-6.22a1.58 1.58 0 0 1 1.65-.22c83.79 38.26 174.51 38.26 257.31 0a1.58 1.58 0 0 1 1.68.2c2.56 2.11 5.25 4.23 8 6.24a1.64 1.64 0 0 1-.14 2.72 262.37 262.37 0 0 1-40 19 1.63 1.63 0 0 0-.87 2.28 340.72 340.72 0 0 0 26.13 42.52 1.62 1.62 0 0 0 1.8.61 423.17 423.17 0 0 0 128-64.63 1.64 1.64 0 0 0 .67-1.18c10.68-110.44-17.88-206.38-75.7-291.42a1.3 1.3 0 0 0-.63-.63zM427.09 582.85c-25.23 0-46-23.16-46-51.6s20.38-51.6 46-51.6c25.83 0 46.42 23.36 46 51.6.02 28.44-20.37 51.6-46 51.6zm170.13 0c-25.23 0-46-23.16-46-51.6s20.38-51.6 46-51.6c25.83 0 46.42 23.36 46 51.6.01 28.44-20.17 51.6-46 51.6z" style="fill:#fff"/>
                    </svg>
                </a>
                @endoauth_discord

                @oauth_github
                <a href="{{ route('auth.oauth.login', ['service' => 'github']) }}" title="{{__('Login with Github')}}">


                    <svg class="w-6 h-6 mx-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.0744 2.9938C4.13263 1.96371 4.37869 1.51577 5.08432 1.15606C5.84357 0.768899 7.04106 0.949072 8.45014 1.66261C9.05706 1.97009 9.11886 1.97635 10.1825 1.83998C11.5963 1.65865 13.4164 1.65929 14.7213 1.84164C15.7081 1.97954 15.7729 1.97265 16.3813 1.66453C18.3814 0.651679 19.9605 0.71795 20.5323 1.8387C20.8177 2.39812 20.8707 3.84971 20.6494 5.04695C20.5267 5.71069 20.5397 5.79356 20.8353 6.22912C22.915 9.29385 21.4165 14.2616 17.8528 16.1155C17.5801 16.2574 17.3503 16.3452 17.163 16.4167C16.5879 16.6363 16.4133 16.703 16.6247 17.7138C16.7265 18.2 16.8491 19.4088 16.8973 20.4002C16.9844 22.1922 16.9831 22.2047 16.6688 22.5703C16.241 23.0676 15.6244 23.076 15.2066 22.5902C14.9341 22.2734 14.9075 22.1238 14.9075 20.9015C14.9075 19.0952 14.7095 17.8946 14.2417 16.8658C13.6854 15.6415 14.0978 15.185 15.37 14.9114C17.1383 14.531 18.5194 13.4397 19.2892 11.8146C20.0211 10.2698 20.1314 8.13501 18.8082 6.83668C18.4319 6.3895 18.4057 5.98446 18.6744 4.76309C18.7748 4.3066 18.859 3.71768 18.8615 3.45425C18.8653 3.03823 18.8274 2.97541 18.5719 2.97541C18.4102 2.97541 17.7924 3.21062 17.1992 3.49805L16.2524 3.95695C16.1663 3.99866 16.07 4.0147 15.975 4.0038C13.5675 3.72746 11.2799 3.72319 8.86062 4.00488C8.76526 4.01598 8.66853 3.99994 8.58215 3.95802L7.63585 3.49882C7.04259 3.21087 6.42482 2.97541 6.26317 2.97541C5.88941 2.97541 5.88379 3.25135 6.22447 4.89078C6.43258 5.89203 6.57262 6.11513 5.97101 6.91572C5.06925 8.11576 4.844 9.60592 5.32757 11.1716C5.93704 13.1446 7.4295 14.4775 9.52773 14.9222C10.7926 15.1903 11.1232 15.5401 10.6402 16.9905C10.26 18.1319 10.0196 18.4261 9.46707 18.4261C8.72365 18.4261 8.25796 17.7821 8.51424 17.1082C8.62712 16.8112 8.59354 16.7795 7.89711 16.5255C5.77117 15.7504 4.14514 14.0131 3.40172 11.7223C2.82711 9.95184 3.07994 7.64739 4.00175 6.25453C4.31561 5.78028 4.32047 5.74006 4.174 4.83217C4.09113 4.31822 4.04631 3.49103 4.0744 2.9938Z" fill="#FFFFFF"/>
                        <path d="M3.33203 15.9454C3.02568 15.4859 2.40481 15.3617 1.94528 15.6681C1.48576 15.9744 1.36158 16.5953 1.66793 17.0548C1.8941 17.3941 2.16467 17.6728 2.39444 17.9025C2.4368 17.9449 2.47796 17.9858 2.51815 18.0257C2.71062 18.2169 2.88056 18.3857 3.05124 18.5861C3.42875 19.0292 3.80536 19.626 4.0194 20.6962C4.11474 21.1729 4.45739 21.4297 4.64725 21.5419C4.85315 21.6635 5.07812 21.7352 5.26325 21.7819C5.64196 21.8774 6.10169 21.927 6.53799 21.9559C7.01695 21.9877 7.53592 21.998 7.99999 22.0008C8.00033 22.5527 8.44791 23.0001 8.99998 23.0001C9.55227 23.0001 9.99998 22.5524 9.99998 22.0001V21.0001C9.99998 20.4478 9.55227 20.0001 8.99998 20.0001C8.90571 20.0001 8.80372 20.0004 8.69569 20.0008C8.10883 20.0026 7.34388 20.0049 6.67018 19.9603C6.34531 19.9388 6.07825 19.9083 5.88241 19.871C5.58083 18.6871 5.09362 17.8994 4.57373 17.2891C4.34391 17.0194 4.10593 16.7834 3.91236 16.5914C3.87612 16.5555 3.84144 16.5211 3.80865 16.4883C3.5853 16.265 3.4392 16.1062 3.33203 15.9454Z" fill="#FFFFFF"/>
                    </svg>

                </a>
                @endoauth_github

            </div>
        </div>

        @endauth
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
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 01-3.6 9.75m6.633-4.596a18.666 18.666 0 01-2.485 5.33"/>
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


      async function doPasskey (event) {
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
        //
        // Format for WebAuthn API

        // Similar to registration step 2

        // Call the WebAuthn browser API and get the response. This may throw, which you
        // should handle. Example: user cancels or never interacts with the device.
        const credential = await navigator.credentials.get({
          publicKey: {
            challenge: Uint8Array.from(atob(data.challengeB64), c => c.charCodeAt(0)),
            allowCredentials: data.credential_ids.map(id => ({
              id: Uint8Array.from(atob(id), c => c.charCodeAt(0)),
              type: 'public-key',
            }))
          },
        })

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
          window.location.href = loginData.redirect_to
        }
        // handle result - if it went ok, perform any client needs to finish auth process
        // const responseData = await response.json()
        // if (result.)
        //  console.log('result', await result.json())
        return false
      }

      // dd

      const elm = document.getElementById('passkey')
      elm.addEventListener('click', doPasskey)
    </script>

@endsection
