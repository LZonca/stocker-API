<!DOCTYPE html>
<html>
<head>
    <base href="/mobile-app/">
    <meta charset="UTF-8">
    <meta content="IE=Edge" http-equiv="X-UA-Compatible">
    <meta name="description" content="App to manage stocks">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="stocker_mobile">
    <link rel="apple-touch-icon" href="{{ asset('mobile-app/icons/Icon-192.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('mobile-app/favicon.png') }}">
    <title>stocker_mobile</title>
    <link rel="manifest" href="{{ asset('mobile-app/manifest.json') }}">
    <script>
        const serviceWorkerVersion = "2002377419";
    </script>
    <script src="{{ asset('mobile-app/flutter.js') }}" defer></script>
</head>
<body>
<script>
    window.addEventListener('load', function(ev) {
        _flutter.loader.loadEntrypoint({
            serviceWorker: {
                serviceWorkerVersion: serviceWorkerVersion,
            },
            onEntrypointLoaded: function(engineInitializer) {
                engineInitializer.initializeEngine().then(function(appRunner) {
                    appRunner.runApp();
                });
            }
        });
    });
</script>
</body>
</html>
