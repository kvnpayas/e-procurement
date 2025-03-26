<!DOCTYPE html>
<html>

<head>
  <title>Registration for e-Procurement</title>
  <style>
    .email-header {
      padding: 10px;
      text-align: center;
      color: #0F3D5C;
    }

    .content-text {
      padding: 10px;
      margin-top: 30px;
      margin-bottom: 30px;
    }

    .content-text p {
      color: #0F3D5C;
      margin-top: 0px
    }
    .content-text p span{
      color: #E76727;
      font-weight: 900;
      font-size: 40px;
    }

    .content-button p {
      text-align: center;
      margin-bottom: 30px;
    }

    .content-button a {
      background: rgb(14, 96, 133);
      background: linear-gradient(360deg, rgba(14, 96, 133, 1) 81%, rgba(148, 200, 231, 1) 100%);
      color: white;
      padding-left: 65px;
      padding-right: 65px;
      padding-top: 10px;
      padding-bottom: 10px;
      text-decoration: none;
      border-radius: 10px;
    }

    .content-button a:hover {
      background: rgb(4, 66, 95);
      background: linear-gradient(360deg, rgb(4, 66, 95) 81%, rgba(148, 200, 231, 1) 100%);
    }

    .content-links {
      margin-top: 50px;
    }

    .content-links a {
      display: block;
      margin-bottom: 20px;
      color: #0e6085;
    }

    .content-links a:hover {
      color: #06364b;
    }

    .content-helpdesk {
      margin-bottom: 20px;
      margin-top: 50px;
    }

    .content-helpdesk span {
      font-weight: bold;
      color: #E76727;
    }

    .content-contact {
      margin-top: 50px;
    }

    .content-contact span:nth-child(1) {
      font-style: italic;
    }
  </style>
</head>

<body>
  {{-- <div class="email-header">
    <h1>Welcome</h1>
    <img src="{{ url('img/tei_logo.png') }}" alt="TEI LOGO" height="120">
  </div> --}}
  <!-- <hr style="border: 1px solid #0F3D5C;"> -->
  <div class="content-text">
    <p>Dear {{ $user }}</p>
    <p>We received a request to log in to your account on TEI e-Procurement. Please use the One-Time Password (OTP) below to complete your login:</p>
    <p><span>{{$otp}}</span></p>
    <p>This OTP is valid for the next 5 minutes. For your security, please do not share this code with anyone.</p>
    <p>If you did not request this login, please contact our support team immediately.</p>
  </div>
  {{-- <div class="content-button" style="text-align: center; margin-bottom: 30px">
    <a href="{{ $link }}">REGISTER</a>
  </div> --}}

  <div class="content-links">
    <p><a href="">Supplier Registration and Basic Navigation</a></p>
    <p><a href="">FAQs</a></p>
  </div>

  <div class="content-helpdesk">
    <span>TEI HELPDESK</span>
  </div>
  <!-- <hr style="border: 1px solid #0F3D5C"> -->
  <div class="content-contact">
    <p><span style="display: block;">For assistance, call or email:</span></p>
    <p><span style="display: block;">Phone No: (045) 606-1834</span></p>
    <p><span>Email: <span style=" text-decoration: underline; color: blue; cursor: pointer;">email@teiph.com</span></span></p>
  </div>
</body>
