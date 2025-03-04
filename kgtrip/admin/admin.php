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

// Handle file upload for 'upload' tab
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload']) && isset($_FILES['fileToUpload'])) {
    $targetDirectory = '../assets/files/';
    $targetFile = $targetDirectory . basename($_FILES['fileToUpload']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file is an actual image
    $check = getimagesize($_FILES['fileToUpload']['tmp_name']);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "File already exists.";
        $uploadOk = 0;
    }

    // Limit file size (optional)
    if ($_FILES['fileToUpload']['size'] > 5000000) {
        echo "File is too large.";
        $uploadOk = 0;
    }

    // Allow specific file formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    // Try to upload if all checks pass
    if ($uploadOk) {
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile)) {
            echo "File ". htmlspecialchars(basename($_FILES['fileToUpload']['name'])). " has been uploaded.";
        } else {
            echo "Error uploading your file.";
        }
    }
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

// Function to get image files from assets directory
function getImageFiles() {
    $directory = '../assets/files/';
    $files = array_diff(scandir($directory), array('.', '..'));
    $imageFiles = [];
    foreach ($files as $file) {
        if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
            $imageFiles[] = $file;
        }
    }
    return $imageFiles;
}
// ------------
// countries 
// ------------

if (isset($_GET['tab']) && $_GET['tab'] == 'cont') {
    // Определить, какая таблица используется
    $table = $_GET['table'] ?? 'ncountries';

    // Загрузка данных из базы
    if ($table == 'ncountries') {
        $sql = "SELECT * FROM ncountries";
    } elseif ($table == 'icountries') {
        $sql = "SELECT ic.id, ic.img, ic.txt, n.name AS nc_name 
                FROM icountries ic 
                JOIN ncountries n ON ic.nc_id = n.id";
    } else {
        $sql = "SELECT * FROM ncountries"; // По умолчанию
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Удаление записи
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        if ($table == 'ncountries') {
            // Удалить связанные записи в icountries
            $stmt = $pdo->prepare("DELETE FROM icountries WHERE nc_id = :id");
            $stmt->execute(['id' => $id]);
        }
        $stmt = $pdo->prepare("DELETE FROM $table WHERE id = :id");
        $stmt->execute(['id' => $id]);
        header("Location: admin.php?tab=cont&table=$table");
        exit();
    }

    // Копирование записи
    if (isset($_GET['copy'])) {
        $id = $_GET['copy'];
        if ($table == 'ncountries') {
            $stmt = $pdo->prepare("INSERT INTO ncountries (name) SELECT name FROM ncountries WHERE id = :id");
        } elseif ($table == 'icountries') {
            $stmt = $pdo->prepare("INSERT INTO icountries (img, txt, nc_id) SELECT img, txt, nc_id FROM icountries WHERE id = :id");
        }
        $stmt->execute(['id' => $id]);
        header("Location: admin.php?tab=cont&table=$table");
        exit();
    }

    // Добавление/изменение записи
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($table == 'ncountries') {
            $name = $_POST['name'];
            if (isset($_POST['add_item'])) {
                $stmt = $pdo->prepare("INSERT INTO ncountries (name) VALUES (:name)");
                $stmt->execute(['name' => $name]);
            } elseif (isset($_POST['edit_item'])) {
                $id = $_POST['id'];
                $stmt = $pdo->prepare("UPDATE ncountries SET name = :name WHERE id = :id");
                $stmt->execute(['name' => $name, 'id' => $id]);
            }
        } elseif ($table == 'icountries') {
            $img = $_POST['img'];
            $txt = $_POST['txt'];
            $nc_id = $_POST['nc_id'];
            // Проверка существования записи в ncountries
            $stmt = $pdo->prepare("SELECT id FROM ncountries WHERE id = :id");
            $stmt->execute(['id' => $nc_id]);
            if ($stmt->rowCount() == 0) {
                echo "Ошибка: Сначала создайте запись в ncountries.";
                exit();
            }
            if (isset($_POST['add_item'])) {
                $stmt = $pdo->prepare("INSERT INTO icountries (img, txt, nc_id) VALUES (:img, :txt, :nc_id)");
                $stmt->execute(['img' => $img, 'txt' => $txt, 'nc_id' => $nc_id]);
            } elseif (isset($_POST['edit_item'])) {
                $id = $_POST['id'];
                $stmt = $pdo->prepare("UPDATE icountries SET img = :img, txt = :txt, nc_id = :nc_id WHERE id = :id");
                $stmt->execute(['img' => $img, 'txt' => $txt, 'nc_id' => $nc_id, 'id' => $id]);
            }
        }
        header("Location: admin.php?tab=cont&table=$table");
        exit();
    }
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
        <a href="admin.php?tab=cont"><img src="../assets/files/cont.png" alt="Countries Icon"> Страны</a>
        <a href="admin.php?tab=upload"><img src="../assets/files/upload.png" alt="Upload Icon"> Загрузить</a>
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
                <?php if ($_GET['tab'] == 'slider'): ?>
                    <label for="img">Изображение:</label>
                    <select name="img" id="img" required>
                        <option value="">Выберите изображение</option>
                        <?php foreach (getImageFiles() as $file): ?>
                            <option value="<?= "assets/files/$file"; ?>"><?= $file; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <label for="img">Изображение:</label>
                    <input type="text" name="img" id="img" placeholder="Введите путь к изображению" required>
                <?php endif; ?>

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
        } elseif (isset($_GET['tab']) && $_GET['tab'] == 'upload') {
            // Display the file upload form
        ?>
            <h1>Загрузить файл</h1>
            <form action="admin.php?tab=upload" method="POST" enctype="multipart/form-data">
                <label for="fileToUpload">Выберите файл для загрузки:</label>
                <input type="file" name="fileToUpload" id="fileToUpload" required>
                <button type="submit" name="upload" class="add-btn">Загрузить</button>
            </form>
        <?php
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


