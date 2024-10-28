<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once '../includes/db.php';

// Fetch data based on the selected tab
if (isset($_GET['tab']) && $_GET['tab'] == 'services') {
    $sql = "SELECT * FROM services";
} elseif (isset($_GET['tab']) && $_GET['tab'] == 'packages') {
    $sql = "SELECT * FROM packages";
} elseif (isset($_GET['tab']) && $_GET['tab'] == 'locations') {
    $sql = "SELECT * FROM locations";
} elseif (isset($_GET['tab']) && $_GET['tab'] == 'slider') {
    $sql = "SELECT * FROM slider";
} else {
    $sql = "SELECT * FROM services"; // Default to services if no tab is selected
}

$stmt = $pdo->prepare($sql);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle delete requests for services, packages, locations, and slider
if (isset($_GET['delete']) && isset($_GET['tab'])) {
    $id = $_GET['delete'];
    $table = $_GET['tab'];
    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: admin.php?tab=" . $_GET['tab']);
    exit();
}

// Handle copy requests for services, packages, locations, and slider
if (isset($_GET['copy']) && isset($_GET['tab'])) {
    $id = $_GET['copy'];
    $table = $_GET['tab'];
    if ($table == 'services') {
        $stmt = $pdo->prepare("INSERT INTO services (img, srv, txt) SELECT img, srv, txt FROM services WHERE id = :id");
    } elseif ($table == 'packages') {
        $stmt = $pdo->prepare("INSERT INTO packages (img, price) SELECT img, price FROM packages WHERE id = :id");
    } elseif ($table == 'locations') {
        $stmt = $pdo->prepare("INSERT INTO locations (img, name) SELECT img, name FROM locations WHERE id = :id");
    } elseif ($table == 'slider') {
        $stmt = $pdo->prepare("INSERT INTO slider (img) SELECT img FROM slider WHERE id = :id");
    }
    $stmt->execute(['id' => $id]);
    header("Location: admin.php?tab=" . $_GET['tab']);
    exit();
}

// Handle form submission for adding or editing a service, package, location, or slider
if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['add_item']) || isset($_POST['edit_item']))) {
    $img = $_POST['img'];
    if ($_GET['tab'] == 'services') {
        $srv = $_POST['srv'];
        $txt = $_POST['txt'];
    } elseif ($_GET['tab'] == 'packages') {
        $price = $_POST['price'];
    } elseif ($_GET['tab'] == 'locations') {
        $name = $_POST['name'];
    }

    if (isset($_POST['add_item'])) {
        if ($_GET['tab'] == 'services') {
            $stmt = $pdo->prepare("INSERT INTO services (img, srv, txt) VALUES (:img, :srv, :txt)");
            $stmt->execute(['img' => $img, 'srv' => $srv, 'txt' => $txt]);
        } elseif ($_GET['tab'] == 'packages') {
            $stmt = $pdo->prepare("INSERT INTO packages (img, price) VALUES (:img, :price)");
            $stmt->execute(['img' => $img, 'price' => $price]);
        } elseif ($_GET['tab'] == 'locations') {
            $stmt = $pdo->prepare("INSERT INTO locations (img, name) VALUES (:img, :name)");
            $stmt->execute(['img' => $img, 'name' => $name]);
        } elseif ($_GET['tab'] == 'slider') {
            $stmt = $pdo->prepare("INSERT INTO slider (img) VALUES (:img)");
            $stmt->execute(['img' => $img]);
        }
    } elseif (isset($_POST['edit_item'])) {
        $id = $_POST['id'];
        if ($_GET['tab'] == 'services') {
            $stmt = $pdo->prepare("UPDATE services SET img = :img, srv = :srv, txt = :txt WHERE id = :id");
            $stmt->execute(['img' => $img, 'srv' => $srv, 'txt' => $txt, 'id' => $id]);
        } elseif ($_GET['tab'] == 'packages') {
            $stmt = $pdo->prepare("UPDATE packages SET img = :img, price = :price WHERE id = :id");
            $stmt->execute(['img' => $img, 'price' => $price, 'id' => $id]);
        } elseif ($_GET['tab'] == 'locations') {
            $stmt = $pdo->prepare("UPDATE locations SET img = :img, name = :name WHERE id = :id");
            $stmt->execute(['img' => $img, 'name' => $name, 'id' => $id]);
        } elseif ($_GET['tab'] == 'slider') {
            $stmt = $pdo->prepare("UPDATE slider SET img = :img WHERE id = :id");
            $stmt->execute(['img' => $img, 'id' => $id]);
        }
    }

    header("Location: admin.php?tab=" . $_GET['tab']);
    exit();
}

