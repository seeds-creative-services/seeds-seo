(function(Seeds, $, undefined) {

    'use strict';

    /**
     * @namespace Seeds
     * @method Seeds.SEO
     * @uses https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js
     * @description Search Engine Optimization Preview
     */

    Seeds.SEO = {

        UpdateTitle: function() {

            $('#google-preview h3').text($('input[name="seeds_seo[title]"]').val());
            $('#facebook-preview h3').text($('input[name="seeds_seo[title]"]').val());

        },

        UpdateDescription: function() {

            $('#google-preview p').text($('input[name="seeds_seo[description]"]').val());
            $('#facebook-preview p').text($('input[name="seeds_seo[description]"]').val());

        }

    };

    Seeds.SEO.UpdateTitle();
    Seeds.SEO.UpdateDescription();


}(window.Seeds = window.Seeds || {}, jQuery));