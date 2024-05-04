<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vendor Request Details</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;">

<div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px;">
    <div style="text-align: center; padding: 20px;">
        <h2 style="color: #333333;">Vendor Request from adamsvibe.com</h2>
    </div>
    <div style="padding: 20px;">
        <p>Nid Number: {{ $nid_number }}</p>
        <p>Name: {{ $first_name.' '.$last_name }}</p>
        <p>Business Name: {{ $business_name }}</p>
        <p>Business Location: {{ $business_location }}</p>
        <p>Type of Business: {{ $type_of_business }}</p>
        <p>Tin Number: {{ $tin_number }}</p>
        <p>Bin Number: {{ $bin_number }}</p>
        <p>Contact Number: {{ $contact_number }}</p>
        <p>Email Address: {{ $email_address }}</p>

    </div>
    {{-- <div style="text-align: center; padding: 20px; background-color: #f7f7f7;">
        <p style="color: #888888;">This is an automated email. Please do not reply.</p>
    </div> --}}
</div>

</body>
</html>
