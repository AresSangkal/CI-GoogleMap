<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <!-- Wrapper -->
    <div id="wrapper">
        <div class="container">
            <div class="row">
                <div id="content" class="col-12">
                    <nav class="nav-breadcrumb" aria-label="breadcrumb">
                        <ol class="breadcrumb"></ol>
                    </nav>
                    <h1 class="page-title page-title-product"><?php echo trans("update_product"); ?></h1>
                    <div class="form-add-product">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-12 col-lg-10">
                                <div class="row">
                                    <div class="col-12">
                                        <!-- include message block -->
                                        <?php $this->load->view('product/_messages'); ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="tab-content add-product-tabs">
                                            <!-- TAB IMAGES -->
                                            <div class="tab-pane container active" id="tab_product_images">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo trans("photos"); ?></label>
                                                            <div id="product_image_response">
                                                                <?php $this->load->view("product/_image_update_box"); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="error-message error-insufficient-memory">
                                                            <p class="m-b-15">
                                                                <?php echo trans("insufficient_memory"); ?>
                                                            </p>
                                                        </div>
                                                        <div class="error-message error-message-img-upload">
                                                            <p class="m-b-15">
                                                                <?php echo trans("file_too_large") . " " . formatSizeUnits($this->img_uplaod_max_file_size); ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <a href="javascript:void(0)" id="btn_tab_product_details" class="btn btn-lg btn-custom float-right"><?php echo trans("next"); ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- TAB IMAGES END -->

                                            <!-- TAB PRODUCT DETAILS -->
                                            <div class="tab-pane container fade" id="tab_product_details">
                                                <!-- form start -->
                                                <?php echo form_open('product_controller/update_product_post', ['id' => 'form_validate', 'class' => 'validate_price', 'onkeypress' => "return event.keyCode != 13;"]); ?>
                                                <input type="hidden" name="id" class="form-control form-input" value="<?php echo $product->id; ?>">

                                                <div class="form-group">
                                                    <label class="control-label"><?php echo trans("title"); ?></label>
                                                    <input type="text" name="title" class="form-control form-input" value="<?php echo html_escape($product->title); ?>" placeholder="<?php echo trans("title"); ?>" required>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label"><?php echo trans('category'); ?></label>
                                                    <div class="selectdiv">
                                                        <select id="categories" name="category_id" class="form-control selecter" onchange="get_subcategories_by_lang(this.value, '<?php echo $selected_lang->id; ?>');" required>
                                                            <option value=""><?php echo trans('select_category'); ?></option>
                                                            <?php foreach ($parent_categories as $item): ?>
                                                                <?php if ($item->id == $product->category_id): ?>
                                                                    <option value="<?php echo html_escape($item->id); ?>" selected><?php echo html_escape($item->name); ?></option>
                                                                <?php else: ?>
                                                                    <option value="<?php echo html_escape($item->id); ?>"><?php echo html_escape($item->name); ?></option>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div id="subcategories_container">
                                                    <?php if ($product->subcategory_id != 0): ?>
                                                        <div class="form-group">
                                                            <div class="selectdiv">
                                                                <select name="subcategory_id" class="form-control selecter" onchange="get_third_categories_by_lang(this.value, '<?php echo $selected_lang->id; ?>');" required>
                                                                    <option value=""><?php echo trans('select_category'); ?></option>
                                                                    <?php foreach ($subcategories as $item): ?>
                                                                        <?php if ($item->id == $product->subcategory_id): ?>
                                                                            <option value="<?php echo html_escape($item->id); ?>" selected><?php echo html_escape($item->name); ?></option>
                                                                        <?php else: ?>
                                                                            <option value="<?php echo html_escape($item->id); ?>"><?php echo html_escape($item->name); ?></option>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <input type="hidden" name="subcategory_id" value="0">
                                                    <?php endif; ?>
                                                </div>

                                                <div id="third_categories_container">
                                                    <?php if ($product->third_category_id != 0): ?>
                                                        <div class="form-group">
                                                            <div class="selectdiv">
                                                                <select name="third_category_id" class="form-control selecter" required onchange="get_custom_fields_by_third_category(this.value);">
                                                                    <option value=""><?php echo trans('select_category'); ?></option>
                                                                    <?php foreach ($third_categories as $item): ?>
                                                                        <?php if ($item->id == $product->third_category_id): ?>
                                                                            <option value="<?php echo html_escape($item->id); ?>" selected><?php echo html_escape($item->name); ?></option>
                                                                        <?php else: ?>
                                                                            <option value="<?php echo html_escape($item->id); ?>"><?php echo html_escape($item->name); ?></option>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <input type="hidden" name="third_category_id" value="0">
                                                    <?php endif; ?>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-6 m-b-sm-15">
                                                            <label class="control-label"><?php echo trans('condition'); ?></label>
                                                            <div class="selectdiv">
                                                                <select name="product_condition" class="form-control" required>
                                                                    <option value=""><?php echo trans('select'); ?></option>
                                                                    <option value="new_with_tags" <?php echo ($product->product_condition == "new_with_tags") ? 'selected' : ''; ?>><?php echo trans('new_with_tags'); ?></option>
                                                                    <option value="new" <?php echo ($product->product_condition == "new") ? 'selected' : ''; ?>><?php echo trans('new'); ?></option>
                                                                    <option value="very_good" <?php echo ($product->product_condition == "very_good") ? 'selected' : ''; ?>><?php echo trans('very_good'); ?></option>
                                                                    <option value="good" <?php echo ($product->product_condition == "good") ? 'selected' : ''; ?>><?php echo trans('good'); ?></option>
                                                                    <option value="satisfactory" <?php echo ($product->product_condition == "satisfactory") ? 'selected' : ''; ?>><?php echo trans('satisfactory'); ?></option>
                                                                    <option value="used" <?php echo ($product->product_condition == "used") ? 'selected' : ''; ?>><?php echo trans('used'); ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6">
                                                            <label class="control-label"><?php echo trans('quantity'); ?></label>
                                                            <input type="number" name="quantity" class="form-control form-input" min="1" max="999999" value="<?php echo html_escape($product->quantity); ?>" placeholder="<?php echo trans("quantity"); ?>" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if ($general_settings->selected_system == "marketplace"): ?>
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo trans('price'); ?></label>
                                                        <div class="row">
                                                            <div class="col-12 col-sm-6 m-b-sm-15">
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text input-group-text-currency" id="basic-addon1"><?php echo get_currency($payment_settings->default_product_currency); ?></span>
                                                                        <input type="hidden" name="currency" value="<?php echo $payment_settings->default_product_currency; ?>">
                                                                    </div>
                                                                    <input type="text" name="price" id="product_price_input" aria-describedby="basic-addon1" class="form-control form-input price-input validate-price-input" value="<?php echo price_format_input($product->price); ?>" placeholder="<?php echo $this->input_initial_price; ?>" onpaste="return false;" maxlength="32" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-6">
                                                                <p class="calculated-price">
                                                                    <strong><?php echo trans("you_will_earn"); ?> (<?php echo get_currency($payment_settings->default_product_currency); ?>):&nbsp;&nbsp;
                                                                        <i id="earned_price" class="earned-price">
                                                                            <?php $earned_price = $product->price - (($product->price * $general_settings->commission_rate) / 100);
                                                                            $earned_price = number_format($earned_price, 2, '.', '');
                                                                            echo price_format_input($earned_price); ?>
                                                                        </i>
                                                                    </strong>&nbsp;&nbsp;&nbsp;
                                                                    <small> (<?php echo trans("commission_rate"); ?>:&nbsp;&nbsp;<?php echo $general_settings->commission_rate; ?>%)</small>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo trans('price'); ?></label>
                                                        <div class="row">
                                                            <?php if ($this->payment_settings->default_product_currency == "all"): ?>
                                                                <div class="col-12 col-sm-6 m-b-sm-15">
                                                                    <div class="selectdiv">
                                                                        <select name="currency" class="form-control" required>
                                                                            <?php $currencies = get_currencies();
                                                                            if (!empty($currencies)):
                                                                                foreach ($currencies as $key => $value):
                                                                                    if ($key == $product->currency):?>
                                                                                        <option value="<?php echo $key; ?>" selected><?php echo $value["name"] . " (" . $value["hex"] . ")"; ?></option>
                                                                                    <?php else: ?>
                                                                                        <option value="<?php echo $key; ?>"><?php echo $value["name"] . " (" . $value["hex"] . ")"; ?></option>
                                                                                    <?php endif; ?>
                                                                                <?php endforeach; ?>
                                                                            <?php endif; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-6 m-b-sm-15">
                                                                    <input type="text" name="price" class="form-control form-input price-input validate-price-input" value="<?php echo price_format_input($product->price); ?>" placeholder="<?php echo $this->input_initial_price; ?>" onpaste="return false;" maxlength="32" required>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="col-12 col-sm-6 m-b-sm-15">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text input-group-text-currency" id="basic-addon2"><?php echo get_currency($payment_settings->default_product_currency); ?></span>
                                                                            <input type="hidden" name="currency" value="<?php echo $payment_settings->default_product_currency; ?>">
                                                                        </div>
                                                                        <input type="text" name="price" id="product_price_input" aria-describedby="basic-addon2" class="form-control form-input price-input validate-price-input" value="<?php echo price_format_input($product->price); ?>" placeholder="<?php echo $this->input_initial_price; ?>" onpaste="return false;" maxlength="32" required>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-6 m-b-sm-15">
                                                            <label class="control-label"><?php echo trans('shipping_cost'); ?></label>
                                                            <div class="selectdiv">
                                                                <select name="shipping_cost_type" class="form-control" onchange="if(this.value=='shipping_buyer_pays'){$('.shipping-cost-container').show();}else{$('.shipping-cost-container').hide();}" required>
                                                                    <option value=""><?php echo trans("select"); ?></option>
                                                                    <option value="free_shipping" <?php echo ($product->shipping_cost_type == "free_shipping") ? 'selected' : ''; ?>><?php echo trans("free_shipping"); ?></option>
                                                                    <option value="shipping_included" <?php echo ($product->shipping_cost_type == "shipping_included") ? 'selected' : ''; ?>><?php echo trans("shipping_included"); ?></option>
                                                                    <option value="shipping_buyer_pays" <?php echo ($product->shipping_cost_type == "shipping_buyer_pays") ? 'selected' : ''; ?>><?php echo trans("shipping_buyer_pays"); ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6">
                                                            <label class="control-label"><?php echo trans('shipping_time'); ?></label>
                                                            <div class="selectdiv">
                                                                <select name="shipping_time" class="form-control" required>
                                                                    <option value=""><?php echo trans("select"); ?></option>
                                                                    <option value="1_business_day" <?php echo ($product->shipping_time == "1_business_day") ? 'selected' : ''; ?>><?php echo trans("1_business_day"); ?></option>
                                                                    <option value="2_3_business_days" <?php echo ($product->shipping_time == "2_3_business_days") ? 'selected' : ''; ?>><?php echo trans("2_3_business_days"); ?></option>
                                                                    <option value="4_7_business_days" <?php echo ($product->shipping_time == "4_7_business_days") ? 'selected' : ''; ?>><?php echo trans("4_7_business_days"); ?></option>
                                                                    <option value="8_15_business_days" <?php echo ($product->shipping_time == "8_15_business_days") ? 'selected' : ''; ?>><?php echo trans("8_15_business_days"); ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 m-t-15 shipping-cost-container" style="<?php echo ($product->shipping_cost_type == "shipping_buyer_pays") ? 'display:block;' : ''; ?>">
                                                            <label class="control-label"><?php echo trans('shipping_cost'); ?></label>
                                                            <div class="input-group">
                                                                <?php if ($this->payment_settings->default_product_currency != "all"): ?>
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text input-group-text-currency" id="basic-addon3"><?php echo get_currency($this->payment_settings->default_product_currency); ?></span>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <input type="text" name="shipping_cost" aria-describedby="basic-addon3" class="form-control form-input price-input" value="<?php echo price_format_input($product->shipping_cost); ?>" placeholder="<?php echo $this->input_initial_price; ?>" onpaste="return false;" maxlength="32" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group m-0">
                                                    <div class="row" id="custom_fields_container">
                                                        <?php if (isset($custom_field_array)) {
                                                            $this->load->view("product/_response_custom_fields", ["custom_fields" => $custom_field_array]);
                                                        } ?>
                                                    </div>
                                                </div>

                                                <?php if ($general_settings->selected_system == "classified_ads"): ?>
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo trans("external_link"); ?>&nbsp;<small>(<?php echo trans("buy_button_link"); ?>)</small>
                                                        </label>
                                                        <input type="text" name="external_link" class="form-control form-input" value="<?php echo html_escape($product->external_link); ?>" placeholder="<?php echo trans("external_link"); ?>">
                                                    </div>
                                                <?php else: ?>
                                                    <input type="hidden" name="external_link" class="form-control form-input" value="<?php echo html_escape($product->external_link); ?>">
                                                <?php endif; ?>

                                                <?php if (is_admin()): ?>
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo trans('visibility'); ?></label>
                                                        <div class="selectdiv">
                                                            <select name="visibility" class="form-control" required>
                                                                <option value="1" <?php echo ($product->visibility == 1) ? 'selected' : ''; ?>><?php echo trans('visible'); ?></option>
                                                                <option value="0" <?php echo ($product->visibility == 0) ? 'selected' : ''; ?>><?php echo trans('hidden'); ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <input type="hidden" name="visibility" value="<?php echo $product->visibility; ?>">
                                                <?php endif; ?>

                                                <div class="form-group">
                                                    <label class="control-label"><?php echo trans('status'); ?></label>
                                                    <div class="selectdiv">
                                                        <select name="status_sold" class="form-control" required>
                                                            <option value="active" <?php echo ($product->is_sold == 0) ? 'selected' : ''; ?>><?php echo trans('active'); ?></option>
                                                            <option value="sold" <?php echo ($product->is_sold == 1) ? 'selected' : ''; ?>><?php echo trans('sold'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label"><?php echo trans('description'); ?></label>
                                                    <textarea name="description" id="ckEditor" class="text-editor"><?php echo $product->description; ?></textarea>
                                                </div>


                                                <?php if ($general_settings->product_location_system == 1): ?>
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo trans('location'); ?></label>
                                                        <div class="row">
                                                            <div class="col-12 col-sm-6 m-b-15">
                                                                <?php if ($general_settings->default_product_location == 0): ?>
                                                                    <div class="selectdiv">
                                                                        <select id="countries" name="country_id" class="form-control" onchange="get_states(this.value);" required>
                                                                            <option value=""><?php echo trans('country'); ?></option>
                                                                            <?php foreach ($countries as $item): ?>
                                                                                <option value="<?php echo $item->id; ?>" <?php echo ($item->id == $product->country_id) ? 'selected' : ''; ?>><?php echo html_escape($item->name); ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="selectdiv">
                                                                        <select id="countries" name="country_id" class="form-control" required>
                                                                            <?php foreach ($countries as $item): ?>
                                                                                <?php if ($item->id == $general_settings->default_product_location): ?>
                                                                                    <option value="<?php echo $item->id; ?>" selected><?php echo html_escape($item->name); ?></option>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-12 col-sm-6 m-b-15">
                                                                <div class="selectdiv">
                                                                    <select id="states" name="state_id" class="form-control" onchange="update_product_map();" required>
                                                                        <option value=""><?php echo trans('state_city'); ?></option>
                                                                        <?php
                                                                        if (!empty($states)):
                                                                            foreach ($states as $item): ?>
                                                                                <option value="<?php echo $item->id; ?>" <?php echo ($item->id == $product->state_id) ? 'selected' : ''; ?>><?php echo html_escape($item->name); ?></option>
                                                                            <?php endforeach;
                                                                        endif; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-12 col-sm-6 m-b-sm-15">
                                                                <input type="text" name="address" id="address_input" class="form-control form-input" value="<?php echo html_escape($product->address); ?>" placeholder="<?php echo trans("address") ?>">
                                                            </div>

                                                            <div class="col-12 col-sm-3">
                                                                <input type="text" name="zip_code" id="zip_code_input" class="form-control form-input" value="<?php echo html_escape($product->zip_code); ?>" placeholder="<?php echo trans("zip_code") ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div id="map-result">
                                                            <!--load map-->
                                                            <?php $this->load->view("product/_load_map", ["map_address" => get_location($product)]); ?>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <input type="hidden" name="country_id" value="<?php echo $product->country_id; ?>">
                                                    <input type="hidden" name="state_id" value=<?php echo $product->state_id; ?>>
                                                <?php endif; ?>

                                                <div class="form-group m-t-15">
                                                    <a href="javascript:void(0)" id="btn_tab_product_images" class="btn btn-lg btn-custom float-left"><?php echo trans("back"); ?></a>
                                                    <button type="submit" class="btn btn-lg btn-custom float-right"><?php echo trans("save_changes"); ?></button>
                                                </div>
                                                <?php echo form_close(); ?><!-- form end -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Wrapper End-->
    <!-- Ckeditor js -->
    <script src="<?php echo base_url(); ?>assets/vendor/ckeditor/ckeditor.js"></script>
    <!-- Ckeditor -->
    <script>
        var ckEditor = document.getElementById('ckEditor');

        if (ckEditor != undefined && ckEditor != null) {
            CKEDITOR.replace('ckEditor', {
                language: 'en',
                removeButtons: 'Source,Flash,Table,Smiley,SpecialChar,Styles',
            });
        }

        CKEDITOR.on('dialogDefinition', function (ev) {
                var editor = ev.editor;
                var dialogDefinition = ev.data.definition;

                // This function will be called when the user will pick a file in file manager
                var cleanUpFuncRef = CKEDITOR.tools.addFunction(function (a) {
                    $('#ck_file_manager').modal('hide');
                    CKEDITOR.tools.callFunction(1, a, "");
                });
                var tabCount = dialogDefinition.contents.length;
                for (var i = 0; i < tabCount; i++) {
                    var browseButton = dialogDefinition.contents[i].get('browse');
                    if (browseButton !== null) {
                        browseButton.onClick = function (dialog, i) {
                            editor._.filebrowserSe = this;
                            var iframe = $('#ck_file_manager').find('iframe').attr({
                                src: editor.config.filebrowserBrowseUrl + '&CKEditor=body&CKEditorFuncNum=' + cleanUpFuncRef + '&langCode=en'
                            });
                            $('#ck_file_manager').appendTo('body').modal('show');
                        }
                    }
                }
            }
        );
    </script>

<?php if ($this->selected_system == "marketplace"): ?>
    <script>
        //calculate product earned value
        var thousands_separator = '<?php echo $this->thousands_separator; ?>';
        var commission_rate = '<?php echo $this->general_settings->commission_rate; ?>';
        $(document).on("input keyup paste change", "#product_price_input", function () {
            var input_val = $(this).val();
            input_val = input_val.replace(',', '.');
            var price = parseFloat(input_val);
            commission_rate = parseInt(commission_rate);
            //calculate
            if (!Number.isNaN(price)) {
                var earned_price = price - ((price * commission_rate) / 100);
                earned_price = earned_price.toFixed(2);
                if (thousands_separator == ',') {
                    earned_price = earned_price.replace('.', ',');
                }
            } else {
                earned_price = '0' + thousands_separator + '00';
            }
            $("#earned_price").html(earned_price);
        });
    </script>
<?php endif; ?>