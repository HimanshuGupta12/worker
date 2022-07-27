<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scanner</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link href="https://unpkg.com/vue-qrcode-reader@2.0.3/dist/vue-qrcode-reader.css" rel="stylesheet">
</head>
<body>
    @include('partial.messages')
    <div id="app">
        {{--    <p>--}}
        {{--        Last result: <b>@{{ decodedContent }}</b>--}}
        {{--    </p>--}}

{{--        <p class="error">--}}
{{--            @{{ errorMessage }}--}}
{{--        </p>--}}

        <qrcode-stream @decode="onDecode" @init="onInit"></qrcode-stream>
    </div>

    @yield('buttons')

    <script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js"></script>

    <script src="https://unpkg.com/vue@2.6.10/dist/vue.min.js"></script>
    <script src="{{ env('PUBLIC_PATH') }}/js/VueQrcodeReader.umd.min.js"></script>
    <script>
        function redirect(code) {
            var url = @json($redirect);
            window.location = url + '?code=' + code;
        }
        new Vue({
            el: '#app',

            data() {
                return {
                    decodedContent: '',
                    errorMessage: ''
                }
            },

            methods: {
                onDecode(content) {
                    this.decodedContent = content;
                    if (content) { // if not empty string or null
                        redirect(content);
                    }
                },

                onInit(promise) {
                    promise.then(() => {
                        console.log('Successfully initilized! Ready for scanning now!')
                    })
                        .catch(error => {
                            if (error.name === 'NotAllowedError') {
                                this.errorMessage = 'Hey! I need access to your camera'
                            } else if (error.name === 'NotFoundError') {
                                this.errorMessage = 'Do you even have a camera on your device?'
                            } else if (error.name === 'NotSupportedError') {
                                this.errorMessage = 'Seems like this page is served in non-secure context (HTTPS, localhost or file://)'
                            } else if (error.name === 'NotReadableError') {
                                this.errorMessage = 'Couldn\'t access your camera. Is it already in use?'
                            } else if (error.name === 'OverconstrainedError') {
                                this.errorMessage = 'Constraints don\'t match any installed camera. Did you asked for the front camera although there is none?'
                            } else {
                                this.errorMessage = 'UNKNOWN ERROR: ' + error.message
                            }
                        })
                }
            }
        })
    </script>
</body>
</html>
