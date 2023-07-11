<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/tinymce/tinymce.min.js')}}"></script>

<script>

    /**
     * Ripopola le localita
     */

    function chainLocalita(el_father, el_child, is_listing){

        var loc = <?php echo $data["localita_chained"]?>;
        var m_id = el_father.val()

        if (m_id) {

            var localita_id = el_child.data('id');
            el_child.empty();

            /**
             * Voglio che le options siano ordinate alfabeticamente
             */

            var locarray = [];
            jQuery.each(loc[m_id], function(localita_id, localita_nome){
                locarray.push({'id': localita_id, 'nome': localita_nome});
            });

            locarray.sort(function(a, b){
                return a.nome.toLowerCase().localeCompare(b.nome.toLowerCase());
            });

            /**
             * Se la macrolocalita contiene una sola localita,
             * la select delle localita obbligherà ad una scelta (non ci sarà la voce "vuota")
             * se invece la macrolicalita ha più localita,
             * la select delle localita renderà possibile non indicare nulla (ci sarà la voce "vuota")
             */

            if(is_listing === true){
                if(locarray.length > 1) {
                    locarray.unshift({'id': '', 'nome': ''});
                }
            } else {
                locarray.unshift({'id': '', 'nome': ''});
            }

            jQuery.each(locarray, function(i, el){
                var opt = jQuery('<option></option>')
                    .attr('value', el.id)
                    .attr('selected', localita_id == el.id)
                    .text(el.nome);
                el_child.append(opt);
            });

        }
    }

    /**
     * Conteggio caratteri 
     */

    function conteggio ( obj) {

        var result = obj.val();
        return result.length;

    }

    jQuery(document).ready(function(){

        /** 
         * Conteggio caratteri e tag nei testi
         */

        jQuery(".cont_caratteri_input").each(function () {
            jQuery(this).parent().find("span.cont_caratteri").text( conteggio(jQuery(this)) );
        }).keyup(function (e) {
            jQuery(this).parent().find("span.cont_caratteri").text(  conteggio(jQuery(this)) );
        });

        jQuery(".tag").click( function (e){
            var me = jQuery('#' + jQuery(this).data("link"));
            var text = 	me.val();
            var valore = jQuery(this).text();
            var newtext = text.substr(0, me[0].selectionStart) + valore + text.substr(me[0].selectionEnd, text.length);
            me.val(newtext);
            jQuery(this).parent().find("span.cont_caratteri").text( conteggio( me ) );
            e.preventDefault();
        });

        jQuery(".tag2").click( function (e){
            var me = jQuery('#' + jQuery(this).data("link"));
            var text = 	me.val();
            var valore = jQuery(this).data("text")
            var newtext = text.substr(0, me[0].selectionStart) + valore + text.substr(me[0].selectionEnd, text.length);
            me.val(newtext);
            jQuery(this).parent().find("span.cont_caratteri").text( conteggio( me ) );
            e.preventDefault();
        });

        /**
         * Ripopola le localita
         */

        var el_listing_father = jQuery('#listing_macrolocalita_id');
        var el_listing_child = jQuery('#listing_localita_id');
        var el_menu_father = jQuery('#menu_macrolocalita_id');
        var el_menu_child = jQuery('#menu_localita_id');

        chainLocalita(el_listing_father, el_listing_child, true);

        el_listing_father.change(function(){
            chainLocalita(el_listing_father, el_listing_child, true);
        });

        chainLocalita(el_menu_father, el_menu_child, false);

        el_menu_father.change(function(){
            chainLocalita(el_menu_father, el_menu_child, false);
        });

    });

</script>