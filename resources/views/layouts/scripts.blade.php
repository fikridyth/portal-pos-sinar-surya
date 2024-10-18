<!-- MDB -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
{{-- Swal --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Jquery --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
{{-- DataTable --}}
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>

{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

{{-- Validation --}}
<script>
    $(document).ready(function() {
        $('#tab-register').change(function() {
            $('#registerEmail, #registerPassword').val('');
        });
    });

    $(document).ready(function() {
        @if (session('alert.status'))
            show_alert_dialog(`{{ session('alert.status') }}`, `{{ session('alert.message') }}`);
        @endif

        @if ($errors->any())
            var status = ``;
            var message = ``;
            @if (session('response_code'))
                status = {{ session('response_code') }};
            @endif
            @foreach ($errors->all() as $error)
                message += `{{ $error }}<br/>`;
            @endforeach
            message += ``;
            show_alert_dialog(status, message);
        @endif

        @if (request()->get('alert_status'))
            show_alert_dialog("{{ request()->get('alert_status') }}", "{{ request()->get('alert_message') }}");
        @endif
    });

    function show_alert_dialog(status, message) {
        if (!(typeof message === "string" || message instanceof String)) {
            message = message.responseText;
        }

        if (status == "00")
            Swal.fire({
                title: "Success",
                html: message,
                icon: "success",
            });
        else if (status == "99")
            Swal.fire({
                title: "Failed",
                html: message,
                icon: "error",
            });
        else
            Swal.fire({
                title: "Process Failed",
                html: message,
                icon: "warning",
            });
    }
</script>

{{-- Redirect Swal --}}
@if (session('alert'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('alert')['message'] }}',
            showConfirmButton: true,
        });
    </script>
@endif

{{-- Delete Swal --}}
<script>
    function deleteData(row_id) {
        const formId = 'delete-form-' + row_id;

        Swal.fire({
            title: 'Notification',
            text: "Delete Data?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form directly
                document.getElementById(formId).submit();
            }
        });
    }

    function confirmAlert(event, text) {
        event.preventDefault();
        Swal.fire({
            title: 'Notification',
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form directly
                document.getElementById('myForm').submit();
            }
        });
    }

    function formatInputNumber(input) {
        let value = input.value.replace(/\D/g, '');
        if (!value) { value = '0'; }
        value = parseInt(value, 10).toLocaleString('id-ID');
        input.value = value;
    }
</script>