<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        @media (max-width: 600px) {
            div {
                padding-left: 35px !important;
                padding-right: 35px !important;
            }
        }

        @media (min-width: 1000px) {
            #main {
                font-size: 25px;
            }

            #image {
                height: 40px;
            }
        }
    </style>
</head>


<body style="width: 100%; background-color:#181624; height: 100%">
    <div id="main" style="color: white; padding: 80px 194px;">
        <img id="image" style="display: block; margin-left: auto; margin-right: auto;"
            src="{{ asset('images/confirm-email.png') }}" alt='confirm email' />
        <p style="text-align: center; color:#DDCCAA;">MOVIE QUOTES</p>
        <p style="margin-top: 80px; margin-bottom: 24px; font-family: sans-serif">{{ __('reset-password.hola') }}
            {{ $name }}!</p>
        <p style="margin-bottom: 50px; margin-top: 40px; font-family: sans-serif">{{ __('reset-password.click_link') }}
        </p>
        <a style="background-color: #E31221; padding: 7px 13px; border-radius: 4px; color:white; text-decoration:none; font-family: sans-serif"
            href={{ $url }}>{{ __('reset-password.reset_password') }}</a>
        <p style="margin-top: 40px; margin-top: 40px; display: block; margin-bottom: 24px; font-family: sans-serif">If
            {{ __('reset-password.paste') }}</p>
        <div style="word-break: break-all">
            <p style="color: #DDCCAA; margin-bottom: 40px; font-family: sans-serif">
                {{ $url }}
            </p>
        </div>
        <p style='margin-bottom: 24px; font-family: sans-serif;'>{{ __('reset-password.contact') }}</p>
        <p style="font-family: sans-serif">{{ __('reset-password.crew') }}</p>
    </div>
</body>



</html>
