<center>
<h3><?=$navbar['www_report'];?></h3>
<a href=index.php?module=Balance&action=hosts&day=<?=$_GET['day'];?>&subaction=bigfiles><?=$navbar['big_files'];?></a>
</center>

<table border=0 cellpadding=2 cellspacing=0 width=50% align=center>
  <tbody>
    <tr align = left>
    <td>
    <b><?=$l_tables['sum_all'];?></>:</b> <?=$main_rep_total;?>
    </td>
    </tr>
</table>
<br>

<table border=1 cellpadding=2 cellspacing=0 bordercolor=#111111 width=50% align=center>
  <tbody>
    <tr bgColor=c0c0c0 align = center>

      <td width=60%><strong><?=$l_tables['url'];?></strong></td>
      <td width=20%><strong><?=$l_tables['connects'];?></strong></td>
      <td width=20%><strong><?=$l_tables['bytes'];?></strong></td>