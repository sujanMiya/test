function topContributersTabLoadmore() {
    const top_contributers = $("#crowdfundly-topcontributers-load-more");
    if ( top_contributers ) {

        let current_page = 1;
        top_contributers.click(function(e) {
            e.preventDefault();
            const $self = $(this);
            const target_div = $('#top-contributors .donor-list');
            const last_page = $(this).data('last-page');
            current_page += 1;

            $self.find('.ml-2').text(crowdfundlyPublicData.loading);

            $.ajax({
                url: crowdfundlyPublicData.ajax_url,
                type: 'POST',
                data: {
                    action: "cf_top_contributers_loadmore",
                    security: crowdfundlyPublicData.nonce,
                    camp_id: $(this).data('camp-id'),
                    org_settings: $(this).data('org-settings'),
                    camp_currency: $(this).data('camp-currency'),
                    current_page: current_page
                },
                success: function(response) {
                    console.log('topContributers', response)
                    $(response).appendTo(target_div);

                    if( last_page == current_page ) {
                        setTimeout( function() {
                            $self.css({"display": "none"});
                        }, 300 );
                    }
                    $self.find('.ml-2').text(crowdfundlyPublicData.load_more);
                },
                error: function(error) {
                    console.log(error);
                    $self.find('.ml-2').text(crowdfundlyPublicData.load_more);
                }
            });
        });
    }
}

exports.topContributersTabLoadmore = topContributersTabLoadmore;
