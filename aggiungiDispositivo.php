<?php
require_once 'lib/conn.php';
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
$result = $conn->prepare("SELECT id_stanza, nome FROM stanze");
$result->execute();

?>
<html>

<head>
    <meta charset="utf-8">
    <title>Aggiungi Dispositivo</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>
    <h1 style="text-align: center;">Aggiungi Nuovo Dispositivo</h1><br>

    <div style="width: 400px; margin: auto; padding: 20px; border: 1px solid #cccccc; border-radius: 5px;">
        <form action="insertDispositivo.php" method="post">
            <label for="nome">Nome Dispositivo:</label><br>
            <input type="text" id="nome" name="nome" required><br><br>
            <label for="stanza">Stanza:</label><br>
            <select id="stanza" name="stanza" required>
                <option value="">Seleziona Stanza</option>
                <?php
                if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) {
                        echo "<option value='" . $row['id_stanza'] . "'>" . $row['nome'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nessuna stanza disponibile</option>";
                }
                ?>
            </select><br><br>
            <label for="tipo">Tipo Dispositivo:</label><br>
            <select id="tipo" name="tipo" required>
                <option value="Sensore">Sensore</option>
                <option value="Attuatore">Attuatore</option>
            </select><br><br>
            <label for="unita_misura">Unità di Misura:</label><br>
            <select id="unita_misura" name="unita_misura" required>
                <option value="">Seleziona Unità di Misura</option>
                <option value="°C">°C</option>
                <option value="%">%</option>
                <option value="Lux">Lux</option>
            </select><br><br>
            <label for="soglia_minima">Soglia Minima:</label><br>
            <input type="number" id="soglia_minima" name="soglia_minima" step="0.01" required><br><br>
            <label for="soglia_massima">Soglia Massima:</label><br>
            <input type="number" id="soglia_massima" name="soglia_massima" step="0.01" required><br><br>
            <input type="submit" value="Aggiungi Dispositivo">
        </form>
    </div>
</body>

</html>