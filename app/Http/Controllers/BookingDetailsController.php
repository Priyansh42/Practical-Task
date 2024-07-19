<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingDetails;
use Illuminate\Support\Facades\DB;
use Session;

class BookingDetailsController extends Controller
{
    // Function to Redirect to Add Booking Details View Page
    public function addBookingDetails()
    {
        return view('add_booking_details');
    }

    // Function to insert Booking Details in Database Table by checking duplicate records first
    public function insertBookingDetails(Request $request)
    {
        //Validation Rules
        $rules = [
            'customer_id' => 'required|integer',
            'booking_date' => 'required|date',
            'booking_type' => 'required|integer',
            'booking_slot' => 'nullable|integer',
            'from_time' => 'nullable',
            'to_time' => 'nullable'
        ];

        $validated_data = $request->validate($rules);

        // Find Duplicate Record with same values
        $check_duplicate_record = BookingDetails::select(DB::raw('COUNT(*) AS duplicate_count'))
            ->where('booking_date',$validated_data['booking_date'])
            ->where('booking_type',$validated_data['booking_type']);

        if($validated_data['booking_type'] == 2)
        {
            $check_duplicate_record = $check_duplicate_record->where('booking_slot',$validated_data['booking_slot']);
        }
        else if($validated_data['booking_type'] == 3)
        {
            $check_duplicate_record = $check_duplicate_record->where('from_time',$validated_data['from_time'])
            ->where('to_time',$validated_data['to_time']);
        }

        $check_duplicate_record = $check_duplicate_record->first();

        // Check For Conflicting Records
        if($validated_data['booking_type'] == 1)
        {
            // While inserting Full Day booking, check for any half booking on same date
            $check_condition_1 = BookingDetails::select(DB::raw('COUNT(*) AS conditions_count_1'))
                ->where('booking_date',$validated_data['booking_date'])
                ->where('booking_type','2')
                ->first();

            // While inserting Full Day booking, check for any custom booking on same date
            $check_condition_2 = BookingDetails::select(DB::raw('COUNT(*) AS conditions_count_2'))
                ->where('booking_date',$validated_data['booking_date'])
                ->where('booking_type','3')
                ->first();
        }
        if($validated_data['booking_type'] == 2)
        {
            // while inserting half day booking check for any full day booking on same date
            $check_condition_1 = BookingDetails::select(DB::raw('COUNT(*) AS conditions_count_1'))
                ->where('booking_date',$validated_data['booking_date'])
                ->where('booking_type','1')
                ->first();

            // while inserting half day booking check for conflicting time slot on same date
            if(isset($validated_data['booking_slot']) && $validated_data['booking_slot'] == 1)
            {
                $check_condition_2 = BookingDetails::select(DB::raw('COUNT(*) AS conditions_count_2'))
                    ->where('booking_date',$validated_data['booking_date'])
                    ->where('booking_type','3')
                    ->where('from_time','>=','00:00:00')
                    ->where('to_time','<=','11:59:59')
                    ->first();
            }
            else if(isset($validated_data['booking_slot']) && $validated_data['booking_slot'] == 2)
            {
                $check_condition_2 = BookingDetails::select(DB::raw('COUNT(*) AS conditions_count_2'))
                ->where('booking_date',$validated_data['booking_date'])
                ->where('booking_type','3')
                ->where('from_time','>=','12:00:00')
                ->where('to_time','<=','23:59:59')
                ->first();
            }
        }
        else if($validated_data['booking_type'] == 3)
        {
            // while inserting custom day booking check for any full day booking on same date
            $check_condition_1 = BookingDetails::select(DB::raw('COUNT(*) AS conditions_count_1'))
                ->where('booking_date',$validated_data['booking_date'])
                ->where('booking_type',1)
                ->first();

            // while inserting custom day booking check for any half day booking with conflicting time slot on same date
            if($validated_data['from_time'] >= '00:00:00' && $validated_data['to_time'] <= '11:59:59')
            {
                $check_condition_2 = BookingDetails::select(DB::raw('COUNT(*) AS conditions_count_2'))
                    ->where('booking_date',$validated_data['booking_date'])
                    ->where('booking_type','2')
                    ->where('booking_slot','1')
                    ->first();
            }
            else if($validated_data['from_time'] >= '12:00:00' && $validated_data['to_time'] <= '23:59:59')
            {
                $check_condition_2 = BookingDetails::select(DB::raw('COUNT(*) AS conditions_count_2'))
                    ->where('booking_date',$validated_data['booking_date'])
                    ->where('booking_type','2')
                    ->where('booking_slot','2')
                    ->first();
            }
        }

        // Check for Duplicate Records
        if($check_duplicate_record['duplicate_count'] == 0)
        {
            // insert data in table based on the counts else show error message
            if($validated_data['booking_type'] == 1 && $check_condition_1['conditions_count_1'] == 0 && $check_condition_2['conditions_count_2'] == 0)
            {
                $insert_booking_details = BookingDetails::create($validated_data);
            }
            else if($validated_data['booking_type'] == 2 && $check_condition_1['conditions_count_1'] == 0 && $check_condition_2['conditions_count_2'] == 0)
            {
                $insert_booking_details = BookingDetails::create($validated_data);
            }
            else if($validated_data['booking_type'] == 3 && $check_condition_1['conditions_count_1'] == 0 && $check_condition_2['conditions_count_2'] == 0)
            {
                $insert_booking_details = BookingDetails::create($validated_data);
            }
            else
            {
                session()->flash('error','Duplicate Booking! Please select different date or slot.');
                return redirect()->back();
            }
    
            // if booking successful then show success message else error message
            if($insert_booking_details)
            {
                session()->flash('success','Booking Details Added Successfully!');
            }
            else
            {
                session()->flash('error','Error Adding Booking Details!');
            }
        }
        else
        {
            session()->flash('error','Duplicate Booking! Please select different date or slot.');
        }

        // redirect back to add booking details view page to show either success or error message
        return redirect()->back();
    }
}
