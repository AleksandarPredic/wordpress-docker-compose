<?php
/**
 * Force WP to send emails to mailhog
 */
add_action('phpmailer_init', 'predicSetupMailhog');
function predicSetupMailhog($phpmailer)
{
    $phpmailer->Host = 'mailhog';
    $phpmailer->Port = 1025;
    $phpmailer->IsSMTP();
}
