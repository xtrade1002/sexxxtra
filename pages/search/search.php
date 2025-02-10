<body onload="getLocation()">

<div class="search-container">
    <h3>Keresés</h3>
    <form method="GET" action="search_results.php">
        
        <div class="search-category">Kulcsszó</div>
        <div class="dropdown-content">
            <input type="text" name="keywords" placeholder="Kulcsszó">
        </div>

        <div class="search-category">Szolgáltatás <img src="assets/pictures/down_neon.png" class="dropdown-icon" alt="Nyitás"></div>
        <div class="dropdown-content">
            <label><input type="checkbox" name="service[]" value="masszazs"> Masszázs</label>
            <label><input type="checkbox" name="service[]" value="webcam"> Webcam</label>
            <label><input type="checkbox" name="service[]" value="escort"> Escort</label>
        </div>

        <div class="search-category">Város <img src="assets/pictures/down_neon.png" class="dropdown-icon" alt="Nyitás" ></div>
        <div class="dropdown-content">
            <input type="text" name="city" placeholder="Város">
        </div>

        <div class="search-category">Szexualitás <img src="assets/pictures/down_neon.png" class="dropdown-icon" alt="Nyitás" ></div>
        <div class="dropdown-content">
            <label><input type="radio" name="sexuality" value="hetero"> Hetero</label>
            <label><input type="radio" name="sexuality" value="biszex"> Biszex</label>
            <label><input type="radio" name="sexuality" value="meleg"> Meleg</label>
            <label><input type="radio" name="sexuality" value="leszbikus"> Leszbikus</label>
        </div>

        <div class="search-category">Etnikum <img src="assets/pictures/down_neon.png" class="dropdown-icon" alt="Nyitás" ></div>
        <div class="dropdown-content">
            <select name="ethnicity">
                <option value="">Válassz</option>
                <option value="caucasian">Kaukázusi</option>
                <option value="african">Afrikai</option>
                <option value="asian">Ázsiai</option>
                <option value="latin">Latin</option>
            </select>
        </div>

        <div class="search-category">Szemszín <img src="assets/pictures/down_neon.png" class="dropdown-icon" alt="Nyitás"></div>
        <div class="dropdown-content">
            <select name="eye_color">
                <option value="">Válassz</option>
                <option value="blue">Kék</option>
                <option value="brown">Barna</option>
                <option value="green">Zöld</option>
                <option value="gray">Szürke</option>
            </select>
        </div>

        <div class="search-category">Életkor <img src="assets/pictures/down_neon.png" class="dropdown-icon" alt="Nyitás"></div>
        <div class="dropdown-content">
            <input type="number" name="age_min" placeholder="Tól" min="18">
            <input type="number" name="age_max" placeholder="Ig" min="18">
        </div>

        <div class="search-category">Testalkat <img src="assets/pictures/down_neon.png" class="dropdown-icon" alt="Nyitás"></div>
        <div class="dropdown-content">
            <select name="body_type">
                <option value="">Válassz</option>
                <option value="thin">Vékony</option>
                <option value="average">Átlagos</option>
                <option value="athletic">Sportos</option>
                <option value="muscular">Izmos</option>
                <option value="curvy">Dundi</option>
            </select>
        </div>

        <div class="search-category">Megjelenés dátuma <img src="assets/pictures/down_neon.png" class="dropdown-icon" alt="Nyitás"></div>
        <div class="dropdown-content">
            <select name="date">
                <option value="">Válassz</option>
                <option value="today">Ma</option>
                <option value="week">Elmúlt 7 nap</option>
                <option value="month">Elmúlt 30 nap</option>
            </select>
        </div>

        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <button type="submit" class="btn">Keresés</button>
    </form>
</div>


    <script>
function toggleDropdown(id) {
    let dropdown = document.getElementById(id);
    let icon = dropdown.previousElementSibling.querySelector(".dropdown-icon");

    if (dropdown.classList.contains("active")) {
        dropdown.classList.remove("active");
        icon.src = "assets/pictures/down_neon.png"; // Nyíl lefelé
    } else {
        dropdown.classList.add("active");
        icon.src = "assets/pictures/up.png"; // Nyíl felfelé
    }
}


</script>

</body>
