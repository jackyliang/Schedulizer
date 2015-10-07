<script type="text/javascript">
    $(function()
    {
        $.ui.autocomplete.prototype._renderItem = function( ul, item){
            var term = this.term.split(' ').join('|');
            var re = new RegExp("(" + term + ")", "gi") ;
            var t = item.label.replace(re,"<b>$1</b>");
            return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + t + "</a>" )
                    .appendTo( ul );
        };

        // once page loads, make AJAX request to get your autocomplete list and apply to HTML
        $.ajax({ url: '{{ URL('autocomplete') }}',
            type: "GET",
            contentType: "application/json",
            success: function(tags) {
                $( "#q" ).autocomplete({
                    source: tags,
                    minLength: 2,
                    delay: 0,
                    autoFocus: true,
                    select: function(event, ui) {
                        $('#q').val(ui.item.value);
                    }
                });
            }
        });
    });
</script>