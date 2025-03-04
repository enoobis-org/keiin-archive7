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

<!--STYLES FOR CP_BUTTON-->
<style>
        .custom-button {
            background-color: gray; 
            border-radius: 50%;
            padding: 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
            transition: background-color 0.3s; 
        }
        .custom-button img {
            width: 20px;
            height: 20px;
        }
        .active-button {
            background-color: red !important;
        }

        nav {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: white;
    z-index: 10000; /* Highest priority */
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    padding: 10px 0;
}

.country-button {
    position: relative;
    z-index: -100; /* Lower than nav */
}
</style>


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
            <div class="custom-button" onclick="toggleTextStyle(this)">
                <img src="./assets/files/eye.png" alt="Button Icon">
            </div>
            <li>
                <a href="#home">Главная</a>
                <a href="#pacanc">Пакеты</a>
                <a href="#locanc">Локации</a>
                <a href="https://www.discoverkyrgyzstan.org/ru">О нас</a>
                <a href="https://www.discoverkyrgyzstan.org/ru/contacts">Свяжитесь с нами</a>
            </li>
        </ul>
    </nav>


<!-- SCRIPT FOR CP_button  -->
<script>
        let isActive = false; // Initial state

        function toggleTextStyle(button) {
            isActive = !isActive; // Toggle state

            // Toggle button color
            button.classList.toggle("active-button", isActive);

            // Apply or remove red and bold styling on all text elements
            document.querySelectorAll('*').forEach(element => {
                element.style.color = isActive ? 'red' : ''; // Set to red if active, otherwise default
                element.style.fontWeight = isActive ? 'bold' : ''; // Set to bold if active, otherwise default
            });
        }
</script>


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
                    echo '<a href="https://ru.wikipedia.org/wiki/%D0%98%D1%81%D1%81%D1%8B%D0%BA-%D0%9A%D1%83%D0%BB%D1%8C">';
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



<!--==============================-->
<!-- Countries -->
<!--==============================-->
    <div class="package-title">
        <h2>Страны</h2>
    </div>

<div class="countries-wrapper">
    <div class="countries-container">
        <!-- GEN CONT BUTTONS IN DB  -->
        <?php



        // CONNECT data FROM 1st AND 2nd DB : JOIN
        $sql = "SELECT ncountries.id AS nc_id, ncountries.name, icountries.img1, icountries.img2, icountries.img3 
                FROM ncountries
                JOIN icountries ON ncountries.name = icountries.name";
        $result = $conn->query($sql);
        $defaultCountry = "Кыргызстан"; // KG DEF

        if ($result->num_rows > 0) {
            // CREATE BUTTON FOR EACH CONT
            while($row = $result->fetch_assoc()) {
                $activeClass = ($row['name'] == $defaultCountry) ? "active" : ""; // KG ACTIVE AS DEF 
                echo "<button class='country-button $activeClass' onclick=\"toggleCountry(this, 'country_" . $row['nc_id'] . "')\">" . $row['name'] . "</button>";
            }
        } else {
            echo "Нет доступных стран.";
        }
        ?>



    </div>
    <!-- DONT KNOW I FORGOT  -->
    <div class="line"></div>

    <!-- CIMG CONTAINER -->
    <div class="images-container">
        <?php
        // RESET RESULT
        $result->data_seek(0);

        if ($result->num_rows > 0) {
            // GEN IMG IN CONTAINERS
            while($row = $result->fetch_assoc()) {
                $activeClass = ($row['name'] == $defaultCountry) ? "active" : ""; // ACTIVE CLASS FOR KG 
                echo "<div id='country_" . $row['nc_id'] . "' class='images $activeClass'>";
                echo "<img src='" . $row['img1'] . "' alt='Image 1'>";
                echo "<img src='" . $row['img2'] . "' alt='Image 2'>";
                echo "<img src='" . $row['img3'] . "' alt='Image 3'>";
                echo "</div>";
            }
        }
        ?>
    </div>
</div>

<!-- STYLE OR CONTAINER -->
<style>
    .countries-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
    }
    .countries-container {
        display: flex;
        justify-content: space-around;
        width: 100%;
        max-width: 1200px;
        margin-bottom: 10px;
        position: relative;
    }
    .country-button {
        background-color: #32CD32;
        color: white;
        border: none;
        border-radius: 20px;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 1em;
        transition: background-color 0.3s;
        position: relative;
        z-index: 0;
    }
    .country-button:hover {
        background-color: #28a428;
    }
    .country-button.active {
        background-color: #28a428;
    }
    
    /* CONTAINER WITH IMG */
    .images-container {
        display: flex;
        justify-content: center;
        width: 100%;
        max-width: 1000px;
        padding: 20px;
        background: linear-gradient(to bottom, #30ff33, #3037ff);
        border-radius: 20px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3); /* SHADOW */
    }
    .images {
        display: none;
        flex-direction: row;
        justify-content: space-between;
        width: 100%;
        gap: 10px;
    }
    .images img {
        flex: 1;
        max-width: calc(100% / 3 - 10px);
        height: auto;
        border-radius: 5px;
    }
    .images.active {
        display: flex;
    }
</style>

<script>
    function toggleCountry(button, countryId) {
        // HIDE ACTIVE FOR EVERY BUTTON CONT 
        document.querySelectorAll('.country-button').forEach(btn => {
            btn.classList.remove('active');
        });

        // ADD ACTIVE TO SELECTED BUTTON
        button.classList.add('active');

        //  HIDE ACTIVE FOR IMG 
        document.querySelectorAll('.images').forEach(images => {
            images.classList.remove('active');
        });
        
        // ADD ACTIVE CLASS FOR SELECTED CONT
        const selectedImages = document.getElementById(countryId);
        if (selectedImages) {
            selectedImages.classList.add('active');
        }
    }
</script>

<?php
// DB CLOSE
$conn->close();
?>

    <!-- FOOTER CON -->
    <?php include 'includes/footer.html'; ?>
</body>

</html>