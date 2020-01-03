<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
    $(document).on('click', '#btn_show_custom_field_category_select', function () {
        $('#category_select_container').show();
        $('#btn_show_custom_field_category_select').hide();
        $('#btn_clear_custom_fields_session_array').hide();
        $('#btn_clear_custom_fields_categories').hide();
    });
    $(document).on('click', '#btn_close_custom_field_category_select', function () {
        $('#category_select_container').hide();
        $('#btn_show_custom_field_category_select').show();
        $('#btn_clear_custom_fields_session_array').show();
        $('#btn_clear_custom_fields_categories').show();
    });
    $(document).on('click', '#btn_field_add_category_session', function () {
        var category_id = $('#select_field_add_category').val();
        if (category_id == 0) {
            $('#select_field_add_category').addClass('has-error');
            return false;
        } else {
            $('#select_field_add_category').removeClass('has-error');
        }
        var data = {
            "category_id": category_id
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            type: "POST",
            url: base_url + "ajax_controller/add_category_to_custom_fields_array",
            data: data,
            success: function (response) {
                document.getElementById("custom_fields_response").innerHTML = response;
            }
        });
    });

    $(document).on('click', '#btn_field_add_category', function () {
        var field_id = $(this).attr("data-field-id");
        var category_id = $('#select_field_add_category').val();
        if (category_id == 0) {
            $('#select_field_add_category').addClass('has-error');
            return false;
        } else {
            $('#select_field_add_category').removeClass('has-error');
        }
        var data = {
            "field_id": field_id,
            "category_id": category_id
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            type: "POST",
            url: base_url + "ajax_controller/add_category_to_custom_field",
            data: data,
            success: function (response) {
                document.getElementById("custom_fields_response").innerHTML = response;
            }
        });
    });

    $(document).on('click', '#btn_clear_custom_fields_session_array', function () {
        var data = {};
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            type: "POST",
            url: base_url + "ajax_controller/clear_custom_fields_session_array",
            data: data,
            success: function (response) {
                document.getElementById("custom_fields_response").innerHTML = response;
            }
        });
    });

    //clear custom files categories
    function clear_custom_files_categories(val) {
        var data = {
            "field_id": val
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            type: "POST",
            url: base_url + "ajax_controller/clear_custom_files_categories",
            data: data,
            success: function (response) {
                document.getElementById("custom_fields_response").innerHTML = response;
            }
        });
    }

    //get custom field category from session
    function delete_custom_field_category_session(val) {
        var data = {
            "category_id": val
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            type: "POST",
            url: base_url + "ajax_controller/delete_custom_field_category_session",
            data: data,
            success: function (response) {
                document.getElementById("custom_fields_response").innerHTML = response;
            }
        });
    }

    //get custom field category
    function delete_custom_field_category(field_id, category_id) {
        var data = {
            "field_id": field_id,
            "category_id": category_id
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            type: "POST",
            url: base_url + "ajax_controller/delete_custom_field_category",
            data: data,
            success: function (response) {
                document.getElementById("custom_fields_response").innerHTML = response;
            }
        });
    }

    //show custom field options
    function show_custom_field_options(val) {
        if (val == "dropdown" || val == "select_box") {
            $('.custom-fields-options-container').show();
        } else {
            $('.custom-fields-options-container').hide();
        }
    }

    $(document).on('click', '#btn_add_custom_field_option_session', function () {
        var language_ids = $(this).attr("data-language-ids");
        var ids_array = language_ids.split(',');
        var result = true;
        var data = {};
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        ids_array.forEach(function (item) {
            var input_name = 'option_lang_' + item;
            var input_value = $.trim($('#option_lang_' + item).val());
            if (input_value == "") {
                $('#option_lang_' + item).addClass('has-error');
                result = false;
            } else {
                $('#option_lang_' + item).removeClass('has-error');
            }
            data[input_name] = input_value;
        });
        if (result == true) {
            $.ajax({
                type: "POST",
                url: base_url + "ajax_controller/add_custom_field_option_session",
                data: data,
                success: function (response) {
                    document.getElementById("custom_field_options_response").innerHTML = response;
                }
            });
        }
    });

    $(document).on('click', '#btn_add_custom_field_option', function () {
        var field_id = $(this).attr("data-field-id");
        var language_ids = $(this).attr("data-language-ids");
        var ids_array = language_ids.split(',');
        var result = true;
        var data = {
            'field_id': field_id
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        ids_array.forEach(function (item) {
            var input_name = 'option_lang_' + item;
            var input_value = $.trim($('#option_lang_' + item).val());
            if (input_value == "") {
                $('#option_lang_' + item).addClass('has-error');
                result = false;
            } else {
                $('#option_lang_' + item).removeClass('has-error');
            }
            data[input_name] = input_value;
        });
        if (result == true) {
            $.ajax({
                type: "POST",
                url: base_url + "ajax_controller/add_custom_field_option",
                data: data,
                success: function (response) {
                    location.reload();
                }
            });
        }
    });


    //delete custom field option session
    function delete_custom_field_option_session(common_id) {
        var data = {
            "common_id": common_id
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            type: "POST",
            url: base_url + "ajax_controller/delete_custom_field_option_session",
            data: data,
            success: function (response) {
                document.getElementById("custom_field_options_response").innerHTML = response;
            }
        });
    }

    //delete custom field option
    function delete_custom_field_option(common_id) {
        var data = {
            "common_id": common_id
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            type: "POST",
            url: base_url + "ajax_controller/delete_custom_field_option",
            data: data,
            success: function (response) {
                location.reload();
            }
        });
    }
</script>