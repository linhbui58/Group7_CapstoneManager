    </div>

    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DATATABLE -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- SWEET ALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- APP JS -->
    <script src="assets/js/app.js"></script>

    <script>

        /*
        |--------------------------------------------------------------------------
        | AOS INIT
        |--------------------------------------------------------------------------
        */

        AOS.init({

            duration: 700,
            once: true

        });

        /*
        |--------------------------------------------------------------------------
        | GLOBAL LOADER
        |--------------------------------------------------------------------------
        */

        $(window).on('load', function(){

            $('#global-loader').fadeOut(300);

        });

        /*
        |--------------------------------------------------------------------------
        | DATATABLE INIT
        |--------------------------------------------------------------------------
        */

        $(document).ready(function() {
    /* KHỞI TẠO DATATABLE AN TOÀN — bỏ qua bảng đã có id riêng */
    $.fn.dataTable.ext.errMode = 'none';

    $('.datatable').each(function() {
        // Bỏ qua nếu bảng đã có id (đã được init riêng ở page-level)
        if ($(this).attr('id')) return;
        // Bỏ qua nếu đã init rồi
        if ($.fn.DataTable.isDataTable(this)) return;

        $(this).DataTable({
            retrieve: true,
            responsive: true,
            language: {
                search: "",
                searchPlaceholder: "Tìm kiếm...",
                lengthMenu: "Hiện _MENU_ dòng",
                info: "Hiển thị _START_ đến _END_ trong _TOTAL_ mục",
                paginate: { next: "Sau", previous: "Trước" }
            }
        });
    });

    /* TỰ ĐỘNG ẨN THÔNG BÁO */
    setTimeout(() => {
        $('.alert').fadeOut(500);
    }, 3000);
});
        /*
        |--------------------------------------------------------------------------
        | DELETE CONFIRM
        |--------------------------------------------------------------------------
        */

        $(document).on('click', '.btn-delete', function(e){

            e.preventDefault();

            let link = $(this).attr('href');

            Swal.fire({

                title: 'Are you sure?',

                text: "This data will be deleted.",

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#6366f1',

                cancelButtonColor: '#ef4444',

                confirmButtonText: 'Delete'

            }).then((result) => {

                if(result.isConfirmed){

                    window.location.href = link;

                }

            });

        });

        /*
        |--------------------------------------------------------------------------
        | AUTO CLOSE ALERT
        |--------------------------------------------------------------------------
        */

        setTimeout(() => {

            $('.alert').fadeOut();

        }, 3000);

        /*
        |--------------------------------------------------------------------------
        | MOBILE SIDEBAR
        |--------------------------------------------------------------------------
        */

        $('#sidebarToggle').click(function(){

            $('.sidebar').toggleClass('show');

            $('#sidebar-overlay').toggleClass('active');

        });

        $('#sidebar-overlay').click(function(){

            $('.sidebar').removeClass('show');

            $('#sidebar-overlay').removeClass('active');

        });

    </script>

</body>
</html>