
  <!-- toast -->
  <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('success-message', (event) => {
                Toastify({
                    text: "✔️ " + event, 
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