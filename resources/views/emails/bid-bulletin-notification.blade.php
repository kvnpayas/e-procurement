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
    <p>Dear Bidder,</p>
    <p>Greetings!</p>
    <p>
      This is to inform you that a Supplemental Bid Bulletin [No. {{ $bulletin['count_bulletin'] }}] for <span
        style="text-decoration: underline; font-weight:bolder">{{ $bidding->project_id }} - {{ $bidding->title }}</span>
      has been posted on {{ date('F j, Y, g:i A', strtotime(now())) }} at the TEI e-Procurement Portal. Please log in to
      your account to review the latest updates and
      ensure you are up to date with all the relevant information about the project.
    </p>
    <p>
      Should you have any questions or require further information, please do not hesitate to contact us directly.
    </p>

    <p>
      Respectfully,
    </p>
    <p>
      Bids and Awards Committee Chairman (BAC-Chairman)
    </p>
  </div>
  <div class="content-contact">
    <div>
      <span style="display: block;">For Bidding Concerns, call or email:</span>
    </div>
    <div>
      <span style="display: block;">Landline: 045-606-8347</span>
    </div>
    <div>
      <span>Email: <span
          style=" text-decoration: underline; color: blue; cursor: pointer;">tei_purchasing@teiph.com</span></span>
    </div>
  </div>
  <div class="content-contact">
    <div>
      <span style="display: block;">For Technical Concerns, call or email:</span>
    </div>
    <div>
      <span style="display: block;">Phone No.: 045-606-1834</span>
    </div>
    <div>
      <span>Email: <span
          style=" text-decoration: underline; color: blue; cursor: pointer;">email@teiph.com</span></span>
    </div>
  </div>
</body>
