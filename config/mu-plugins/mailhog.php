<?php
/**
 * Force WP to send emails to mailhog
 *
 * Set to run last to override any possible plugins
 */
add_action('phpmailer_init', 'predicSetupMailhog', 9999999);
function predicSetupMailhog($phpmailer)
{
    $phpmailer->Host = 'mailhog';
    $phpmailer->Port = 1025;
    $phpmailer->IsSMTP();
    $phpmailer->Username = '';
    $phpmailer->Password = '';
    $phpmailer->SMTPAuth = false;
    $phpmailer->SMTPSecure = 'TLS';
}
