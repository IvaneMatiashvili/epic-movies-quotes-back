<x-email-layout>
    <div class="main" style="
        height: 95vh;
        overflow: hidden;
     background: linear-gradient(187.16deg, #181623 0.07%, #191725 51.65%, #0D0B14 98.75%);
    ">
    <img src="{{ $message->embed(public_path().'/assets/icon.png') }}" alt="landing image" style="display: block;
        margin-top: 5rem;
        margin-left: auto;
        margin-right: auto;
        "
    />

    <img src="{{ $message->embed(public_path().'/assets/movie-quotes.png') }}" alt="landing image" style="display: block;
        margin-top: 1rem;
        margin-left: auto;
        margin-right: auto;
        "
    />
        <p class="confirmation" style="font-weight: 400;
        font-size: 18px;
        margin-left: 11rem;
        margin-top: 3.5rem;
        color: #FFFFFF;
        font-family: 'Inter', 'sans-serif';">
            {{ __('content.hola') .' ' .  $user->name }}
        </p>

    <p class="confirmation" style="font-weight: 400;
        font-size: 18px;
        margin-left: 11rem;
        margin-top: 2rem;
        color: #FFFFFF;
        font-family: 'Inter', 'sans-serif';">
        {{ $fromProfile === true ? __('content.newEmailMsg') : __('content.thanksForJoiningAndVerify') }}
    </p>
        <a href="{{ $locale === 'ka' ? urldecode(env('FRONT_URL').'ka'.'/verify-email?stage=emailActivated&feedback=').$url : urldecode(env('FRONT_URL').'/verify-email?stage=emailActivated&feedback=').$url  }}" style=";
        margin-top: 2.5rem;
        font-size: 18px;
        font-weight: 400;
        color: #FFFFFF;
        background: #E31221;
        display: block;
        text-align: center;
        border: 0.8rem solid #E31221;
        height: 1.3rem;
        width: 8rem;
        margin-left: 11rem;
        border-radius: 8px;
        text-decoration: none;
        font-family: 'Inter', 'sans-serif';">
            {{ __('content.verifyAccount') }}
        </a>

    <p class="confirmation" style="font-weight: 400;
        font-size: 18px;
        margin-left: 11rem;
        margin-top: 3rem;
        color: #FFFFFF;
        font-family: 'Inter', 'sans-serif';">
        {{ __('content.clickDoesNotWork') }}
    </p>

    <p class="confirmation" style="font-weight: 400;
        font-size: 18px;
        margin-left: 11rem;
        margin-top: 2rem;
        color: #DDCCAA;
        font-family: 'Inter', 'sans-serif';">
        {{$locale === 'ka' ? urldecode(env('FRONT_URL').'ka'.'/verify-email?stage=emailActivated&feedback=').$url : urldecode(env('FRONT_URL').'/verify-email?stage=emailActivated&feedback=').$url}}
    </p>

    <p class="confirmation" style="font-weight: 400;
        font-size: 18px;
        margin-left: 11rem;
        margin-top: 3rem;
        color: #FFFFFF;
        font-family: 'Inter', 'sans-serif';">
        {{ __('content.anyProblems') }}
    </p>

    <p class="confirmation" style="font-weight: 400;
        font-size: 18px;
        margin-left: 11rem;
        margin-top: 2.5rem;
        color: #FFFFFF;
        font-family: 'Inter', 'sans-serif';">
        {{ __('content.crew') }}
    </p>
    </div>

    <style>
        @font-face {
            font-family: 'Inter';
            font-style: normal;
        }
        @media (max-width: 900px) {
            body {
                overflow: visible !important;
            }
            div {
                height: 120vh !important;
                 overflow-x: hidden;
                overflow-y: scroll;
            }
            p {
                margin-left: 1rem !important;
                word-break: break-all;
            }
            a {
                margin-left: 1rem !important;
            }
        }
        @media (max-width: 600px) {
            div {
                height: 170vh !important;
            }
        }
    </style>
</x-email-layout>
