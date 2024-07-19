<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Add Booking Details</title>
</head>
<body>
    <div class="container">
        <div class="heading">
            <h1>Add Booking Details</h1>
        </div>

        <!-- Display Success or Error Message -->
        @if(session()->has('success'))
        <b>{{session('success')}}</b>
        @endif
        @if(session()->has('error'))
        <b>{{session('error')}}</b>
        @endif
        <!-- Display Success or Error Message -->
        <br><br>

        <div class="row">

            <!-- Add Booking Details Form -->
            <form method="POST" action="/insert-booking-details">
                @csrf
                <input type="hidden" id="customer_id" name="customer_id" value="1">

                <div class="col-md-3">
                    <label>Booking Date</label>
                    <input type="date" id="booking_date" name="booking_date" required>
                </div>

                <div class="col-md-3">
                    <label>Booking Type</label>
                    <select id="booking_type" name="booking_type" required>
                        <option value="" disabled selected>Select</option>
                        <option value="1">Full Day</option>
                        <option value="2">Half Day</option>
                        <option value="3">Custom</option>
                    </select>
                </div>

                <div class="col-md-3 book_slot" style="display: none;">
                    <label>Booking Slot</label>
                    <select id="booking_slot" name="booking_slot">
                        <option value="" disabled selected>Select</option>
                        <option value="1">First Half</option>
                        <option value="2">Second Half</option>
                    </select>
                </div>

                <div class="col-md-3 from_time" style="display: none;">
                    <label>Booking Time From</label>
                    <input type="time" id="from_time" name="from_time">
                </div>

                <div class="col-md-3 to_time" style="display: none;">
                    <label>Booking Time To</label>
                    <input type="time" id="to_time" name="to_time">
                </div>

                <br>

                <div class="col-md-3">
                    <input type="submit">
                </div>
            </form>
            <!-- Add Booking Details Form -->

        </div>
    </div>
</body>

<script>
    $(document).ready(function() {
        
        $('#booking_type').change(function() {
            // Show Booking Slot only if Booking type is selected as Half Day else hide that
            if($(this).val() == 2)
            {
                $('.book_slot').show();
            }
            else
            {
                $('.book_slot').hide();
            }

            // Show From and To Time only if Booking Type is selected as Custom else hide that
            if($(this).val() == 3)
            {
                $('.from_time').show();
                $('.to_time').show();
            }
            else
            {
                $('.from_time').hide();
                $('.to_time').hide();
            }
        })
    });
</script>

</html>