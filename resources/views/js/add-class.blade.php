<script type="text/javascript">
    $(function()
    {
        'use strict';

        // NOTE: If you change these constants, you have to change the button
        // string defined in results.blade.php too.
        var ADD = "Add This!";
        var REMOVE = "Remove This!";

        /**
         * PNotification to indicate the status of a class
         * @param text The text to display on the notification
         * @param type The type of notification (success.. error.. etc)
         *
         * PNotification to indicate the status of a class
         */
        function notification(text, type) {
            new PNotify({
                text: text,
                type: type,
                animation: 'slide',
                delay: 3000,
                min_height: "16px",
                animate_speed: 400,
                text_escape: true,
                nonblock: {
                    nonblock: true,
                    nonblock_opacity: .1
                },
                buttons: {
                    show_on_nonblock: true
                }
            });
        }

        /**
         * Change the look of the button in the callback
         * @param button The button object i.e. $(this)
         * @param remove The class to remove
         * @param add    The class to add
         * @param text   The text to replace on the button
         */
        function changeButton(button, remove, add, text, action) {
            button.removeClass(remove);
            button.addClass(add);
            button.text(text);
            button.data('action', action);
        }

        /**
         * Get and change the cart quantity
         */
        function getCartQuantity() {
            $('#jewel').text('');
            $.getJSON("{{ url('schedulizer/cart') }}", function(data) {
                if(data.quantity > 0) {
                    $('#jewel')
                            .show("slide", { direction: "up" }, 300)
                            .text(data.quantity);
                } else {
                    $('#jewel')
                            .hide("slide", { direction: "down" }, 300)
                            .text(data.quantity);
                }
            });
        }

        // Get the cart quantity on page load and display the notification
        // jewel
        getCartQuantity();

        /**
         * Performs the add/remove action, the resulting notification prompts and
         * button visual characteristics
         *
         * It POSTs to the cart API to add/remove from cart, and there are a
         * set of conditions that are set which are better explained in the docs
         * for the API under /app/Http/Controllers/SchedulizerController.php in
         * the add() and remove() method
         **/
        $('.btn-material-yellow-600').click(function(){
            var $localThis = $(this);
            var $className = $(this).data('class-name');

            // "Add to cart" is clicked
            if($(this).data('action') === 'add') {
                $.ajax({
                    type: 'post',
                    url: '{{ URL('schedulizer/add') }}',
                    data: {
                        "class": $className,
                        _token: "{{ csrf_token() }}" // Laravel needs a csrf
                                                     // token for all POSTs
                    },
                    dataType: 'json'
                }).done(function(data){
                    // If the code is 1, it indicates that the class was
                    // successfully added to cart, so change the button to red,
                    // change the text, flash a success notification, and change
                    // the data attribute to remove
                    if(data.code === 1) {
                        notification(data.message, 'success');

                        // Change the button to the "Remove Me!" style
                        changeButton(
                            $localThis,
                            'btn-material-yellow-600 mdi-content-add-circle-outline',
                            'btn-danger mdi-content-remove-circle-outline',
                            '\n' + REMOVE,
                            'remove'
                        );
                    } else if (data.code === 0) {
                        // If the code is 0, it indicates that the item already
                        // exists in the cart, so change the button to red,
                        // change the text, flash an error message, and change
                        // the data attribute to remove
                        notification(data.message, 'error');

                        // Change the button to the "Remove Me!" style
                        changeButton(
                            $localThis,
                            'btn-material-yellow-600 mdi-content-add-circle-outline',
                            'btn-danger mdi-content-remove-circle-outline',
                            '\n' + REMOVE,
                            'remove'
                        );
                    } else {
                        notification(data.message, 'error');
                    }
                    getCartQuantity();
                });
                return false;
            // "Remove from cart" is clicked
            } else if($(this).data('action') === 'remove'){
                $.ajax({
                    type: 'post',
                    url: '{{ URL('schedulizer/remove') }}',
                    data: {
                        "class": $className,
                        _token: "{{ csrf_token() }}" // Laravel needs a csrf
                                                     // token for all POST
                    },
                    dataType: 'json'
                }).done(function(data){
                    // If the code is 1, it indicates that the class was
                    // successfully removed from the cart, so change the button
                    // back to yellow, change the text, flash a success notif,
                    // and change the data attribute to add
                    if(data.code === 1) {
                        notification(data.message, 'success');

                        // Change the button to the "Add Me!" style
                        changeButton(
                            $localThis,
                            'btn-danger mdi-content-remove-circle-outline',
                            'btn-material-yellow-600 mdi-content-add-circle-outline',
                            '\n' + ADD,
                            'add'
                        );
                    } else if(data.code === 0) {
                        // If the code is 0, it indicates that the class was not
                        // found in the cart, so change the button to yellow,
                        // change the text, flash an error message, and change
                        // the data attribute to add
                        notification(data.message, 'error');

                        // Change the button to the "Add Me!" style
                        changeButton(
                            $localThis,
                            'btn-danger mdi-content-remove-circle-outline',
                            'btn-material-yellow-600 mdi-content-add-circle-outline',
                            '\n' + ADD,
                            'add'
                        );
                    } else {
                        // Something else went wrong, and it shouldn't happen,
                        // so just flash a notif
                        notification(data.message, 'error');
                    }
                    getCartQuantity();
                });
                return false;
            }

        });
    });
</script>