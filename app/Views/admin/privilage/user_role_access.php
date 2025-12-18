<div class="card">
    <div class="card-body">
        <h5 class="mb-0"><?php echo $user_role['role_admin']; ?></h5>
        <div class="table-responsive-md">
            <table id="table-data" class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th class="number">No</th> 
                        <th class="text-center">Nama Akses Menu</th>
                        <th class="text-center" width="10%" style="background: #afe7e7;">VIEW</th>
                        <th class="text-center" width="10%" style="background: #bcebd1;">CREATE</th>
                        <th class="text-center" width="10%" style="background: #d8dafc;">EDIT</th>
                        <th class="text-center" width="10%" style="background: #ffc6d4;">DELETE</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $.getScript('<?php echo base_url();?>assets/backoffice/js/custome.js');

        var save_method;

        var table_data = $('#table-data').DataTable({
            ajax: {
                url : '<?php echo base_url();?>admin/privilage/user_role_access/datatables',
            },
            order      : [[ 0, 'ASC' ]],
            pageLength : 50,
            ordering   : false,
            searching  : false,
            info       : false,
            paging     : false,
            columnDefs : [
                {
                    className: 'text-center',
                    targets  : [0, -4, -3, -2, -1],
                },
                {
                    orderable: false,
                    targets  : [0, 1, -1],
                }
            ],
        });


        $(document).on('click', '.trigg-checkbox', function() {

            var id_menu = $(this).attr('data');
            var param   = $(this).attr('param');
            var id_role = '<?php echo $user_role['id_role']; ?>';

            $.ajax({
                method : 'post',
                url    : '<?php echo base_url();?>admin/privilage/user_role_access/menu_edit',
                data   : { id_menu:id_menu, param:param, id_role:id_role },
                dataType : 'json',
                success: function(response) {
                    if(response.status == 1) {
                        notif_success(response.message);
                    } else {
                        notif_error(response.message);
                    } 
                },
                async: false
            });
        });

    });

</script>