<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Enquiry</title>
    <style>
        body  { font-family: Arial, Helvetica, sans-serif; background:#f4f4f4; margin:0; padding:0; color:#333; }
        .wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:6px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        .hdr  { background:#DF0A0A; padding:28px 32px; }
        .hdr h1 { margin:0; font-size:20px; color:#fff; }
        .hdr p  { margin:4px 0 0; font-size:13px; color:rgba(255,255,255,.8); }
        .body { padding:32px; }
        .row  { margin-bottom:18px; }
        .lbl  { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#888; margin-bottom:4px; }
        .val  { font-size:15px; color:#1C2539; word-break:break-word; }
        .val a { color:#DF0A0A; text-decoration:none; }
        .divider { border:none; border-top:1px solid #eee; margin:22px 0; }
        .msg-box { background:#f8f8f8; border-left:4px solid #DF0A0A; padding:14px 16px; border-radius:3px; font-size:14px; line-height:1.7; color:#333; white-space:pre-wrap; word-break:break-word; }
        .ftr { background:#f4f4f4; padding:16px 32px; font-size:12px; color:#999; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="hdr">
        <h1>New Enquiry Received</h1>
    <p>Submitted {{ $enquiry->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }} IST</p>
    </div>
    <div class="body">

        <div class="row">
            <div class="lbl">Name</div>
            <div class="val">{{ $enquiry->name }}</div>
        </div>

        <div class="row">
            <div class="lbl">Email</div>
            <div class="val"><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></div>
        </div>

        @if($enquiry->phone)
        <div class="row">
            <div class="lbl">Phone</div>
            <div class="val">{{ $enquiry->phone }}</div>
        </div>
        @endif

        @if($enquiry->subject)
        <div class="row">
            <div class="lbl">Subject</div>
            <div class="val">{{ $enquiry->subject }}</div>
        </div>
        @endif

        @if($enquiry->gst_number)
        <div class="row">
            <div class="lbl">GST Number</div>
            <div class="val">{{ $enquiry->gst_number }}</div>
        </div>
        @endif

        <hr class="divider">

        <div class="row">
            <div class="lbl">Message</div>
            <div class="msg-box">{{ $enquiry->message }}</div>
        </div>

    </div>
    <div class="ftr">
      Reply to this email to answer the visitor directly. Their email address is set as the Reply-To.
    </div>
</div>
</body>
</html>
