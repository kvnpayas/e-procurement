<table>
  <thead>
    <tr>
      <td colspan="3">Date: {{ date('Y-m-d', strtotime(now())) }}</td>
      <td rowspan="3" colspan="14" valign="center" align="center">
        Tarlac Electric Inc.
      </td>
      <td colspan="3" align="right">Company: TEI</td>
    </tr>
    <tr>
      <td colspan="3">Time: {{ date('h:i A', strtotime(now())) }}</td>
      <td colspan="3" align="right"></td>
    </tr>
    <tr>
      <td colspan="3">User: {{ Auth::user()->name }}</td>
      <td colspan="3"></td>
    </tr>
    <tr>
      <td colspan='20'></td>
    </tr>
  </thead>
</table>
