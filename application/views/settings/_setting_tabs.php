<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="profile-tabs">
    <ul class="nav">
        <li class="nav-item <?php echo ($active_tab == 'update_profile') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo lang_base_url(); ?>settings">
                <span><?php echo trans("update_profile"); ?></span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_tab == 'contact_informations') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo lang_base_url(); ?>settings/contact-informations">
                <span><?php echo trans("contact_informations"); ?></span>
            </a>
        </li>
        <?php if ($this->selected_system == "marketplace"): ?>
            <li class="nav-item <?php echo ($active_tab == 'shipping_address') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo lang_base_url(); ?>settings/shipping-address">
                    <span><?php echo trans("shipping_address"); ?></span>
                </a>
            </li>
        <?php endif; ?>
        <li class="nav-item <?php echo ($active_tab == 'social_media') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo lang_base_url(); ?>settings/social-media">
                <span><?php echo trans("social_media"); ?></span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_tab == 'preferences') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo lang_base_url(); ?>settings/preferences">
                <span><?php echo trans("preferences"); ?></span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_tab == 'change_password') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo lang_base_url(); ?>settings/change-password">
                <span><?php echo trans("change_password"); ?></span>
            </a>
        </li>
    </ul>
</div>