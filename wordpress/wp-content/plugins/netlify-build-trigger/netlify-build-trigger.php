<?php
/**
 * Plugin Name: Netlify Build Trigger (RenovaLink)
 * Description: Dispara un build de Netlify cuando se publica o actualiza un servicio o proyecto.
 * Version: 1.0.0
 * Author: RenovaLink Dev Automation
 */

if (!defined('ABSPATH')) { exit; }

// URL del Build Hook de Netlify (proporcionada por el usuario)
if (!defined('RENOVALINK_NETLIFY_BUILD_HOOK')) {
    define('RENOVALINK_NETLIFY_BUILD_HOOK', 'https://api.netlify.com/build_hooks/68dc32c2462086a77888dafe');
}

// CPTs / post types a monitorear (incluye ahora 'page'). Se hace filtrable.
function rl_get_watched_post_types() {
    $default = [ 'proyectos', 'servicios', 'page' ];
    return apply_filters('renovalink_netlify_watched_post_types', $default);
}

// Tiempo mínimo entre builds (segundos) para evitar spam accidental
const RENOVALINK_BUILD_THROTTLE_SECONDS = 60; // 1 minuto

/**
 * Determina si se debe disparar el build
 */
function rl_should_trigger_netlify_build($post_id, $post, $update) {
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return false;
    }

    $watched = rl_get_watched_post_types();
    if (!in_array($post->post_type, $watched, true)) {
        return false;
    }

    // Solo cuando está publicado
    if ($post->post_status !== 'publish') {
        return false;
    }

    return true;
}

// Permite desactivar el modo automático definiendo en wp-config.php:
// define('RENOVALINK_NETLIFY_MANUAL_ONLY', true);
if (!defined('RENOVALINK_NETLIFY_MANUAL_ONLY')) {
    define('RENOVALINK_NETLIFY_MANUAL_ONLY', false);
}

function rl_fire_netlify_build($context = 'manual', $extra = []) {
    // Throttle si viene de auto (para manual NO aplicamos throttle severo salvo que quieras cambiarlo)
    $is_auto = ($context === 'auto');
    $now = time();
    if ($is_auto) {
        $last = get_option('rl_last_build_trigger_time');
        if ($last && ($now - (int)$last) < RENOVALINK_BUILD_THROTTLE_SECONDS) {
            return false;
        }
    }

    $body = array_merge([
        'trigger_title' => 'Manual Netlify build (' . $context . ')',
        'source'        => 'wordpress-netlify-plugin',
        'timestamp'     => $now,
    ], $extra);

    $response = wp_remote_post(RENOVALINK_NETLIFY_BUILD_HOOK, [
        'timeout'  => 8,
        'blocking' => false,
        'body'     => $body,
    ]);

    // Persist last trigger meta
    update_option('rl_last_build_trigger_time', $now);
    update_option('rl_last_build_trigger_context', $context);

    if (is_wp_error($response)) {
        error_log('[Netlify Build Trigger] Error: ' . $response->get_error_message());
        update_option('rl_last_build_trigger_status', 'error');
        return false;
    }
    error_log('[Netlify Build Trigger] Build disparado (' . $context . ')');
    update_option('rl_last_build_trigger_status', 'ok');
    return true;
}

function rl_trigger_netlify_build_auto($post_id, $post, $update) {
    if (RENOVALINK_NETLIFY_MANUAL_ONLY) return; // desactivado modo auto
    if (!rl_should_trigger_netlify_build($post_id, $post, $update)) return;
    rl_fire_netlify_build('auto', [
        'post_type' => $post->post_type,
        'post_id'   => $post_id,
        'slug'      => $post->post_name,
    ]);
}
add_action('save_post', 'rl_trigger_netlify_build_auto', 10, 3);

/**
 * Añade una notificación en el admin después de guardar
 */
function rl_admin_notices() {
    if (!current_user_can('edit_posts')) return;

    $last = get_option('rl_last_build_trigger_time');
    if (!$last) return;

    $seconds_since = time() - (int)$last;
    if ($seconds_since < 120) { // Mostrar durante 2 minutos
        $status = get_option('rl_last_build_trigger_status') === 'ok' ? 'success' : 'error';
        $context = get_option('rl_last_build_trigger_context') ?: 'manual';
        $msg = $status === 'success'
            ? 'Build en Netlify disparado (' . esc_html($context) . ').'
            : 'Error al disparar build Netlify.';
        echo '<div class="notice notice-' . esc_attr($status) . ' is-dismissible"><p>' . $msg . ' (hace ' . esc_html($seconds_since) . 's)</p></div>';
    }
}
add_action('admin_notices', 'rl_admin_notices');

