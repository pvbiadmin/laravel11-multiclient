<script>
    // Toastr configuration
    toastr.options = {
        "closeButton": true, // Show close button
        "debug": false, // Disable debug mode
        "progressBar": true, // Show progress bar
        "positionClass": "toast-top-right", // Position of the toast
        "showDuration": "300", // Duration of the show animation
        "hideDuration": "1000", // Duration of the hide animation
        "timeOut": "5000", // Auto-close after 5 seconds
        "extendedTimeOut": "1000", // Additional time if the user hovers over the toast
        "showEasing": "swing", // Easing for show animation
        "hideEasing": "linear", // Easing for hide animation
        "showMethod": "fadeIn", // Show method
        "hideMethod": "fadeOut" // Hide method
    };

    // Display validation errors
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif

    // Display session messages
    @if (Session::has('message'))
        const type = "{{ Session::get('alert-type', 'success') }}";
        const message = "{{ Session::get('message') }}";

        switch (type) {
            case 'info':
                toastr.info(message);
                break;
            case 'success':
                toastr.success(message);
                break;
            case 'warning':
                toastr.warning(message);
                break;
            case 'error':
                toastr.error(message);
                break;
            default:
                toastr.success(message); // Default to success if no type is provided
        }
    @endif
</script>
