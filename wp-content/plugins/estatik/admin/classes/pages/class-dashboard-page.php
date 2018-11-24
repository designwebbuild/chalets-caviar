<?php

/**
 * Class Es_Dashboard_Page
 */
class Es_Dashboard_Page extends Es_Object
{
    /**
     * Add actions for dashboard page.
     */
    public function actions()
    {
        add_action( 'admin_enqueue_scripts', array( $this , 'enqueue_styles' ) );
    }

    /**
     * Enqueue styles for dashboard page.
     *
     * @return void
     */
    public function enqueue_styles()
    {
        $vendor = 'admin/assets/css/vendor/';

        wp_register_style( 'es-scroll-style', ES_PLUGIN_URL . $vendor . 'jquery.mCustomScrollbar.css' );
        wp_enqueue_style( 'es-scroll-style' );

        wp_register_style( 'es-admin-slick-style', ES_PLUGIN_URL . $vendor . 'slick.css' );
        wp_register_style( 'es-admin-slick-theme-style', ES_PLUGIN_URL . $vendor . 'slick-theme.css' );

        wp_enqueue_style( 'es-admin-slick-style' );
        wp_enqueue_style( 'es-admin-slick-theme-style' );
    }

    /**
     * Render dashboard page.
     *
     * @return void
     */
    public static function render()
    {
        $template = apply_filters( 'es_dashboard_page_template_path', ES_ADMIN_TEMPLATES . '/dashboard/dashboard.php' );

        if ( file_exists( $template ) ) {
            include_once( $template );
        }
    }

    /**
     * Return shortcodes list for dashboard page.
     *
     * @return array
     */
    public static function get_shortcodes_list()
    {
        return apply_filters( 'es_get_shortcodes_list', array(
            '[es_my_listing layout="list | 3_col | 2_col"]',
            '[es_my_listing sort="recent | highest_price | lowest_price | most_popular"] ',
            '[es_my_listing  prop_id="1,2,5,6...n"]',
            '[es_my_listing category="category name"]',
            '[es_my_listing status="status name"]',
            '[es_my_listing type="type name"]',
            '[es_my_listing rent_period="rent period 1"]',
            '[es_property_map show="all"] (PRO)',
            '[es_property_map type="your type"] (PRO)',
            '[es_property_map category="your category"] (PRO)',
            '[es_property_map status="your status"] (PRO)',
            '[es_property_map rent_period="your period"] (PRO)',
            '[es_property_map limit=20] (PRO)',
            '[es_property_map ids="1,2,3,4,5"] (PRO)',
            '[es_property_map address="your address string"] (PRO)',
            '[es_property_slideshow] (PRO)',
            '[es_city city="city name"] (PRO)',
            '[es_state state="state name"] (PRO)',
            '[es_country country="country name"] (PRO)',
            '[es_labels label="label_name"] (PRO)',
            '[es_features feature="a,b,c,d"] (PRO)',
            '[es_featured_props] ',
            '[es_latest_props] ',
            '[es_cheapest_props]',
            '[es_agents] (PRO)',
            '[es_listing_agent name="agent username"] (PRO)',
            '[es_subscription_table] (PRO)',
            '[es_register] (PRO)',
            '[es_login] (PRO)',
            '[es_prop_management] (PRO)',
            '[es_reset_pwd] (PRO)',
        ) );
    }

