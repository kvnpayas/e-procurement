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
      margin-top: 20px;
    }

    .content-helpdesk span {
      font-weight: bold;
      color: #E76727;
    }

    .content-contact {
      /* margin-top: 50px; */
    }

    .content-contact span:nth-child(1) {
      font-style: italic;
    }
  </style>
</head>

<body>
  <div class="content-text">
    <p>Dear Sir/Mam,</p>
    <p>Greetings!</p>
    <p>
      The submission of bids for <span style="text-decoration: underline; font-weight:bolder">{{ $bidding->project_id }}
        - {{ $bidding->title }}</span> has officially closed on {{ date('F j, Y, g:i A', strtotime(now())) }} and is now ready for
      evaluation.
    </p>
    <p>
      Here’s the list of suppliers who submitted their bids:
    </p>
    <div style="margin-bottom: 20px">
      <table style="border-collapse: collapse; width: 100%;">
        <thead>
          <tr>
            <th style="border: 1px solid black;">Order</th>
            <th style="border: 1px solid black;">Submission Date and Time</th>
            <th style="border: 1px solid black;">Bidder Name</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($datas as $index =>  $data)
            <tr>
              <td style="border: 1px solid black; text-align: center;">{{ $index + 1 }}</td>
              <td style="border: 1px solid black; text-align: center;">{{ $data['submission_date'] ? date('F j, Y, g:i A', strtotime($data['submission_date'])) : '' }}</td>
              <td style="border: 1px solid black; text-align: center;">{{ $data['vendor'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <p>
      This is a system-generated email, please do not reply.
    </p>
  </div>
  <div class="content-helpdesk">
    <span>TEI HELPDESK</span>
  </div>
  <div class="content-contact">
    <div>
      <span style="display: block;">For Technical Concerns, call or email:</span>
    </div>
    <div>
      <span style="display: block;">Phone No.: 045-606-1834</span>
    </div>
    <div>
      <span>Email: <span style=" text-decoration: underline; color: blue; cursor: pointer;">email@teiph.com</span></span>
    </div>
  </div>
</body>
