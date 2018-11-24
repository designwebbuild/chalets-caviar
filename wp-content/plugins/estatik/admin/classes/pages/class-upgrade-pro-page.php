<?php

/**
 * Class Es_Upgrade_Pro_Page
 */
class Es_Upgrade_Pro_Page extends Es_Object
{
    /**
     * @return void
     */
    public function actions()
    {
        add_action( 'init', array( $this, 'upgrade' ) );

        if ( ! empty( $_GET['install'] ) ) {
            add_action( 'init', array( $this, 'start_reinstall' ) );
        }
    }

    public function start_reinstall()
    {
        Estatik::install();
        wp_redirect( admin_url( 'admin.php?page=es_dashboard' ) );
        die;
    }

    /**
     * Render upgrade to pro page.
     *
     * @return void
     */
    public static function render()
    {
        include_once( ES_ADMIN_TEMPLATES . 'upgrade-pro/upgrade-pro.php' );
    }

    /**
     * Upgrade estatik handle.
     */
    public function upgrade()
    {
        if ( isset( $_POST['es_upgrade_pro'] ) && wp_verify_nonce($_POST['es_upgrade_pro'], 'es_upgrade_pro' ) && ! empty( $_FILES['file'] ) ) {
            $upgrader = new Estatik_Upgrader( $_FILES['file'], new Es_Messenger( 'es_message' ) );
            $upgrader->run();
        }
    }
}
