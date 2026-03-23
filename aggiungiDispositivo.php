<?php
require_once 'lib/conn.php';
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
?>
<html>
<head>
    <meta charset="utf-8">
    <title>Aggiungi Dispositivo</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
    <form action="insertDispositivo.php" method="post">
        <label for="nome">Nome Dispositivo:</label>
        <input type="text" id="nome" name="nome" required><br><br>
        <label for="stanza">Stanza:</label>
        <select id="stanza" name="stanza" required>
            <option value="">Seleziona Stanza</option>
            <?php
            $sql = "SELECT id, nome FROM stanze";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                }
            }
            ?>
        </select><br><br>
        <label for="tipo">Tipo Dispositivo:</label>
        <select id="tipo" name="tipo" required>
            <option value="">Seleziona Tipo</option>
            <option value="Sensore">Sensore</option>
            <option value="Attuatore">Attuatore</option>
        </select><br><br>
        <label for="unita_misura">Unità di Misura:</label>
        <select id="unita_misura" name="unita_misura" required>
            <option value="">Seleziona Unità di Misura</option>
            <option value="°C">°C</option>
            <option value="%">%</option>
            <option value="Lux">Lux</option>
        </select><br><br>
        <label for="soglia_minima">Soglia Minima:</label>
        <input type="number" id="soglia_minima" name="soglia_minima" step="0.01" required><br><br>
        <label for="soglia_massima">Soglia Massima:</label>
        <input type="number" id="soglia_massima" name="soglia_massima" step="0.01" required><br><br>
        <input type="submit" value="Aggiungi Dispositivo">
    </form>
</body>
</html>