/** Helper: formatea diferencia de tiempo en lenguaje humano **/
function rl_netlify_human_diff($from, $to = null) {
    $to = $to ?: time();
    $diff = max(0, $to - (int)$from);
    if ($diff < 60) return $diff . 's';
    if ($diff < 3600) return floor($diff/60) . 'm';
    if ($diff < 86400) return floor($diff/3600) . 'h';
    return floor($diff/86400) . 'd';
}

/**
 * Página de ajustes simple (opcional futuro)
 */
function rl_netlify_trigger_admin_menu() {
    add_options_page(
        'Netlify Build Trigger',
        'Netlify Build Trigger',
        'manage_options',
        'rl-netlify-trigger',
        'rl_netlify_trigger_settings_page'
    );
}
add_action('admin_menu', 'rl_netlify_trigger_admin_menu');

function rl_netlify_trigger_settings_page() {
    if (!current_user_can('manage_options')) return;

    // Botón manual
    if (isset($_POST['rl_manual_build']) && check_admin_referer('rl_manual_build_action', 'rl_manual_build_nonce')) {
        $ok = rl_fire_netlify_build('manual');
        echo '<div class="notice '.($ok ? 'notice-success' : 'notice-error').' is-dismissible"><p>'
          . ($ok ? 'Build manual disparado correctamente.' : 'Error al disparar build, revisa logs.')
          . '</p></div>';
    }

    $last = get_option('rl_last_build_trigger_time');
    $last_status = get_option('rl_last_build_trigger_status');
    $last_context = get_option('rl_last_build_trigger_context');
    $last_html = $last ? ( 'Hace ' . esc_html( rl_netlify_human_diff($last) ) . ' (' . date_i18n('Y-m-d H:i:s', $last) . ')' ) : '—';
    $badge = $last_status ? ('<span style="display:inline-block;padding:2px 6px;border-radius:4px;background:' . ($last_status==='ok'?'#2d7a2d':'#8a1f11') . ';color:#fff;font-size:11px;vertical-align:middle;">' . esc_html($last_status) . '</span>') : '';

    echo '<div class="wrap"><h1>Netlify Build Trigger</h1>';
    echo '<table class="widefat striped" style="max-width:820px;margin-top:1em;">';
    echo '<tbody>';
    echo '<tr><th style="width:220px;">Hook</th><td><code>' . esc_html(RENOVALINK_NETLIFY_BUILD_HOOK) . '</code></td></tr>';
    echo '<tr><th>Tipos monitoreados</th><td><code>' . implode(', ', rl_get_watched_post_types()) . '</code></td></tr>';
    echo '<tr><th>Throttle automático</th><td>' . RENOVALINK_BUILD_THROTTLE_SECONDS . 's</td></tr>';
    echo '<tr><th>Modo actual</th><td>' . (RENOVALINK_NETLIFY_MANUAL_ONLY ? 'Manual (no se dispara en save_post)' : 'Automático + Manual') . '</td></tr>';
    echo '<tr><th>Último build</th><td>' . $last_html . ($last_context? ' — contexto: ' . esc_html($last_context):'') . ' ' . $badge . '</td></tr>';
    echo '</tbody></table>';
    echo '<h2 style="margin-top:1.5em;">Disparo Manual</h2>';
    echo '<form method="post">';
    wp_nonce_field('rl_manual_build_action', 'rl_manual_build_nonce');
    echo '<p><button type="submit" name="rl_manual_build" class="button button-primary">Disparar build ahora</button></p>';
    echo '</form>';
    echo '<p style="margin-top:1em;">Para usar solo modo manual añade en <code>wp-config.php</code>:<br><code>define(\'RENOVALINK_NETLIFY_MANUAL_ONLY\', true);</code></p>';
    echo '</div>';
}

/** Botón rápido en la barra de admin **/
function rl_netlify_adminbar_button($wp_admin_bar) {
    if (!current_user_can('manage_options')) return;
    $wp_admin_bar->add_node([
        'id'    => 'rl-netlify-build-now',
        'title' => 'Netlify Build',
        'href'  => '#',
        'meta'  => [ 'onclick' => "(function(){var n=this; n.textContent='Netlify…';fetch('".esc_js(RENOVALINK_NETLIFY_BUILD_HOOK)."',{method:'POST'}).then(()=>{n.textContent='Build ✓'; setTimeout(()=>{n.textContent='Netlify Build';},4000);}).catch(()=>{n.textContent='Error'; setTimeout(()=>{n.textContent='Netlify Build';},4000);});})(); return false;" ]
    ]);
}
add_action('admin_bar_menu', 'rl_netlify_adminbar_button', 100);
