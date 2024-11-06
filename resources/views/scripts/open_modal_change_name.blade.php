
@push('scripts')
    @if (session('show_name_modal'))
        <script type="text/javascript">
            $(document).ready(function(){
                $('#nameConfirmationModal').modal('show');
            });
        </script>
    @endif
@endpush