    /**
     * Return changelog array for dashboard page.
     *
     * @return mixed
     */
    public static function get_changelog_list()
    {
        return apply_filters( 'es_get_changelog_list', array(
        	__( '3.7.2 (October, 03, 2018)', 'es-plugin' ) => '<ul>
			    <li>Excluded Google maps geolocation library</li>
			    <li>Address components DM fix</li>
			    <li>Added single page class instance as global variable</li>
			    <li>Property Image counter notice fix</li>
			    <li>Enabled wishlist feature by default</li>
			    <li>Fixed widget display type notice</li>
			    <li>User login fix</li>
			    <li>Fixed main theme color for plugin buttons</li>
			    <li>minor fixes</li>
			</ul>',

	        __( '3.7.1 (September, 14, 2018)', 'es-plugin' ) => '<ul>
			    <li>Bug with featured image fixed</li>
			    <li>Option to disable wishlist added</li>
			    <li>Search fields selected option error fixed</li>
			    <li>Plugin color settings fixed</li>
			    <li>Saved search feature saving process fix added</li>
			    <li>minor fixes</li>
			</ul>',

	        __( '3.7.0 (September, 7, 2018)', 'es-plugin' ) => '<ul>
			    <li>NEW: Wishlist added</li>
			    <li>NEW: Saved searches added</li>
			    <li>NEW: User profiles added</li>
			    <li>NEW: Ligthbox for main image added</li>
			    <li>NEW: Slideshow widget added & shortcode added</li>
			    <li>NEW: Fields Builder competely unlocked</li>
			    <li>NEW: Labels editing in Data Manager added</li>
			    <li>NEW: Remove option for featured image added</li>
			    <li>NEW: Agents profiles added (PRO & Premium)</li>
			    <li>NEW: Emails notifications about matching searches added (PRO & Premium)</li>
			    <li>Fix: Multiselect feature for Select type in FB added</li>
			    <li>Fix: Integrated WP HTTP Api instead of file_get_contents function</li>
			    <li>Fix: Address import fix (Premium)</li>
			    <li>minor fixes</li>
			</ul>'

        ,__( '3.6.6 (August, 14, 2018)', 'es-plugin' ) => '<ul>
			    <li>Images q-ty to listings added (admin area)</li>
			    <li>Bug with File type in FB fixed</li>
			    <li>Save Settings bug fixed</li>
			    <li>Multiselect feature for ShelterZoom category field added (PRO & Premium)</li>
			    <li>Fixed image size for fb sharing</li>
			    <li>Images import via URLs (for Matrix system) (Premium)</li>
			    <li>Filter by MLS # in admin panel added (Premium)</li>
			    <li>Added Deleted value column in Synchronization panel (Premium)</li>
			
			    <li>minor fixes</li>
			</ul>',

	        __( '3.6.5 (July, 6, 2018)', 'es-plugin' ) => '<ul>
                <li>Fields Builder perfomance optimized</li>
                <li>Empty search results for shortcodes with es_city, es_country & es_my_listings address fixed</li>
                <li>Fixed render of FB fields with the same names</li>
                <li>Option to remove Cities in Data Manager added</li>
                <li>Custom Date type field bug fixed</li>
                <li>Price format fixes</li>
                <li>Synchronizator fixes (Premium)</li>
                <li>Duplicated RETS images prefix fix (Premium)</li>

                <li>other fixes</li>
            </ul>',

            __( '3.6.4 (May 29, 2018)', 'es-plugin' ) => '<ul>
                <li>GDPR compliance implemented (Checkboxes to Request and Register forms added)</li>
                <li>New Agent Permissions added (PRO)</li>
                <li>View values RETS entities popup bug fixed (Premium)</li>
                <li>Rooms config implemented (Premium)</li>
                <li>MLS Update Date field fix (Premium)</li>
                <li>RETS Synchronizator search algorithm changed</li>
                <li>RETS Synchronizator updater fixed</li>
                <li>RETS Synchronizator fixed duplicated entities bug</li>
                <li>other fixes</li>
            </ul>',

            __( '3.6.3 (May 05, 2018)', 'es-plugin' ) => '<ul>
                <li>Thumb images in gallery centering fixed</li>
                <li>html entities into wp_editor on front-end management decoded (PRO & Premium)</li>
                <li>PDF issue fixed</li>
                <li>Area and lot size fields steps fixed</li>
                <li>Edit property link added to admin bar</li>
                <li>Added option to override templates in child theme: <ul>
                        <li>- estatik/property/gallery.php</li>
                        <li>- estatik/property/tabs.php</li>
                        <li>- estatik/property/share.php</li>
                        <li>- estatik/content-single.php</li>
                        <li>- estatik/archive-properties.php</li>
                        <li>- estatik/content-archive.php</li>
                        <li>- estatik/shortcodes/register.php</li>
                    </ul>
                </li>
                <li>Bug with custom fields assignment in FB (PRO & Premium)</li>
                <li>Fixed issue with images import duplication (Premium)</li>
                <li>Entitiy RETS data popup implemented (Premium)</li>
                <li>Title import fixed (Premium)</li>
                <li>Fields order in Config fixed (Premium)</li>
                <li>Bug with custom fields and sections display n frontend management fixed (PRO and Premium)</li>
                <li>Fixed template for es_feature taxonomy</li>
            </ul>',

            __( '3.6.2 (March 23, 2018)', 'es-plugin' ) => '<ul>
                <li>Multiselect for filterable fields in Import/update settings added (Premium)</li>
                <li>RETS profile active now sign added (Premium)</li>
                <li>Filter by RETS user in Listings in admin area added (Premium)</li>
                <li>FB sections to admin area added (Premium)</li>
                <li>Map view optimization (Premium)</li>
                <li>Bulk removal for import/update schedules added (Premium)</li>
                <li>Google Address validation option added (Premium)</li>
                <li>Map view optimization (PRO and Premium)</li>
                <li>other fixes</li>
            </ul>',

            __( '3.6.1 (March 12, 2018)', 'es-plugin' ) => '<ul>
                <li>Front management css styles for attachment fields fixed</li>
                <li>Attachment link removal for the es_prop_management shortcode fixed</li>
                <li>Fixed uploading files process for FB fields on front management page</li>
                <li>Grecaptcha log error fixed. Request form duplicated emails issue fixed</li>
                <li>Added extra step 0.01 to number field type in FB</li>
                <li>Dismiss option for admin messages added</li>
                <li>Big size image for center single property layout added</li>
                <li>other fixes</li>
            </ul>',

            __( '3.6.0 (February 10, 2018)', 'es-plugin' ) => '<ul>
                <li>Responive layout for es_search_form shortcode fixed</li>
                <li>Dimensions & Currencies removal option deleted</li>
                <li>Added option for restore basic fields in FB</li>
                <li>Added color settings fix to support new Estatik Project theme</li>
                <li>ShelterZoom widget support added (PRO & Premium)</li>
                <li>Agent drop-down bug fixed in search form (PRO & Premium)</li>
                <li>minor fixes</li>
            </ul>',

            __( '3.5.0 (January 25, 2018)', 'es-plugin' ) => '<ul>
                <li>New feature - Color settings added</li>
                <li>Extra price format is added (99,999)</li>
                <li>Pagination fixed (PRO)</li>
                <li>White label added (PRO & Premium)</li>
                <li>Share buttons fixed (PRO & Premium)</li>
                <li>Map view on single property page fixed (PRO & Premium)</li>
                <li>Zoom option added (PRO & Premium)</li>
                <li>other fixes</li>
            </ul>',

            __( '3.4.0 (January 10, 2018)', 'es-plugin' ) => '<ul>
                <li>Search by multicategories issue fixed</li>
                <li>New shortcode for search results page added - [es_search]</li>
                <li>Zoom in/out option for map view added (PRO & Premium)</li>
                <li>New map search feature added - [es_search_map] (PRO & Premium)</li>
                <li>Settings tabs fixed in frontend management (PRO & Premium)</li>
                <li>Subscription success page fixed (PRO & Premium)</li>
                <li>Synchronizer bugs fixes (Premium)</li>
                <li>Lookup fields in synchronizer fixes (Premium)</li>
                <li>Social sharing icons bug fixed</li>
                <li>other fixes</li>
            </ul>',

            __( '3.3.7 (December 29, 2017)', 'es-plugin' ) => '<ul>
                <li>Demo listings and pages setup added (Simple)</li>
                <li>Request form widget added to Simple (Simple)</li>
                <li>Hide option for Name and Phone fields in Request widget added</li>
                <li>Email text edit option in Request form added</li>
                <li>other fixes</li>
            </ul>',

	        __( '3.3.6 (December 13, 2017)', 'es-plugin' ) => '<ul>
                <li>Search by price field added</li>
                <li>Bug with fields removal fixed (PRO & Premium)</li>
                <li>New shortcode for single property page added [es_single id=\'ID\']</li>
                <li>Language files updated</li>
                <li>Changed labels for dimensions</li>
                <li>Added fix for AccessPress Lite theme support</li>
                <li>Property q-ty field autogeneration (PRO & Premium)</li>
                <li>Empty sections fixed</li>
                <li>Slick slider nav arrows fixed</li>
                <li>minor fixes</li>
            </ul>',

            __( '3.3.5 (November 15, 2017)', 'es-plugin' ) => '<ul>
                <li>AJAX address search with auto-suggest feature implemented</li>
                <li>Conflict with SiteOringin builder fixed</li>
                <li>PayPal pwd field type changed</li>
                <li>New Price note field added</li>
                <li>Option to remove default fields added</li>
                <li>reCaptcha added Register form (PRO & Premium version)</li>
                <li>Add new field in frontend management bug fixed (PRO & Premium version)</li>
                <li>Profiles added for multiple RETS accounts (Premium version)</li>
                <li>minor fixes</li>
            </ul>',

			__( '3.3.4 (November 1, 2017)', 'es-plugin' ) => '<ul>
                <li>Publish/Unpublish agents fix</li>
                <li>Characters issue in PDF fixed</li>
                <li>Order issue in Fields Builder fixed</li>
                <li>Address removal fix for listings deleted</li>
                <li>Search by price issue fixed</li>
                <li>reCAPTCHA integrated</li>
                <li>CSV import issues fixed</li>
                <li>Listings images removal for listings deleted</li>
                <li>Reset button in search fixed</li>
                <li>Added popup open zoom effect</li>
                <li>Actualization feature added (Estatik Premium)</li>
                <li>Polish language files added</li>
                <li>Italian language files updated</li>
                <li>minor fixes</li>
            </ul>',

            __( '3.3.3 (October 9, 2017)', 'es-plugin' ) => '<ul>
                <li>Fields builder bug with repeated fields fixed</li>
                <li>Scroll bar to drop-down fields in search added</li>
                <li>Images repeatition bug in lightbox gallery fixed</li>
                <li>Description text output in list view added</li>
                <li>Romanian language files added</li>
            </ul>',

            __( '3.3.2 (August 11, 2017)', 'es-plugin' ) => '<ul>
                <li>Alignment issue with fields in frontend fixed</li>
                <li>some minor fixes</li>
            </ul>',

            __( '3.3.1 (July 21, 2017)', 'es-plugin' ) => '<ul>
                <li>Price issue is fixed</li>
            </ul>',

            __( '3.3.0 (Juy 17, 2017)', 'es-plugin' ) => '<ul>
                <li>Fields builder added</li>
                <li>Currency manager added</li>
                <li>Search shortcode added [es_search]</li>
                <li>hu_HU language translation files added</li>
                <li>PDF brochure with Logo upload option updated (PRO)</li>
                <li>other minor fixes</li>
            </ul>',

            __( '3.2.1 (July 8, 2017)', 'es-plugin' ) => '<ul>
                <li>Search shortcode added</li>
                <li>New currenies are added (COP, Thai Baht, Turkish Lira, Hungarin Forint)</li>
                <li>PDF brochure updated (PRO)</li>
                <li>Labels icons fixed (PRO)</li>
                <li>hu_HU language files added</li>
                <li>minor fixes</li>
            </ul>',
          __( '3.2.0 (June, 17, 2017)', 'es-plugin' ) => '<ul>
                <li>WPML support added</li>
                <li>New currencies (Rp, AED, ZAR) added</li>
                <li>Drag & drop feature for new fields added (PRO & Premium)</li>
                <li>Powered by link fixed</li>
                <li>Remove icons in listings if empty values</li>
                <li>minor fixes</li>
            </ul>',

            __( '3.1.0 (MAY, 24, 2017)', 'es-plugin' ) => '<ul>
                <li>Frontend management added back</li>
                <li>Pagination bug fixed</li>
                <li>Some texts edited</li>
                <li>Display of excerpt text in subscriptions fixed</li>
                <li>Show/hide Title removed</li>
                <li>Extra layouts settings added</li>
                <li>Sharing via Facebook, Twitter, LinkedIn added</li>
                <li>Currency symbols issue fixed</li>
            </ul>',

            __( '3.0.2 (May, 10, 2017)', 'es-plugin' ) => '<ul>
                <li>Area field fixed</li>
                <li>Search widget enhanced by search by tags</li>
                <li>Bug with copying fields fixed (PRO)</li>
                <li>Hide title option removed from Settings</li>
                <li>Spanish and Russian language files updated</li>
                <li>Shortcode [es_my_listing category="for rent" posts_per_page="3" show_filter=0] fixed</li>
                <li>Bug with agents registration fixed (PRO)</li>
            </ul>',

            __( '3.0.1 (APRIL, 18, 2017)', 'es-plugin' ) => '<ul>
                <li>New currencies added: ₹ (INR), ¥ (JPY), Fr. (CHF), ₱ (PHP)</li>
                <li>Fixed some styles in description html text box</li>
                <li>Migration from ver. 2.4.0 optimized</li>
                <li>Optimized image styles</li>
            </ul>',

            __( '3.0.0 (April, 12, 2017)', 'es-plugin' ) => '<ul>
                <li>Property became WP_Post entity</li>
                <li>Images upload via WP Media only</li>
                <li>Numerous new shortcodes added</li>
                <li>Search with drag & drop feature improved</li>
                <li>Archive page created, can be customized using wp hooks</li>
                <li>Pagination improved</li>
                <li>Google Map improved, option to add address with lat/lng fields added (PRO only)</li>
                <li>Labels became editable (PRO only)</li>
                <li>CSV Import improved, images import via link added (PRO only)</li>
                <li>Subscriptions: recurring payments added (PRO only)</li>
                <li>Frontend management replaced by limited admin area (PRO only)</li>
                <li>Admin logo upload added (PRO only)</li>
                <li>Other fixes..</li>
            </ul>',

            __( '2.4.0 (September 26, 2016)', 'es-plugin' ) => '<ul>
                <li>Issue with Upgrade to Pro option fixed</li>
            </ul>',

            __( '2.3.1 (August 21, 2016)', 'es-plugin' ) => '<ul>
                <li>Arbitrary file upload vulnerability fixed</li>
            </ul>',

            __( '2.3.0 (August 15, 2016)', 'es-plugin' ) => '<ul>
                <li>File upload vulnerability fixed</li>
                <li>Review and removal of session_start() and ob_start()</li>
                <li>MAP API issue fixed</li>
            </ul>',

            __( '2.2.3 (March 30, 2016)', 'es-plugin' ) => '<ul>
                <li>Permalinks issue fixed</li>
                <li>Price issue > 1 mln fixed</li>
                <li>beds and baths translation fixed</li>
                <li>Search bug fixed</li>
                <li>Subscription plans added (PRO)</li>
                <li>PDF bug with currency change fixed (PRO)</li>
                <li>New shortcode to display listings of a specific agent added (PRO)</li>
                <li>Automatic/manual approval of listings added (PRO)</li>
            </ul>Please read detailed description of release <a href="http://estatik.net/estatik-simple-pro-ver-2-2-0-released/" target="_blank">here</a>.',

            __( '2.2.2 (November 21, 2015)', 'es-plugin' ) => '<ul>
                <li>View first menu ON/OFF option added</li>
                <li>Bug with currency format 99 999 fixed</li>
                <li>Popup icon in admin map returned</li>
                <li>Search results on 2,3, etc. pages fixed</li>
                <li>Some grammatical errors corrected</li>
                <li>Half baths added to front-end management page (PRO)</li>
                <li>Correct redirection for agents after logged into front-end management page fixed (PRO)</li>
            </ul>',

            __( '2.2.1 (October 22, 2015)', 'es-plugin' ) => '<ul>
                <li>Search by category fixed</li>
            </ul>',

            __( '2.2.0 (October 22, 2015)', 'es-plugin' ) => '<ul>
                <li>Map issues fixed in frontend, admin and lightbox</li>
                <li>Half bathroom option added</li>
                <li>Dark/light style mode added</li>
                <li>Search widget updated with separate Country, State and City drop-down fields</li>
                <li>New shortcode for city added [es_city city="city name"]</li>
                <li>Dimension display of Area and Lot size fields bug fixed</li>
                <li>Slashes // in new fields removed</li>
                <li>Agent phone field bug fixed</li>
                <li>Deprecated method for wp_widget updated</li>
            </ul>Please read full description of new release <a href="http://estatik.net/estatik-simple-pro-ver-2-2-0-released/" target="_blank">here</a>.',

            __( '2.1.0 (July 7, 2015)', 'es-plugin' ) => '<ul>
                <li>New shortcodes for categories added: [es_category category="for sale"],[es_category type="house"],[es_category status="open"].</li>
                <li>New shortcode for search results page added.</li>
                <li>French translation added.</li>
                <li>Google Map API option added.</li>
                <li>Search widget results page bug fixed.</li>
                <li>Description box bug with text fixed.</li>
                <li>Display of area/lot size dimensions on front-end fixed.</li>
                <li>PRO: PDF translation issue fixed.</li>
                <li>PRO: PDF display in IE and Chrome error fixed.</li>
                <li>PRO: Google Map API option added.</li>
                <li>PRO: Copying images after CSV import fixed.</li>
                </ul>Please read full description of new release <a href="http://estatik.net/estatik-2-1-release-no-more-coding-from-now/" target="_blank">here</a>.',

            __( '2.0.1', 'es-plugin' ) => '<ul>
                <li>Italian translation added</li>
                <li>Spanish translation added</li>
                <li>Arabic translation added</li>
                </ul>Please read full description of new release <a href="http://estatik.net/estatik-2-0-terrific-released-map-view-lots-of-major-fixes-done/" target="_blank">here</a>.',

            __( '2.0 (May 16, 2015)', 'es-plugin' ) => '<ul>
                <li>Safari responsive layout issue fixed.</li>
                <li>Google Map icons issue fixed.</li>
                <li>PRO - HTML editor added.</li>
                <li>PRO - Lightbox on single property page added.</li>
                <li>PRO - Tabs issue fixed.</li>
                <li>PRO - Map view shortcodes added.</li>
                <li>PRO - Map view widget added.</li>
                <li>PRO - Option to use different layouts added.</li>
                </ul>',

            __( '1.1.1', 'es-plugin' ) => '<ul>
                <li>Issue with Google Map API fixed</li>
                <li>Translation into Russian added</li>
                </ul>',

            __( '1.0.1', 'es-plugin' ) => '<ul>
                <li>jQuery conflicts fixed.</li>
                <li>Language files added.</li>
                </ul>',

            __( '1.0.0 (March 24, 2015)', 'es-plugin' ) => '<ul>
                <li>Data manager is added.</li>
                <li>Property listings shortcodes are added.</li>
                <li>Search widget is added.</li></ul>
            ',
        ) );
    }

    /**
     * Return themes list for dashboard page.
     *
     * @return mixed
     */
    public static function get_themes_list() {

	    return apply_filters( 'es_get_themes_list', array(
            'project' => array(
                'link' => 'https://estatik.net/product/estatik-project-theme/',
                'image' => ES_ADMIN_IMAGES_URL . 'project-theme.jpg'
            ),
		    'trendy' => array(
			    'link' => 'https://estatik.net/product/theme-trendy-estatik-pro/',
			    'image' => ES_ADMIN_IMAGES_URL . 'es_theme_trendy.png'
		    ),
		    'native' => array(
			    'link' => 'https://estatik.net/?post_type=product&p=12&preview=true',
			    'image' => ES_ADMIN_IMAGES_URL . 'es_theme_native.png'
		    ),
            'mls' => array(
			    'link' => 'https://estatik.net/',
			    'image' => ES_ADMIN_IMAGES_URL . 'estatik_mls_theme.png'
		    ),
	    ) );
    }
}