// Fetch a single item for editing if needed
$itemToEdit = null;
if (isset($_GET['edit']) && isset($_GET['tab'])) {
    $id = $_GET['edit'];
    $table = $_GET['tab'];
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $itemToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <!-- Sidebar menu -->
    <div class="sidebar">
        <a href="admin.php?tab=main"><img src="../assets/files/home.png" alt="Home Icon"> Главная</a>
        <a href="admin.php?tab=services"><img src="../assets/files/plane.png" alt="Services Icon"> Услуги</a>
        <a href="admin.php?tab=packages"><img src="../assets/files/package.png" alt="Packages Icon"> Пакеты</a>
        <a href="admin.php?tab=locations"><img src="../assets/files/mountains.png" alt="Locations Icon"> Локации</a>
        <a href="admin.php?tab=slider"><img src="../assets/files/slider.png" alt="Slider Icon"> Слайдер</a>
        <a href="../" class="logout"><img src="../assets/files/exit.png" alt="Logout Icon"> Выход</a>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <?php
        // Display content based on the selected tab
        if (isset($_GET['tab']) && ($_GET['tab'] == 'services' || $_GET['tab'] == 'packages' || $_GET['tab'] == 'locations' || $_GET['tab'] == 'slider')) {
            if (isset($_GET['action']) && $_GET['action'] == 'add') {
                // Add form
                $formTitle = ($_GET['tab'] == 'services') ? 'Добавить Услугу' : 
                             (($_GET['tab'] == 'packages') ? 'Добавить Пакет' : 
                             (($_GET['tab'] == 'locations') ? 'Добавить Локацию' : 'Добавить Слайдер'));
        ?>
            <h1><?= $formTitle ?></h1>
            <form action="admin.php?tab=<?= $_GET['tab']; ?>&action=add" method="POST">
                <label for="img">Изображение:</label>
                <input type="text" name="img" id="img" placeholder="Введите путь к изображению" required>
                <?php if ($_GET['tab'] == 'services'): ?>
                    <label for="srv">Название Сервиса:</label>
                    <input type="text" name="srv" id="srv" placeholder="Введите название сервиса" required>

                    <label for="txt">Описание Сервиса:</label>
                    <input type="text" name="txt" id="txt" placeholder="Введите описание сервиса" required>
                <?php elseif ($_GET['tab'] == 'packages'): ?>
                    <label for="price">Цена:</label>
                    <input type="number" step="0.01" name="price" id="price" placeholder="Введите цену" required>
                <?php elseif ($_GET['tab'] == 'locations'): ?>
                    <label for="name">Название Локации:</label>
                    <input type="text" name="name" id="name" placeholder="Введите название локации" required>
                <?php endif; ?>

                <button type="submit" name="add_item" class="add-btn">Добавить</button>
                <a href="admin.php?tab=<?= $_GET['tab']; ?>" class="back-btn">Назад</a>
            </form>
        <?php
            } elseif (isset($_GET['edit']) && $itemToEdit) {
                // Edit form
                $formTitle = ($_GET['tab'] == 'services') ? 'Изменить Услугу' : 
                             (($_GET['tab'] == 'packages') ? 'Изменить Пакет' : 
                             (($_GET['tab'] == 'locations') ? 'Изменить Локацию' : 'Изменить Слайдер'));
        ?>
            <h1><?= $formTitle ?></h1>
            <form action="admin.php?tab=<?= $_GET['tab']; ?>&edit=<?= $itemToEdit['id']; ?>" method="POST">
                <input type="hidden" name="id" value="<?= $itemToEdit['id']; ?>">
                <label for="img">Изображение:</label>
                <input type="text" name="img" id="img" value="<?= $itemToEdit['img']; ?>" required>
                <?php if ($_GET['tab'] == 'services'): ?>
                    <label for="srv">Название Сервиса:</label>
                    <input type="text" name="srv" id="srv" value="<?= $itemToEdit['srv']; ?>" required>

                    <label for="txt">Описание Сервиса:</label>
                    <input type="text" name="txt" id="txt" value="<?= $itemToEdit['txt']; ?>" required>
                <?php elseif ($_GET['tab'] == 'packages'): ?>
                    <label for="price">Цена:</label>
                    <input type="number" step="0.01" name="price" id="price" value="<?= $itemToEdit['price']; ?>" required>
                <?php elseif ($_GET['tab'] == 'locations'): ?>
                    <label for="name">Название Локации:</label>
                    <input type="text" name="name" id="name" value="<?= $itemToEdit['name']; ?>" required>
                <?php endif; ?>

                <button type="submit" name="edit_item" class="add-btn">Сохранить</button>
                <a href="admin.php?tab=<?= $_GET['tab']; ?>" class="back-btn">Назад</a>
            </form>
        <?php
            } else {
                // Display the list of services, packages, locations, or sliders
                $listTitle = ($_GET['tab'] == 'services') ? 'Услуги' : 
                             (($_GET['tab'] == 'packages') ? 'Пакеты' : 
                             (($_GET['tab'] == 'locations') ? 'Локации' : 'Слайдер'));
        ?>
            <h1><?= $listTitle ?></h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>IMG</th>
                        <?php if ($_GET['tab'] == 'services'): ?>
                            <th>SRV</th>
                            <th>TXT</th>
                        <?php elseif ($_GET['tab'] == 'packages'): ?>
                            <th>PRICE</th>
                        <?php elseif ($_GET['tab'] == 'locations'): ?>
                            <th>NAME</th>
                        <?php endif; ?>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item['id']; ?></td>
                        <td><?= $item['img']; ?></td>
                        <?php if ($_GET['tab'] == 'services'): ?>
                            <td><?= $item['srv']; ?></td>
                            <td><?= $item['txt']; ?></td>
                        <?php elseif ($_GET['tab'] == 'packages'): ?>
                            <td><?= $item['price']; ?></td>
                        <?php elseif ($_GET['tab'] == 'locations'): ?>
                            <td><?= $item['name']; ?></td>
                        <?php endif; ?>
                        <td>
                            <a href="admin.php?tab=<?= $_GET['tab']; ?>&copy=<?= $item['id']; ?>" class="action-btn">Копировать</a>
                            <a href="admin.php?tab=<?= $_GET['tab']; ?>&delete=<?= $item['id']; ?>" class="action-btn" onclick="return confirm('Are you sure?')">Удалить</a>
                            <a href="admin.php?tab=<?= $_GET['tab']; ?>&edit=<?= $item['id']; ?>" class="action-btn">Изменить</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="admin.php?tab=<?= $_GET['tab']; ?>&action=add" class="add-btn-center">Добавить</a>
        <?php
            }
        } else {
            // Default content when 'Главная' is selected
        ?>
            <h1>ГЛАВНАЯ</h1>
            <img src="../assets/files/admin.jpg" alt="Image">
        <?php
        }
        ?>
    </div>
</body>
</html>
