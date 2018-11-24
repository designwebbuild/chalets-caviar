<?php

/**
 * Class Es_Taxonomy
 */
class Es_Taxonomy
{
    /**
     * @var
     */
    protected $_taxonomy_name;

    /**
     * Es_Taxonomy constructor.
     * @param $taxonomy
     */
    public function __construct( $taxonomy )
    {
        $this->_taxonomy_name = $taxonomy;
    }

    /**
     * Return taxonomy object.
     *
     * @return false|object|stdClass
     */
    public function get()
    {
        return get_taxonomy( $this->_taxonomy_name );
    }

    /**
     * Return list of default capabilities for taxonomy.
     *
     * @return mixed|void
     */
    public function get_default_capabilities()
    {
        return apply_filters( 'es_get_default_capabilities', array(
            'manage_terms' => 'es_manage_' . $this->_taxonomy_name,
            'edit_terms'   => 'es_edit_' . $this->_taxonomy_name,
            'delete_terms' => 'es_delete_' . $this->_taxonomy_name,
            'assign_terms' => 'es_assing_' . $this->_taxonomy_name,
        ), $this->_taxonomy_name );
    }

    /**
     * Return capability name.
     *
     * @param $cap
     *    - manage_terms
     *    - edit_terms
     *    - delete_terms
     *    - assign_terms
     * @param null $default
     *    Default value for return.
     * @return null|string
     */
    public function get_capability_name( $cap, $default = null ) {
        $caps = $this->get_default_capabilities();

        return ! empty( $caps[ $cap ] ) ? $caps[ $cap ] : $default;
    }

    /**
     * Return list of terms of the taxonomy.
     *
     * @return array|int|WP_Error|WP_Term[]
     */
    public function get_terms()
    {
        return get_terms( $this->get()->name, array( 'hide_empty' => false ) );
    }

    /**
     * Return plugin taxonomy names list.
     *
     * @return mixed|void
     */
    public static function get_taxonomies_list()
    {
        return apply_filters( 'es_get_taxonomies_list', array(
            'es_category', 'es_status', 'es_type', 'es_feature', 'es_rent_period', 'es_amenities', 'es_labels',
        ) );
    }
}
