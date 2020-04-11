<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .sk-chase {
                width: 40px;
                height: 40px;
                position: relative;
                animation: sk-chase 2.5s infinite linear both;
            }

            .sk-chase-dot {
                width: 100%;
                height: 100%;
                position: absolute;
                left: 0;
                top: 0;
                animation: sk-chase-dot 2.0s infinite ease-in-out both;
            }

            .sk-chase-dot:before {
                content: '';
                display: block;
                width: 25%;
                height: 25%;
                background-color: #000;
                border-radius: 100%;
                animation: sk-chase-dot-before 2.0s infinite ease-in-out both;
            }

            .sk-chase-dot:nth-child(1) { animation-delay: -1.1s; }
            .sk-chase-dot:nth-child(2) { animation-delay: -1.0s; }
            .sk-chase-dot:nth-child(3) { animation-delay: -0.9s; }
            .sk-chase-dot:nth-child(4) { animation-delay: -0.8s; }
            .sk-chase-dot:nth-child(5) { animation-delay: -0.7s; }
            .sk-chase-dot:nth-child(6) { animation-delay: -0.6s; }
            .sk-chase-dot:nth-child(1):before { animation-delay: -1.1s; }
            .sk-chase-dot:nth-child(2):before { animation-delay: -1.0s; }
            .sk-chase-dot:nth-child(3):before { animation-delay: -0.9s; }
            .sk-chase-dot:nth-child(4):before { animation-delay: -0.8s; }
            .sk-chase-dot:nth-child(5):before { animation-delay: -0.7s; }
            .sk-chase-dot:nth-child(6):before { animation-delay: -0.6s; }

            @keyframes sk-chase {
                100% { transform: rotate(360deg); }
            }

            @keyframes sk-chase-dot {
                80%, 100% { transform: rotate(360deg); }
            }

            @keyframes sk-chase-dot-before {
                50% {
                    transform: scale(0.4);
                } 100%, 0% {
                      transform: scale(1.0);
                  }
            }
        </style>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content" x-data="test()">
                <input type='text' x-model='foo' id="name"/>
                <input  x-on:change="uploadFile('/upload', $event.target)" type="file" accept="video/*" capture="user" id="recorder">
                <div x-show="is_loading == true" class="sk-chase">
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                </div>
                <a x-bind:href="button_url" x-show="button_url !== null">Go to your video</a>
            </div>
        </div>
        <script>
            function test() {
                return {
                    foo: 'singlewordslug',
                    button_url: null,
                    is_loading: false,
                    messageDisplay: '',
                    uploadFile(a, b) {
                        var data = new FormData();
                        data.append('file', b.files[0]);
                        data.append('name', this.foo);
                        var config = {
                            onUploadProgress: function (progressEvent) {
                                var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                            }
                        };
                        this.is_loading = true;
                        let self = this;
                        axios.post('/api/upload', data, config)
                            .then(function (res) {
                                self.is_loading = null;
                                self.button_url = res.data.url;
                            })
                            .catch(function (err) {
//                        output.className = 'container text-danger';
//                        output.innerHTML = err.message;
                            });
                    }
                }
            }
        </script>
    </body>
</html>
