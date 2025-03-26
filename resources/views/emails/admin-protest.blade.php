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

    .content-text ul li span{
      font-weight: bold;
      color: #E76727;
    }

    .content-text ul li span span{
      font-weight: normal;
      color: #0F3D5C;
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
  <div class="email-header">
    <h1>Welcome</h1>
    <img src="{{ url('img/tei_logo.png') }}" alt="TEI LOGO" height="120">
  </div>
  <!-- <hr style="border: 1px solid #0F3D5C;"> -->
  <div class="content-text">
    <p>Dear BAC,</p>
    <p>We have received a protest filed by one of our vendors regarding a recent bid award. Below are the details:</p>
    <p>
    <ul>
      <li>
        <span>Vendor Name: <span>{{ $name }}</span></span>
      </li>
      <li>
        <span>Bid Reference: <span>{{ $bidReference }}</span></span>
      </li>
      <li>
        <span>Bid Title: <span>{{ $bidTitle }}</span></span>
      </li>
      <li>
        <span>Protest Message: </span>
        <div style="border-style: solid; border-width: thin; border-color: #dcdcdc; padding: 10px;font-size: 15px;font-style: italic;margin-top:0px;margin-bottom:10px;">
          @foreach ($messages as $message)
            <p style="margin: 0px; color:#57575B;">{{ $message }}</p>
          @endforeach
        </div>
      </li>
    </ul>
    </p>
    <p>Please review the protest as soon as possible and take the necessary actions. You can access the full details and
      respond through the <span><a href="{{$link}}">Pending Protest</a></span> menu.</p>
    <p>Thank you for your prompt attention to this matter.</p>
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
    <p><span>Email: <span
          style=" text-decoration: underline; color: blue; cursor: pointer;">email@teiph.com</span></span></p>
  </div>
</body>
