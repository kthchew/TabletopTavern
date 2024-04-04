#!/usr/local/bin/php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tabletop Tavern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
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
        <li class="nav-item"><a class="nav-link" href="creators.html">Creators</a></li>
    </ul>
</nav>

<div class="w-100 d-flex justify-content-center">
    <div class="input-group my-2 w-50">
        <input type="search" class="form-control" placeholder="Search Games..." />
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
        </div>
    </div>
</div>

<div class="border-black bg-black w-75 mx-auto"><hr class="border-3"></div>

<h2 class="text-center py-4">Start your journey...</h2>
<p class="text-center pb-4">Greetings, board game enthusiasts! Have you been looking for your next game to play? Find one here!</p>

<div class="row mx-5">
    <div class="col-md m-2 text-center">
        <a href="random.php" class="btn bg-dark-subtle text-center d-block">
            <div>
                <h3 class="my-2">Roll the Dice!</h3>
<!--                https://icons.getbootstrap.com/icons/shuffle/-->
                <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" fill="currentColor" class="bi bi-shuffle m-4" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M0 3.5A.5.5 0 0 1 .5 3H1c2.202 0 3.827 1.24 4.874 2.418.49.552.865 1.102 1.126 1.532.26-.43.636-.98 1.126-1.532C9.173 4.24 10.798 3 13 3v1c-1.798 0-3.173 1.01-4.126 2.082A9.6 9.6 0 0 0 7.556 8a9.6 9.6 0 0 0 1.317 1.918C9.828 10.99 11.204 12 13 12v1c-2.202 0-3.827-1.24-4.874-2.418A10.6 10.6 0 0 1 7 9.05c-.26.43-.636.98-1.126 1.532C4.827 11.76 3.202 13 1 13H.5a.5.5 0 0 1 0-1H1c1.798 0 3.173-1.01 4.126-2.082A9.6 9.6 0 0 0 6.444 8a9.6 9.6 0 0 0-1.317-1.918C4.172 5.01 2.796 4 1 4H.5a.5.5 0 0 1-.5-.5"/>
                    <path d="M13 5.466V1.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384l-2.36 1.966a.25.25 0 0 1-.41-.192m0 9v-3.932a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384l-2.36 1.966a.25.25 0 0 1-.41-.192"/>
                </svg>
                <p class="bg-dark bg-opacity-10 py-3 mx-2">Take a chance</p>
            </div>
        </a>
    </div>
    <div class="col-md m-2 text-center">
        <a href="filter.php" class="btn bg-dark-subtle text-center d-block">
            <div>
                <h3 class="my-2">Filter Search</h3>
<!--                https://icons.getbootstrap.com/icons/funnel/-->
                <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" fill="currentColor" class="bi bi-funnel m-4" viewBox="0 0 16 16">
                    <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z"/>
                </svg>
                <p class="bg-dark bg-opacity-10 py-3 mx-2">Filter games</p>
            </div>
        </a>
    </div>
</div>

</body>

</html>
