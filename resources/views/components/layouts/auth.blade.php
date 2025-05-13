<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>{{ config('app.name') }}</title>

  <link rel="icon" type="image/png" sizes="16x16" href="/logo.webp">

  <!-- General CSS Files -->
  <link rel="stylesheet" href="/assets/css/bootstrap-4-3.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="/assets/etc/bootstrap-social/bootstrap-social.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/components.css">
  <link rel="stylesheet" type="text/css" href="/assets/toastify/toastify.min.css">

</head>

<body>
  <div id="app">
    <section class="section">
      {{ $slot }}
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="/assets/js/bootstrap-4-3.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="/assets/js/stisla.js"></script>

  <!-- JS Libraies -->

  <!-- Template JS File -->
  <script src="/assets/js/scripts.js"></script>
  <script src="/assets/js/custom.js"></script>

  {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script> --}}
  <script type="text/javascript" src="/assets/toastify/toastify.min.js"></script>
  <!-- toast -->
  <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('success-message', (event) => {
                Toastify({
                    text: "✔️ " + event, // Tambah ikon check
                    className: "info",
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    style: {
                        background: "#4de265",
                        borderRadius: "8px",
                        fontWeight: "bold",
                        boxShadow: "0 4px 10px rgba(0, 0, 0, 0.15)",
                        color: "#fff"
                    }
                }).showToast();
            });

            Livewire.on('failed-message', (event) => {
                Toastify({
                    text: "❌ " + event,
                    className: "info",
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    style: {
                        background: "#e53935",
                        borderRadius: "8px",
                        fontWeight: "bold",
                        boxShadow: "0 4px 10px rgba(0, 0, 0, 0.15)",
                        color: "#fff"
                    }
                }).showToast();
            });
        });
  </script>
  @if (session('success-message'))
        <script>
            Toastify({
                text: "✔️ {{ session('success-message') }}",
                className: "info",
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                style: {
                    background: "#4de265",
                    borderRadius: "8px",
                    fontWeight: "bold",
                    boxShadow: "0 4px 10px rgba(0, 0, 0, 0.15)",
                    color: "#fff"
                }
            }).showToast();
        </script>
  @endif 
  @if (session('failed-message'))
        <script>
            Toastify({
                text: "❌ {{ session('failed-message') }}",
                className: "info",
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                style: {
                    background: "#e53935",
                    borderRadius: "8px",
                    fontWeight: "bold",
                    boxShadow: "0 4px 10px rgba(0, 0, 0, 0.15)",
                    color: "#fff"
                }
            }).showToast();
        </script>
  @endif

  <!-- Page Specific JS File -->
</body>
</html>
