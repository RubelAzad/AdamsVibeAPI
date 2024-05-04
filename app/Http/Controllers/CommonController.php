<?php

namespace App\Http\Controllers;

use App\Models\B2B;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

// Don't forget to import the B2B model

class CommonController extends Controller
{
    public function sendContactForm(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required'],
                'email' => ['required', 'email'],
                'subject' => ['required'],
                'message' => ['required']
            ]);

            if ($validator->fails()) {
                return make_validation_error_response($validator->errors());
            }

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'contactmessage' => $request->message
            ];

            Mail::send('Email.send_contact_form', $data, function ($message) use ($data) {
                $message->to([
                    $data["email"] => $data["name"],
                    "shop@adamsvibe.com" => "adamsvibe"
                ]);
                $message->from(config('mail.contact_form_recipient_email'));
                $message->subject("Adams Vibe: Contact Request");
            });

        } catch (Exception $exception) {
            return make_error_response($exception->getMessage());
        }

        return make_success_response("Email sent successfully.");
    }

    public function sendB2BSaleForm(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [

                'first_name' => ['required'],
                'last_name' => ['nullable'],
                'contact_number' => ['required'],
                'email_address' => ['required', 'email'],
                'nid_number' => ['nullable'],
                'nid_upload' => ['nullable|file|mimes:pdf,jpeg,jpg,png|max:500'],
                'business_name' => ['required'],
                'business_location' => ['required'],
                'type_of_business' => ['required'],
                'tin_number' => ['required'],
                'bin_number' => ['nullable'],
                'doc_upload' => ['nullable|file|mimes:pdf,jpeg,jpg,png|max:500']
            ]);

            if ($validator->fails()) {
                return make_validation_error_response($validator->errors());
            }

            // Store uploaded files
            $nidPath = $request->file('nid_upload')->store('uploads');
            $binPath = $request->file('doc_upload')->store('uploads');

            $data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'contact_number' => $request->contact_number,
                'email_address' => $request->email_address,
                'nid_number' => $request->nid_number,
                'nid_upload' => $request->nid_upload,
                'business_name' => $request->business_name,
                'business_location' => $request->business_location,
                'type_of_business' => $request->type_of_business,
                'tin_number' => $request->tin_number,
                'bin_number' => $request->bin_number,
                'doc_upload' => $request->doc_upload
            ];

            B2B::create([
                'name' => $request->first_name.' '.$request->last_name,
                'contact_number' => $request->contact_number,
                'email_address' => $request->email_address,
                'nid_number' => $request->nid_number,
                'nid_upload' => $request->nid_upload,
                'business_name' => $request->business_name,
                'business_location' => $request->business_location,
                'type_of_business' => $request->type_of_business,
                'tin_number' => $request->tin_number,
                'bin_number' => $request->bin_number,
                'doc_upload' => $request->doc_upload,
                'status' => B2B::STATUS_PENDING,
            ]);

            Mail::send('Email.send_b2b_sale_form', $data, function ($message) use ($data) {
                $message->to([
                    $data["email_address"] => $data["name"],
                    "shop@adamsvibe.com" => "adamsvibe"
                ]);
                $message->from(config('mail.contact_form_recipient_email'));
                $message->subject("Adams Vibe: Vendor Request");
            });

        } catch (Exception $exception) {
            return make_error_response($exception->getMessage());
        }

        return make_success_response("Mail sent successfully.");
    }
}
