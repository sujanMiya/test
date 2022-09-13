<?php
public static function get_top_contributers($id, $per_page =  3, $page = 1)
    {
        $route = 'campaigns/' . $id . '/top-donors?per_page=' . $per_page . '&page=' . $page;
        $response = Request::get( $route );
        if ( $response->status_code() !== 200 ) return false;
            // var_dump($response->body());
        return $response->body();
    }
    public function cf_top_contributers_loadmore()
    {
        $security = check_ajax_referer( 'crowdfundly_public_nonce', 'security' );
        if ( false == $security ) {
            return;
        }

        $camp_id = esc_html( $_POST['camp_id'] );
        $current_page = esc_html( $_POST['current_page'] );
        $org_settings = json_decode( esc_html( $_POST['org_settings'] ) );
        $camp_currency = json_decode( esc_html( $_POST['camp_currency'] ) );

        $top_contributers = $this->get_top_contributers( $camp_id, 3, $current_page );
        // var_dump($top_contributers);
        if ( ! $top_contributers ) {
            wp_send_json_error( [ 'message' => 'Something went wrong' ], 404 );
        }

        if ( ! empty( $top_contributers['data'] ) ) :
            ?>
   
                <?php
                foreach ( $top_contributers['data'] as $contributor ) :
                    $avatar_url = cf_asset( 'images/public/avatar.png' );
                    if ( ! $contributor['is_anonymous'] && $contributor['avatar'] ) {
                        $avatar_url = $contributor['avatar'];
                    }
                    ?>
                    <div class="donor-list__item">
                        <div class="donor-card">
                            <div class="donor-card__avatar" style="background-image: url(<?php echo esc_url( $avatar_url ); ?>);"></div>
                            <div class="donor-card__details">
                                <h6 class="donor-card__name">
                                <?php /*var_dump($contributor['full_name']); */ ?>
                                    <?php echo isset( $contributor['full_name'] ) ? esc_html( $contributor['full_name'] ) : 'Anonymous Contributor'; ?>
                                </h6> 
                                <h6 class="donor-card__label">
                                    <?php _e( 'Amount:', 'crowdfundly' ); ?> 
                                    <span class="donor-card__value">
                                        <?php echo esc_html( SingleCampaignController::get_currency_format( $org_settings, $camp_currency, $contributor['formatted_amount'] ) ); ?>
                                    </span>
                                </h6>
                            </div>
                            <div class="donor-card__badge">
                                <img src="<?php echo esc_url( cf_asset( 'images/public/medal.svg' ) ); ?>" alt="<?php echo __( 'Badge', 'crowdfundly' ); ?>" class="donor-card__badge-img">
                            </div>
                        </div>
                    </div>
                    <?php
                endforeach;
                ?>
 
            <?php
        endif;
    }
