<style>
    h1, h2,h3,h4,h5, p{
        font-family: K2D;
        color: #1C5E33;
    }

    h1 {
        font-weight: bold;
    }

    .nav-link{
        font-family: K2D;
    }

    .nav-link:hover{
        color: #5abc7c !important;
    }

    body{
        background-color: #FBFFF4;
    }

    .btn{
    }


</style>


<div>
<nav class="navbar navbar-expand-lg bg-opacity-75 py-3 justify-content-center" style="background-color: #DEEDC8 ">

    <div class="navbar-brand" style="display: flex; align-items: center;margin-left: 100px; margin-right: 100px">
        <a href="index.php" style="text-decoration: none;"><img src="images/tempLogo.png" alt="A 20 sided dice with the letters 'TT'" style="width:110px;height:110px;"></a>
        <a href="index.php" style="text-decoration: none;"><h1 class="text-right mb-0" style="padding-top: 0; padding-bottom: 0;">Tabletop Tavern</h1></a>
    </div>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="navbarCollapse" style="padding-left: 20px;">
    <ul class="navbar-nav justify-content-around w-50">
        <li class="nav-item h4"><a class="nav-link active" href="index.php" style = "color: #1C5E33"> Home</a></li>
        <li class="nav-item h4"><a class="nav-link" href="about.php" style = "color: #1C5E33"> About</a></li>
        <li class="nav-item h4"><a class="nav-link" href="filter.php" style = "color: #1C5E33"> Browse</a></li>
        <?php if (isset($_SESSION['user'])): ?>
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" style = "color: #1C5E33; border: 2px solid #1C5E33;  display: inline-block; background-color: #cee4ac; font-size: 15pt" aria-expanded="false">
                    <?php echo $_SESSION['username'] ?>'s Account
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" style = "color: #1C5E33" href="dashboard.php">Dashboard</a></li>
                    <li><a class="dropdown-item" style = "color: #1C5E33" href="logout.php">LogOut</a></li>
                </ul>
            </div>
        <?php else: ?>
            <li class="nav-item h4 " ><a class="nav-link rounded" href="login.php" style = "color: #1C5E33; border: 2px solid #1C5E33;  display: inline-block; background-color: #cee4ac"> Login</a></li>
        <?php endif; ?>
    </ul>
    </div>
</nav>
<br>
    <form action="search.php" method="get">
        <div class="w-100 d-flex justify-content-center">
            <div class="input-group my-2 w-50" >
                <input type="search" name="searchTerm" class="form-control" style="border: 1px solid #1C5E33" placeholder="Search Games..."/>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" style="background-color: #1C5E33" type="submit"><i class="bi bi-search" style="color: #c9e898"></i></button>
                </div>
            </div>
        </div>
    </form>

    <div class="border-black w-75 mx-auto" style="background-color: #1C5E33"><hr class="border-3"></div>

</div>