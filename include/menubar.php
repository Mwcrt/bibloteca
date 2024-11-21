<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <!-- Enlace a Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css">
    <!-- Enlace a CSS personalizado -->
    <link rel="stylesheet" href="../css/menubar.css">

</head>
<body>
    
<div class='dashboard'>
    <div class="dashboard-nav">
        <header>
            <a href="#!" class="menu-toggle" aria-expanded="false"><i class="fas fa-bars"></i></a>
            <a href="#" class="brand-logo"><i class="fas fa-book-open"></i> <span>Library</span></a>
        </header>
        <nav class="dashboard-nav-list">
            <a href="dashboard_user.php" class="dashboard-nav-item"><i class="fas fa-home"></i> Home</a>
            <a href="book_form.php" class="dashboard-nav-item"><i class="fas fa-book"></i> Books</a>
            <a href="reservation_form.php" class="dashboard-nav-item"><i class="fas fa-calendar-check"></i> Reservations</a>
            <a href="comentarios_form.php" class="dashboard-nav-item"><i class="fas fa-comment-alt"></i> Comments</a>

            <!-- Filtro que abre el modal -->
            <a href="#" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i class="fas fa-search"></i> Search</a>

            <!-- Cerrar Sesión y Perfil -->
            <a href="../pages/profile_form.php" class="dashboard-nav-item"><i class="fas fa-user"></i> Profile</a>
            <a href="../manages/logout.php" class="dashboard-nav-item"><i class="fas fa-power-off"></i> Logout</a>
        </nav>
    </div>
    <div class='dashboard-app'>
        <header class='dashboard-toolbar'>
            <a href="#!" class="menu-toggle" aria-expanded="false"><i class="fas fa-bars"></i></a>
        </header>
        <div class='dashboard-content'>
        </div>
    </div>
</div>

<!-- Ventana Modal para los filtros -->
<div id="filterModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Filtros</h2>
        <form id="searchForm" action="book_form.php" method="GET" class="search-bar">
            <input type="text" id="searchInput" name="query" placeholder="Buscar..." aria-label="Buscar libros" />
            <button type="submit" class="search-button" aria-label="Realizar búsqueda">
                <i class="fas fa-search"></i>
            </button>
            
            <!-- Filtros desplegables -->
            <label for="category" class="material-label">Categoría:</label>
                <select id="category" name="category">
                    <option value="">Todas las categorías</option>
                    <option value="Ficción Contemporánea" <?php if (isset($book) && $book['category'] == 'Ficción Contemporánea') echo 'selected'; ?>>Ficción Contemporánea</option>
                    <option value="Fantasía" <?php if (isset($book) && $book['category'] == 'Fantasía') echo 'selected'; ?>>Fantasía</option>
                    <option value="Ciencia Ficción" <?php if (isset($book) && $book['category'] == 'Ciencia Ficción') echo 'selected'; ?>>Ciencia Ficción</option>
                    <option value="Romance" <?php if (isset($book) && $book['category'] == 'Romance') echo 'selected'; ?>>Romance</option>
                    <option value="Misterio y Suspenso" <?php if (isset($book) && $book['category'] == 'Misterio y Suspenso') echo 'selected'; ?>>Misterio y Suspenso</option>
                    <option value="Terror" <?php if (isset($book) && $book['category'] == 'Terror') echo 'selected'; ?>>Terror</option>
                    <option value="Autobiografías y Biografías" <?php if (isset($book) && $book['category'] == 'Autobiografías y Biografías') echo 'selected'; ?>>Autobiografías y Biografías</option>
                    <option value="Desarrollo Personal y Autoayuda" <?php if (isset($book) && $book['category'] == 'Desarrollo Personal y Autoayuda') echo 'selected'; ?>>Desarrollo Personal y Autoayuda</option>
                    <option value="No Ficción" <?php if (isset($book) && $book['category'] == 'No Ficción') echo 'selected'; ?>>No Ficción</option>
                    <option value="Clásicos" <?php if (isset($book) && $book['category'] == 'Clásicos') echo 'selected'; ?>>Clásicos</option>
                    <option value="Aventura" <?php if (isset($book) && $book['category'] == 'Aventura') echo 'selected'; ?>>Aventura</option>
                    <option value="Ficción Histórica" <?php if (isset($book) && $book['category'] == 'Ficción Histórica') echo 'selected'; ?>>Ficción Histórica</option>
                    <option value="Poesía" <?php if (isset($book) && $book['category'] == 'Poesía') echo 'selected'; ?>>Poesía</option>
                    <option value="Cómics y Novelas Gráficas" <?php if (isset($book) && $book['category'] == 'Cómics y Novelas Gráficas') echo 'selected'; ?>>Cómics y Novelas Gráficas</option>
                    <option value="Infantil y Juvenil" <?php if (isset($book) && $book['category'] == 'Infantil y Juvenil') echo 'selected'; ?>>Infantil y Juvenil</option>
                    <option value="Anime/Manga" <?php if (isset($book) && $book['category'] == 'Anime/Manga') echo 'selected'; ?>>Anime/Manga</option>
                </select>
                            <br/>
            <label for="order" class="material-label">Ordenar:</label>
            <select id="order" name="order">
                <option value="ASC">A-Z</option>
                <option value="DESC">Z-A</option>
            </select>
            <br/>
            <label for="year" class="material-label">Seleccionar Año:</label>
            <select name="year" id="year">
                <option value="">-- Todos los años --</option>
                <?php
                for ($year = 2000; $year <= date('Y'); $year++) {
                    echo "<option value='$year'>$year</option>";
                }
                ?>
            </select>
        </form>
    </div>
</div>

<!-- Scripts de jQuery y Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script personalizado para el menú -->
<script src="../js/menubar.js"></script>

</body>
</html>
