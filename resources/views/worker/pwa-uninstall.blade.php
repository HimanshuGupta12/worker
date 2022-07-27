<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>

<h1>Uninstaller</h1>
<script type="text/javascript">
    if ('caches' in window) {
        caches.keys()
          .then(function(keyList) {
              return Promise.all(keyList.map(function(key) {
                  return caches.delete(key);
              }));
          })
          .then(function(){
            navigator.serviceWorker.getRegistrations().then( 
              function(registrations) { 
                  for(let registration of registrations) { 
                      registration.unregister(); 
                  } 
              }).then(function(){
              	window.location = '/'
              });
          })
      }
</script>
</body>
</html>