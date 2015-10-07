<script type="text/javascript">
    $(function()
    {
        /**
         * Stores the generated schedule array from our API
         **/
        var result;

        /**
         * The starting index for the URL hash
         **/
        var index = 0;

        /**
         * This generates the URL which will query our schedule generation API
         * It contains:
         * 'from'   - from what time you don't want classes. Default - 10 AM
         * 'to'     - to what time you don't want classes. Default - 12 PM
         * 'limit'   - days you don't want classes. Default - none
         * 'full'   - include full classes. Default - true
         * 'campus'     - show only university city campus classes. Default - true
         **/
        var from = 1000;
        var to = 1200;
        var limit = '';
        var full = 1;
        var campus = 0;

        // Updates the URL to query the generated schedule API
        var url = getUpdatedURL();

        // FUllCalendar date initialization
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        /**
         * Refresh the calendar
         **/
        $('#refresh').on('click', function(){
            updateResults();
        });

        /**
         * Updates the dropdown text for 'from'
         **/
        $('#from li').on('click', function(){
            $('#from-text').text($(this).text());
        });

        /**
         * Updates the dropdown text for 'to'
         **/
        $('#to li').on('click', function(){
            $('#to-text').text($(this).text());
        });

        /**
        * This generates the `days` value from the day of the week checkboxes,
        * the 'from' time span, the 'to' time span, the 'full' classes flag, and
        * show only center city campus
        **/
        // Generates the 'days' string i.e. MWF
        $("#limit").change(function(){
            var searchIDs = $("input:checkbox:checked").map(function(){
                return $(this).data('date');
            }).toArray();
            limit = searchIDs.join('');
            getUpdatedURL();
        });

        // Get the military time for 'from'
        $('#from li a').on('click', function(){
            from = $(this).data('military');
            getUpdatedURL();
        });

        // Get the military time for 'to'
        $('#to li a').on('click', function(){
            to = $(this).data('military');
            getUpdatedURL();
        });

        // Show full classes or not
        $('#full').change(function(){
            full = $("#full-checkbox").is(':checked') ? 1 : 0 ;
            getUpdatedURL();
        });

        // Show only University City classes or not
        $('#cc').change(function(){
            campus = $("#cc-checkbox").is(':checked') ? 1 : 0 ;
            getUpdatedURL();
        });

        /*
        * Focus on the search bar when clicked
        */
        $(document).on('click', '#focus', function(){
            $("#q").focus();
        });

        /**
         * Updates the global URL that's used to query the class generation API
         */
        function getUpdatedURL() {
            url = '{{ URL('schedulizer/generate') }}' + '?from=' + from + '&to=' + to + '&limit=' + limit + '&full=' + full + '&campus=' + campus;
            return url;
        }

        /**
         * This generates the HTML list of classes, adds a colored circle before
         * the name of each class, and adds a remove button
         * @param result  The array from the class generation JSON API
         * @returns {*}   The HTML for the list of classes used to generate
         *                the schedules
         */
        function formatList(result) {
            if(result.quantity === 0) {
                return 'Oops! Looks like no schedules were generated. <a id="focus"><span class="glyphicon glyphicon-search"></span> Add</a> some classes or widen your filter options!';
            }
            var text = '';
            text += '<ul class="list-group class-cart">';

            // index is a global variable that keeps track of where the user is
            // when looking through the schedules. If he/she removes an item
            // from the cart, there's a possibility that the number of schedules
            // generated is less than the ones before removal, and so the index
            // would mismatch with the new number of schedules. Reset it in that
            // case, otherwise, leave it be.
            if(typeof result.classes[index] === 'undefined'){
                index = 0;
            }
            // Build the unordered list of classes with their name and CRN
            // HACK Aug 21 2015: Yes. I used in-line style. But I am not sure
            //                   how to assign from JSON a `color` variable
            //                   dynamically to the unicode circle item.
            //                   This section of code is NASTY. I am sorry.
            for (i = 0; i < result.classes[index].length; i++) {
                text += '<li class="list-group-item">' +
                '<span class="glyphicon glyphicon-dot" style="opacity: 0.65; color:' +
                result.classes[index][i]['color'] +'"></span> ' +
                result.classes[index][i]['short_name'] +
                ' (' + result.classes[index][i]['crn'] + ')' +
                '<a data-action="remove" data-class-name="' +
                result.classes[index][i]['name'] + '"class="btn btn-default remove-item btn-xs btn-font-12-px btn-raised margin-add-to-cart mdi-content-clear"></a>' +
                '</li>'
                ;
            }
            text += '</ul>';
            return text;
        }

        /**
         * Performs the remove action, the resulting notification prompts and
         * button visual characteristics
         *
         * It POSTs to the cart API to remove from cart, and there are a
         * set of conditions that are set which are better explained in the docs
         * for the API under /app/Http/Controllers/SchedulizerController.php in
         * the remove() method
         **/
        $(document).on('click','.btn.remove-item', function(){
            var $localThis = $(this);
            var $className = $(this).data('class-name');

            if($(this).data('action') === 'remove'){
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
                    // successfully removed from the cart, so refresh and
                    // regenerate the results and schedule
                    if(data.code === 1) {
                        notification(data.message, 'success');
                        updateResults();
                    } else if(data.code === 0) {
                        // If the code is 0, it indicates that the class was not
                        // found in the cart, so refresh and regenerate the
                        // results
                        notification(data.message, 'error');
                        updateResults();
                    } else {
                        // Something else went wrong, and it shouldn't happen,
                        // so just flash a notif
                        notification(data.message, 'error');
                    }
                });
                return false;
            }
        });

        /**
         * Show number of results in header as well as append the list of
         * classes to the cart panel. Uses the dynamically updated URL
         */
        function updateResults(){
            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json'
            }).done(function(data){
                // Get the hash
                window.location.hash = '#' + (index + 1);
                // Save the response data to hash
                result = data;
                text = formatList(result);
                $("#classes").html(text);
                updateIndexOfSchedule();
                // Render the calendar on page-load
                renderCalendar(index);
                getCartQuantity();
                if(data.quantity === 0) {
                    notification(result.message, 'error');
                } else {
                    notification(result.message, 'success');
                }
            });
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

        /*
         * Render the calendar onto the view
         */
        function renderCalendar(index) {

            // Destroy the old calendar before updating the calendar again.
            $('#calendar').fullCalendar('destroy');

            var myDataset = result;

            $('#calendar').fullCalendar({
                editable: false,
                handleWindowResize: true,
                weekends: false, // Hide weekends
                defaultView: 'agendaWeek', // Only show week view
                header: false, // Hide buttons/titles
                minTime: '07:30:00', // Start time for the calendar
                maxTime: '22:00:00', // End time for the calendar
                columnFormat: {
                    week: 'ddd' // Only show day of the week names
                },
                displayEventTime: true,
                allDayText: 'Online/TBD'
            });

            function GetDateString(myDate){
                // GET CURRENT DATE
                var date = new Date(myDate);

                // GET YYYY, MM AND DD FROM THE DATE OBJECT
                var yyyy = date.getFullYear().toString();
                var mm = (date.getMonth()+1).toString();
                var dd  = date.getDate().toString();

                // CONVERT mm AND dd INTO chars
                var mmChars = mm.split('');
                var ddChars = dd.split('');

                // CONCAT THE STRINGS IN YYYY-MM-DD FORMAT
                var datestring = yyyy + '-' + (mmChars[1]?mm:"0"+mmChars[0]) + '-' + (ddChars[1]?dd:"0"+ddChars[0]);

                return datestring;
            }

            /*
            * Checks if an item is in an array
            */
            function containsObject(obj, list) {
                var i;
                for (i = 0; i < list.length; i++) {
                    if (list[i] === obj) {
                        return true;
                    }
                }
                return false;
            }

            // Add events from JSON data
            $('#calendar').fullCalendar('addEventSource',
                function(start, end, timezone, callback) {
                    var events = [];
                    var overlap = [];

                    // Don't run the code if there is no data
                    if(myDataset.classes.length === 0) {
                        return;
                    }

                    for (loop = start.toDate().getTime(); loop <= end.toDate().getTime(); loop = loop + (24 * 60 * 60 * 1000)) {
                        var test_date = new Date(loop);
                        var obj = myDataset.classes[index];

                        for (j = 0; j < obj.length; j++) {

                            var days = obj[j].days;
                            var campus = obj[j].campus;
                            // Add online or TBA-time classes once to the calendar
                            // A list of CRNs has to be saved because this loop
                            // runs a total of six times for the number of days
                            // in a week that we check [to add events depending
                            // on the week].
                            if(days === 'TBD' || campus === 'ONLINE') {
                                // Class is online or TBD, and there is no
                                // no re-occurence detected
                                if(!containsObject(obj[j].crn, overlap)) {
                                    overlap.push(obj[j].crn);
                                    // Generate an all-day event by spanning an
                                    // event from 6 days ago from today to 6 days
                                    // from now.
                                    var lastWeek = moment().subtract(6, 'days').format("YYYY-MM-DD hh:mm a");
                                    var nextWeek = moment().add(6, 'days').format("YYYY-MM-DD hh:mm a");

                                    events.push({
                                        title: obj[j].short_name,
                                        allDay: true,
                                        start: lastWeek,
                                        end: nextWeek,
                                        color: obj[j].color
                                    });
                                }
                                // End THIS iteration of the for-loop but don't
                                // end ALL iterations of the for-loop.
                                continue;
                            }

                            var times = obj[j].times.split('-');
                            var daysArray = days.split('');

                            for (k = 0; k < daysArray.length; k++) {

                                var startDate = GetDateString(loop) + ' ' + times[0].trim();
                                var endDate = GetDateString(loop) + ' ' + times[1].trim();

                                if (daysArray[k] == 'M' && test_date.is().monday()) {
                                    events.push({
                                        title: obj[j].short_name,
                                        start: startDate,
                                        end: endDate,
                                        color: obj[j].color
                                    });
                                } else if (daysArray[k] == 'T' && test_date.is().tuesday()) {
                                    events.push({
                                        title: obj[j].short_name,
                                        start: startDate,
                                        end: endDate,
                                        color: obj[j].color
                                    });
                                } else if (daysArray[k] == 'W' && test_date.is().wednesday()) {
                                    events.push({
                                        title: obj[j].short_name,
                                        start: startDate,
                                        end: endDate,
                                        color: obj[j].color
                                    });
                                } else if (daysArray[k] == 'R' && test_date.is().thursday()) {
                                    events.push({
                                        title: obj[j].short_name,
                                        start: startDate,
                                        end: endDate,
                                        color: obj[j].color
                                    });
                                } else if (daysArray[k] == 'F' && test_date.is().friday()) {
                                    events.push({
                                        title: obj[j].short_name,
                                        start: startDate,
                                        end: endDate,
                                        color: obj[j].color
                                    });
                                }
                            }
                        }
                    }
                    // Convert strings to moment objects because of deprecation
                    // risks
                    for(var i in events){
                        events[i].start = moment(events[i].start,"YYYY-MM-DD hh:mm a");
                        events[i].end = moment(events[i].end,"YYYY-MM-DD hh:mm a");
                    }

                    // return events generated
                    callback(events);
                }
            );
        }

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

        // Update the results on page-load
        updateResults();

        /**
         * Updates the title text of the schedule
         **/
        function updateIndexOfSchedule(){
            if(result.quantity === 0) {
                $("#schedule-panel-title").html('Schedule');
            } else {
                $("#schedule-panel-title").html('Schedule ' + (index + 1) + ' of ' + result.quantity);
            }
        }

        /**
         * Button behaviors for cycling through the generated schedules
         */
        $('.btn.btn-primary.toggle-schedules').click(function(e) {
            // Prevent the page redirect to another page, as you have href on it.
            // Or you can remove the href on the anchors.
            e.preventDefault();
            // Prevent undesired behaviors happen when data is not retrieved yet.
            if (!result || !result.classes) {
                return;
            }
            // Calculate next index for data to show.
            var next = $(this).data('direction') === 'left' ? -1 : 1;
            index = index + next;
            // Make the index in boundary.
            if (index >= result.classes.length) {
                index = 0;
            } else if (index < 0) {
                index = result.classes.length - 1;
            }
            // Add hash.
            window.location.hash = '#' + (index + 1);

            updateIndexOfSchedule();

            text = formatList(result);
            $("#classes").html(text);

            renderCalendar(index);
        });
    });
</script>
