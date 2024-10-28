<!DOCTYPE html>
<html lang="ru">

<head>
    <!-- HEADER CON -->
    <?php include 'includes/header.html'; ?>
    <!-- DB CON -->
    <?php include 'includes/db.php'; ?>
    <!--STYLES slider -->
    <link rel="stylesheet" href="./css/styles.css">
    <!--STYLE slider -->
    <link rel="stylesheet" href="./css/slider.css">
    <link href="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/css/swiffy-slider.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/js/swiffy-slider.min.js" crossorigin="anonymous" defer></script>
</head>



<body>
    <!--==============================-->
    <!--BANNER -->
    <!--==============================-->
    <div class="banner">
        <video src="./assets/files/bgvid.mp4" type="video/mp4" autoplay muted loop></video>
        <div class="content" id="home">

            <!-- NAVIGATION PANEL -->
            <nav>
                <img src="./assets/files/logo2.png" class="logo" alt="Логотип" title="FirstFlight Travels" style="width: 8%; height: 8%;">
                <ul class="navbar">
                    <li>
                        <a href="#home">Главная</a>
                        <a href="#pacanc">Пакеты</a>
                        <a href="#locanc">Локации</a>
                        <a href="https://www.discoverkyrgyzstan.org/ru">О нас</a>
                        <a href="https://www.discoverkyrgyzstan.org/ru/contacts">Свяжитесь с нами</a>
                    </li>
                </ul>
            </nav>

            <!-- DESCRIPTION -->
            <div class="title">
                <h1>kgtrip</h1>
                <p>Ваш надежный проводник в неизведанный мир Кыргызстана.</p>
                <p class="split-text">Горы, озера, культура и адреналин ждут вас.</p>
            </div>
        </div>
    </div>



    <!--==============================-->
    <!-- Services section -->
    <!--==============================-->
    <section class="container">
        <div class="text">
            <h2>Мы предлагаем лучшие услуги</h2>
        </div>
        <div class="rowitems">
        <div id="pacanc" style="height: 0; visibility: hidden;"></div>


            <?php
            // Fetch services data from the database
            $sql = "SELECT img, srv, txt FROM services";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Loop through services and display them
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="container-box">';
                    echo '    <div class="container-image">';
                    echo '        <img src="' . $row["img"] . '" alt="' . $row["srv"] . '">';
                    echo '    </div>';
                    echo '    <h4>' . $row["srv"] . '</h4>';
                    echo '    <p>' . $row["txt"] . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No services available</p>';
            }
            ?>
        </div>
    </section>


    <!--==============================-->
    <!-- PACKAGE Section -->
    <!--==============================-->
    <section class="package" id="package">
        <div class="package-title">
            <h2>Пакеты</h2>
        </div>
        <div class="package-content">
            <div class="text-block">
                <h3>Самые продаваемые</h3>
            </div>
            <div id="locanc" style="height: 0; visibility: hidden;"></div>
            

            <?php
            // Fetch packages from the database
            $sql = "SELECT img, price FROM packages";
            $result = $conn->query($sql);


            if ($result->num_rows > 0) {
                // Loop through each package and display them
                while ($row = $result->fetch_assoc()) {
                    echo '<a href="./package.html#' . $row["price"] . '">';
                    echo '    <div class="box">';
                    echo '        <div class="image">';
                    echo '            <img src="' . $row["img"] . '" alt="Package Image">';
                    echo '            <h3>Начиная с ' . number_format($row["price"], 0, '.', ',') . ' $</h3>';
                    echo '        </div>';
                    echo '    </div>';
                    echo '</a>';
                }
            } else {
                echo '<p>No packages available</p>';
            }

            ?>
        </div>
    </section>


    </br>
    </br>

     <!--==============================-->
     <!-- LOCATIONS -->
     <!--==============================-->
    
     <section class="locations" id="locations">
        <div class="package-title">
            <h2>Локации</h2>
        </div>
        <div class="location-content">
            <?php
            // Check if the connection is still open
            if ($conn) {
                // Fetch locations from the database
                $sql = "SELECT img, name FROM locations";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Loop through each location and display them
                    while ($row = $result->fetch_assoc()) {
                        echo '<a href="./locations.html">';
                        echo '    <div class="col-content">';
                        echo '        <img src="' . $row["img"] . '" alt="' . $row["name"] . '">';
                        echo '        <h5>' . $row["name"] . '</h5>';
                        echo '    </div>';
                        echo '</a>';
                    }
                } else {
                    echo '<p>No locations available</p>';
                }
            } else {
                echo '<p>Database connection failed</p>';
            }
            ?>
        </div>
    </section>



    <!--==============================-->
    <!-- SLIDER Swiffy Implementation -->
    <!--==============================-->
    <div class="slider-container-wrapper">
            <div class="swiffy-slider slider-nav-visible slider-indicators-square slider-nav-dark slider-nav-sm">
                <ul class="slider-container">
                    <?php
                    // Fetch slider images from the database
                    $sql = "SELECT img FROM slider";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Loop through each slider image and display it
                        while ($row = $result->fetch_assoc()) {
                            echo '<li><img src="' . $row["img"] . '" alt="Slider Image"></li>';
                        }
                    } else {
                        echo '<p>No images available for the slider</p>';
                    }
                    ?>
                </ul>

                <!-- Slider Navigation -->
                <button type="button" class="slider-nav slider-nav-prev"></button>
                <button type="button" class="slider-nav slider-nav-next"></button>

                <!-- Slider Indicators -->
                <div class="slider-indicators">
                    <?php
                    // LOOP Dynamically generate slider indicators based on the number of images
                    for ($i = 0; $i < $result->num_rows; $i++) {
                        if ($i == 0) {
                            echo '<button class="active"></button>';
                        } else {
                            echo '<button></button>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>


    <!-- FOOTER CON -->
    <?php include 'includes/footer.html'; ?>
</body>

</html>