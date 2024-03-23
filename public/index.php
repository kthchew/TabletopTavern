#!/usr/local/bin/php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tabletop Tavern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<header class="container-fluid bg-dark text-white m-0">
    <h1 class="text-center py-5 mb-0">Tabletop Tavern</h1>
</header>
<nav class="navbar navbar-expand-lg bg-dark bg-opacity-75 navbar-dark py-3 justify-content-center">
    <ul class="navbar-nav justify-content-around w-75">
        <li class="nav-item"><a class="nav-link active" href="index.html">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
        <li class="nav-item"><a class="nav-link" href="filter.html">Filter Search</a></li>
        <li class="nav-item"><a class="nav-link" href="quiz.html">Quiz</a></li>
        <li class="nav-item"><a class="nav-link" href="creators.html">Creators</a></li>
    </ul>
</nav>

<div class="d-flex input-group rounded justify-content-center col-lg-3 my-2">
    <div class="col-md-3">
        <input type="search" class="form-control rounded" placeholder="Search" />
    </div>
</div>

<div class="border-black bg-black w-75 mx-auto"><hr class="border-3"></div>

<h2 class="text-center py-4">Start your journey...</h2>

<div class="row mx-5">
    <div class="col-md bg-dark-subtle m-2 text-center">
        <h3 class="my-2">Take a Quiz</h3>
        <img src="images/scratching-head.png" alt="Emoji of scratching head" class="img-fluid m-4">
        <p class="bg-dark bg-opacity-10 py-3 mx-2">Find your fate</p>
    </div>
    <div class="col-md bg-dark-subtle m-2 text-center">
        <h3 class="my-2">Roll the Dice!</h3>
        <img src="images/magnifying-glass.png" alt="Emoji with magnifying glass" class="img-fluid m-4">
        <p class="bg-dark bg-opacity-10 py-3 mx-2">Take a chance</p>
    </div>
    <div class="col-md bg-dark-subtle m-2 text-center">
        <h3 class="my-2">Filter Search</h3>
        <img src="images/magnifying-glass.png" alt="Emoji with magnifying glass" class="img-fluid m-4">
        <p class="bg-dark bg-opacity-10 py-3 mx-2">Filter lol</p>
    </div>
</div>

</body>

</html>
