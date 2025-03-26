<!DOCTYPE html>
<html>

<head>
  <title>Registration for e-Procurement</title>
  <style>
    /* .email-header {
      padding: 10px;
      text-align: center;
      color: #0F3D5C;
    } */

    .content-text {
      padding: 10px;
      margin-top: 30px;
      margin-bottom: 30px;
    }

    .content-text p {
      color: #0F3D5C;
      margin-top: 0px
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
    <p>Dear Sir/Madam</p>
    <p>Greetings!</p>
    <p>
      We inform you that the bid process for {{ $bidding->title }} and the winning bidder has been temporarily placed on hold due to a protest
      that is currently under review. We are taking this matter seriously and are working diligently to address the concerns
      raised.
    </p>
    <p>
      We understand the importance of this bid to all parties involved and are committed to resolving the issue as
      quickly as possible. We will provide an update on the status of the bid as soon as more information becomes
      available.
    </p>
    <p>
      Should you have questions or clarifications, pls. do not hesitate to let us know.
    </p>
  </div>
  <div class="content-contact">
    <div>
      <span style="display: block;">For assistance, call or email:</span>
    </div>
    <div>
      <span style="display: block;">Phone No: (045) 606-1834</span>
    </div>
    <div>
      <span>Email: <span style=" text-decoration: underline; color: blue; cursor: pointer;">email@teiph.com</span></span>
    </div>
  </div>
</body>
