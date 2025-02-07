<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kereső</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/search.css">

    <style>
        body { background-color: #1D1D1D; color: white; font-family: Arial, sans-serif; }
        .search-container { width: 250px; position: fixed; left: 0; top: 50px; background: #ff219e; padding: 15px; }
        .search-container input, .search-container select { width: 100%; padding: 10px; margin: 5px 0; }
        .dropdown-search { position: absolute; top: 10px; right: 20px; }
        .dropdown-content { display: none; position: absolute; background: #32eaff; padding: 10px; width: 250px; }
        .dropdown-search:hover .dropdown-content { display: block; }
    </style>
    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                });
            }
        }
    </script>
</head>
<body onload="getLocation()">
    <div class="search-container">
        <h3>Keresés</h3>
        <form method="GET" action="search_results.php">
            <input type="text" name="keywords" placeholder="Kulcsszó">
            <select name="service">
                <option value="">Szolgáltatás</option>
                <option value="masszazs">Masszázs</option>
                <option value="webcam">Webcam</option>
                <option value="escort">Escort</option>
            </select>
            <input type="text" name="city" placeholder="Város">
            <select name="sexuality">
                <option value="">Szexualitás</option>
                <option value="hetero">Hetero</option>
                <option value="biszex">Biszex</option>
                <option value="meleg">Meleg</option>
                <option value="leszbikus">Leszbikus</option>
            </select>
            <select name="ethnicity">
                <option value="">Etnikum</option>
                <option value="caucasian">Kaukázusi</option>
                <option value="african">Afrikai</option>
                <option value="asian">Ázsiai</option>
                <option value="latin">Latin</option>
            </select>
            <select name="eye_color">
                <option value="">Szemszín</option>
                <option value="blue">Kék</option>
                <option value="brown">Barna</option>
                <option value="green">Zöld</option>
                <option value="gray">Szürke</option>
            </select>
            <input type="number" name="age_min" placeholder="Életkor - tól" min="18">
            <input type="number" name="age_max" placeholder="Életkor - ig" min="18">
            <select name="body_type">
                <option value="">Testalkat</option>
                <option value="thin">Vékony</option>
                <option value="average">Átlagos</option>
                <option value="athletic">Sportos</option>
                <option value="muscular">Izmos</option>
                <option value="curvy">Dundi</option>
            </select>
            <select name="date">
                <option value="">Megjelenés dátuma</option>
                <option value="today">Ma</option>
                <option value="week">Elmúlt 7 nap</option>
                <option value="month">Elmúlt 30 nap</option>
            </select>
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            <button type="submit" class="btn">Keresés</button>

        </form>
    </div>
    
 
</body>
</html>
