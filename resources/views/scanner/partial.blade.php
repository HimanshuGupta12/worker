<div id="app">
    <div id="loading" style="margin-top: 10%; margin-left: 50%;">
    <div class="spinner-border text-primary m-1" role="status" ><span class="sr-only"></span></div>
    </div>
    <qrcode-stream @decode="onDecode" @init="onInit"></qrcode-stream>
</div>

@section('head')
    @parent
    <link href="https://unpkg.com/vue-qrcode-reader@2.0.3/dist/vue-qrcode-reader.css" rel="stylesheet">
@endsection

@section('scripts')
    @parent
    <script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js"></script>

    <script src="https://unpkg.com/vue@2.6.10/dist/vue.min.js"></script>
    <script src="{{ env('PUBLIC_PATH') }}/js/VueQrcodeReader.umd.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            translations = '<?php echo $translations ?>';
            translations = JSON.parse(translations);
        });
        function redirect(code) {
            var url = @json($redirect);
            window.location = url + '?code=' + code;
        }
        
        function getTranslation (key_required) {
            let required_value = key_required;
            let keys = Object.keys(translations);
            let texts = Object.values(translations);
            $.each(keys, function( i, key ) {
                if (key == key_required) {
                    required_value = texts[i];
                }
            });
            return required_value;
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
                        $("#loading").hide();
                    })
                    .catch(error => {
                        if (error.name === 'NotAllowedError') {
                            this.errorMessage = getTranslation('Hey! I need access to your camera');
                        } else if (error.name === 'NotFoundError') {
                            this.errorMessage = getTranslation('Do you even have a camera on your device?');
                        } else if (error.name === 'NotSupportedError') {
                            this.errorMessage = getTranslation('Seems like this page is served in non-secure context (HTTPS, localhost or file://)');
                        } else if (error.name === 'NotReadableError') {
                            this.errorMessage = getTranslation("Could not access your camera. Is it already in use?");
                        } else if (error.name === 'OverconstrainedError') {
                            this.errorMessage = getTranslation("Constraints do not match any installed camera. Did you asked for the front camera although there is none?");
                        } else {
                            this.errorMessage = getTranslation("UNKNOWN ERROR")+ ': ' + error.message
                        }
                    })
                }
            }
        })
    </script>
@endsection
