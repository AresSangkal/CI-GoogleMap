<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b style="font-weight: 600;">Version</b> 1.3.2
    </div>
    <strong style="font-weight: 600;"><?php echo html_escape($settings->copyright); ?></strong>
</footer>
</div>
<!-- ./wrapper -->
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
    var thousands_separator = '<?php echo $this->thousands_separator; ?>';
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/admin/vendor/bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables js -->
<script src="<?php echo base_url(); ?>assets/admin/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/vendor/datatables/dataTables.bootstrap.min.js"></script>
<!-- Bootstrap Toggle Js -->
<script src="<?php echo base_url(); ?>assets/admin/js/bootstrap-toggle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/admin/js/adminlte.min.js"></script>
<!-- iCheck js -->
<script src="<?php echo base_url(); ?>assets/admin/vendor/icheck/icheck.min.js"></script>
<!-- Pace -->
<script src="<?php echo base_url(); ?>assets/admin/vendor/pace/pace.min.js"></script>
<!-- Ckeditor js -->
<script src="<?php echo base_url(); ?>assets/vendor/ckeditor/ckeditor.js"></script>
<!-- Tagsinput js -->
<script src="<?php echo base_url(); ?>assets/admin/vendor/tagsinput/jquery.tagsinput.min.js"></script>
<!-- Plugins JS-->
<script src="<?php echo base_url(); ?>assets/admin/js/plugins.js"></script>

<script src="<?php echo base_url(); ?>assets/admin/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
<!-- Custom js -->
<script src="<?php echo base_url(); ?>assets/admin/js/script.js"></script>
<!-- Ckeditor -->
<script>
    var ckEditor = document.getElementById('ckEditor');
    if (ckEditor != null) {
        CKEDITOR.replace('ckEditor');
    }
</script>
<script>
    var ckEditor = document.getElementById('ckEditorBankAccounts');
    if (ckEditor != null) {
        CKEDITOR.replace('ckEditorBankAccounts', {
            language: 'en',
            toolbar: [
                ['Copy', 'Paste', 'PasteText'],
                {name: 'basicstyles', items: ['Bold', 'Italic']}, {name: 'colors', items: ['TextColor', 'BGColor']},
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']},
            ]
        });
    }
</script>
<?php if (isset($lang_search_column)): ?>
    <script>
        var table = $('#cs_datatable_lang').DataTable({
            dom: 'l<"#table_dropdown">frtip',
            "order": [[0, "desc"]],
            "aLengthMenu": [[15, 30, 60, 100], [15, 30, 60, 100, "All"]]
        });
        //insert a label
        $('<label class="table-label"><label/>').text('Language').appendTo('#table_dropdown');

        //insert the select and some options
        $select = $('<select class="form-control input-sm"><select/>').appendTo('#table_dropdown');

        $('<option/>').val('').text('<?php echo trans("all"); ?>').appendTo($select);
        <?php foreach ($languages as $lang): ?>
        $('<option/>').val('<?php echo $lang->name; ?>').text('<?php echo $lang->name; ?>').appendTo($select);
        <?php endforeach; ?>

        table.column(<?php echo $lang_search_column; ?>).search('').draw();

        $("#table_dropdown select").change(function () {
            table.column(<?php echo $lang_search_column; ?>).search($(this).val()).draw();
        });
    </script>
<?php endif; ?>
<script>
    $('#location_1').on('ifChecked', function () {
        $("#location_countries").hide();
    });
    $('#location_2').on('ifChecked', function () {
        $("#location_countries").show();
    });
</script>
</body>
</html>
