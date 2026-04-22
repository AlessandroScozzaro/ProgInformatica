<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

require_once 'lib/conn.php';

try {
    $query = 'SELECT id_misurazione, dispositivi.nome, valore, timestamp FROM misurazioni 
    JOIN dispositivi ON misurazioni.id_dispositivo = dispositivi.id_dispositivo';
    if(isset($_POST['id'])){
        $query .= 'WHERE id_misurazione = :id';
    }
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $misurazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Errore query: ' . $e->getMessage());
}


?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Storico Sensori - Monitoraggio Ambientale</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-microchip"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Monitor Aria</div>
            </a>

            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="stanze.php">
                    <i class="fas fa-building"></i>
                    <span>Gestione Stanze</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="piantina.php">
                    <i class="fas fa-map"></i>
                    <span>Piantina</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="utenti.php">
                    <i class="fas fa-users"></i>
                    <span>Utenti</span>
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="storico.php">
                    <i class="fas fa-chart-line"></i>
                    <span>Storico dati</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="notifiche.php">
                    <i class="fas fa-bell"></i>
                    <span>Notifiche</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="aggiungiDispositivo.php">
                    <i class="fas fa-plus"></i>
                    <span>Aggiungi Dispositivo</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="misurazioni.php">
                    <i class="fas fa-chart-bar"></i>
                    <span>Misurazioni</span>
                </a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                    <h5 class="m-0 font-weight-bold text-primary">Sistema Monitoraggio Ambientale</h5>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#">
                                <a href="logout.php"><span class="mr-2 d-none d-lg-inline text-gray-600 small">Utente</span></a>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Storico Sensori</h1>
                        <a href="index.php" class="btn btn-sm btn-primary"><i class="fas fa-home fa-sm fa-fw"></i> Home</a>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Elenco misurazioni registrate</h6>
                            ///////////////////////////////////////
                            <form action="storico.php" method="post">
                                <div class="form-group">
                                    <label for="nome">ID</label>
                                    <input type="text" class="form-control" id="ID" name="ID" required>
                                </div>
                                <div class="form-group">
                                    <label for="tipo">Sensore</label>
                                    <select class="form-control" id="sensore" name="sensore" required>
                                        <option value="Sensore">Sensore</option>
                                        <option value="Attuatore">Attuatore</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="unita_misura">Unità di misura</label>
                                    <select class="form-control" id="unita_misura" name="unita_misura" required>
                                        <option value="">Seleziona Unità di Misura</option>
                                        <option value="°C">°C</option>
                                        <option value="%">%</option>
                                        <option value="Lux">Lux</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="soglia_minima">Valore</label>
                                    <input type="number" class="form-control" id="valore" name="valore" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Filtra</button>
                            </form>
                            /////////////////////////////////////
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID Misurazione</th>
                                            <th>Dispositivo</th>
                                            <th>Valore</th>
                                            <th>Data/Ora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($misurazioni) > 0): ?>
                                            <?php foreach ($misurazioni as $row): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['id_misurazione']) ?></td>
                                                    <td><?= htmlspecialchars($row['nome']) ?></td>
                                                    <td><?= htmlspecialchars($row['valore']) ?></td>
                                                    <td><?= htmlspecialchars($row['timestamp']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">Nessuna misurazione trovata</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Progetto Monitoraggio Ambientale - 2026</span>
                    </div>
                </div>
            </footer>

        </div>

    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>