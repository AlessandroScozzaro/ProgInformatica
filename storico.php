<?php
$conn = new mysqli("localhost","root","mysql","prog_inf");

$res=$conn->query("SELECT * FROM dati_sensori ORDER BY data DESC");
?>

<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800">Storico Sensori</h1>

<table class="table table-bordered">

<tr>
<th>Temperatura</th>
<th>Umidità</th>
<th>MQ135</th>
<th>Data</th>
</tr>

<?php

while($row=$res->fetch_assoc()){

echo "<tr>";
echo "<td>".$row['temperatura']."</td>";
echo "<td>".$row['umidita']."</td>";
echo "<td>".$row['mq135']."</td>";
echo "<td>".$row['data']."</td>";
echo "</tr>";

}

?>

</table>

</div>