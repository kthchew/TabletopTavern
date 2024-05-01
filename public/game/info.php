#!/usr/local/bin/php
<?php
require '../../vendor/autoload.php';
session_start();
$game_id = isset($_GET['game_id']) ? $_GET['game_id'] : null;
if (!isset($game_id)) {
    $error = "Game not found.";
} else {
    $game = Tabletop\Entities\Game::getGameById($game_id);
    if (!$game) {
        $error = "Game not found.";
    }
}
define('__HEADER_FOOTER_PHP__', true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tabletop Tavern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="../css/style.css">
    <style>
        .heart {
            font-size: 24px;
            cursor: pointer;
            width: 24px;
            display: inline-block;
            text-align: center;
        }

        .heart:hover,
        .heart.active {
            color: #1c5e33;
        }

        .comment-actions {
            margin-top: -20px;
        }

        .timestamp {
            font-size: 0.8em;
            display: block;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hearts = document.querySelectorAll('.heart');
            hearts.forEach((heart, index) => {
                heart.addEventListener('mouseover', function() {
                    hearts.forEach((h, i) => {
                        if (i <= index && !h.classList.contains('selected')) {
                            h.innerHTML = '&#x2764;';
                            h.classList.add('active');
                        } else if (!h.classList.contains('selected')) {
                            h.innerHTML = '&#x2661;';
                            h.classList.remove('active');
                        }
                    });
                });

                heart.addEventListener('mouseout', function() {
                    hearts.forEach(h => {
                        if (!h.classList.contains('selected')) {
                            h.innerHTML = '&#x2661;';
                            h.classList.remove('active');
                        }
                    });
                });

                heart.addEventListener('click', function() {
                    const rating = parseInt(heart.getAttribute('data-value'));
                    hearts.forEach((h, i) => {
                        if (i < rating) {
                            h.innerHTML = '&#x2764;';
                            h.classList.add('active');
                            h.classList.add('selected');
                        } else {
                            h.innerHTML = '&#x2661;';
                            h.classList.remove('active');
                            h.classList.remove('selected');
                        }
                    });
                    document.getElementById('rating_value').value = rating;
                });
            });
        });

    </script>
</head>

<body>
<?php include '../header.php';?>

<div class="container mt-3">
<?php if (!isset($game)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php else: ?>
    <?php if (isset($_SESSION['user'])): ?>
        <!--        result of adding game-->
        <?php if (isset($_SESSION['error'])): ?>
            <div id="error-game-alert" class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php elseif (isset($_SESSION['success'])): ?>
            <div id="success-game-alert" class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
    <?php endif; ?>
    <div style="display: flex;">
        <h1 style="margin-right: 10px;"><?= $game->getName() ?></h1>
        <?php if (isset($_SESSION['user'])): ?>
        <div class="dropdown" style="display: inline-block; vertical-align: middle;">
            <button id="add-game-btn" type="button" data-bs-toggle="dropdown" class="btn square-btn" aria-expanded="false">&plus;</button>
            <ul class="dropdown-menu" aria-labelledby="game-options">
                <?php
                $collections = Tabletop\Entities\Collection::getUserCollections();
                foreach ($collections as $collection) { ?>
                <form action='add_game.php?game_id=<?= $game_id; ?>&collection_id=<?= $collection->getId(); ?>' method='post'>
                <?php
                    echo "<li><button class='dropdown-item' type='submit' style = 'color: #1C5E33'>";
                    echo $collection->getName();
                    echo "</button></li>";
                }
                ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
    <div>
        <div class="w-25 float-end">
            <img src="<?= $game->getImageURL() ?>" alt="<?= $game->getName() ?>" class="img-thumbnail m-3">
        </div>
        <?php if ($game->getMinPlayers() === $game->getMaxPlayers()): ?>
            <p>Players: <?= $game->getMinPlayers() ?></p>
        <?php else: ?>
            <p>Players: <?= $game->getMinPlayers() ?> - <?= $game->getMaxPlayers() ?></p>
        <?php endif; ?>
        <p>Play Time: <?= $game->getPlayTime() ?> min</p>
        <p>Minimum Age: <?= $game->getMinAge() ?></p>
        <p>Year Published: <?= $game->getYearPublished() ?></p>
        <p>Subgenres:</p>
        <ul>
            <?php foreach ($game->getSubgenres() as $subgenre): ?>
                <li><?= $subgenre ?></li>
            <?php endforeach; ?>
        </ul>
        <p>Mechanics:</p>
        <ul>
            <?php foreach ($game->getMechanics() as $mechanic): ?>
                <li><?= $mechanic ?></li>
            <?php endforeach; ?>
        </ul>
        <hr>
        <div id="rating">
            <span><?= $game->getAverageRating() > 0 ? $game->getAverageRating() : "No ratings yet" ?></span>
            <?php if (isset($_SESSION['user'])): ?>
                <span class="heart" data-value="1">&#x2661;</span>
                <span class="heart" data-value="2">&#x2661;</span>
                <span class="heart" data-value="3">&#x2661;</span>
                <span class="heart" data-value="4">&#x2661;</span>
                <span class="heart" data-value="5">&#x2661;</span>
                <span>(<?= $game->getRatingCount() ?>)</span>
                <form action="submit_rating.php?game_id=<?= $game_id ?>" method="post" style="margin-bottom: 20px;">
                    <input type="hidden" id="rating_value" name="rating_value" value="">
                    <button type="submit">Submit Rating</button>
                </form>
            <?php else: ?>
                <a href="../login.php">Log in to rate</a>
            <?php endif; ?>
        </div>
        <h3>Description</h3>
        <p><?= $game->getDescription() ?></p>
    </div>

    <hr>
    <h2>Comments</h2>
    <?php if (isset($_SESSION['user'])): ?>
        <form action="submit_comment.php" method="post">
            <input type="hidden" name="game_id" value="<?= $game_id ?>">
            <div>
                <textarea name="comment_text" required></textarea>
            </div>
            <div>
                <button type="submit">Submit Comment</button>
            </div>
        </form>
    <?php endif; ?>

    <?php
    $comments = Tabletop\Entities\Comment::getCommentsByGameId($game_id);
    foreach ($comments as $comment) {
        $comment_text = preg_replace(
            '/(https?:\/\/[\w\-\.!~?&+\*\'"(),\/:@]+)/',
            '<a href="$1">$1</a>',
            $comment->comment_text
        );
        echo "<p><strong>{$comment->username}</strong>: {$comment_text}<br><span class='timestamp'>{$comment->created_at}</span></p>";
        if (isset($_SESSION['user']) && $_SESSION['user'] == $comment->userId) {
            echo "<p class='comment-actions'>";
            echo "<a href='#' class='edit-comment' data-comment-id='{$comment->id}' style='margin-right: 10px;'>Edit</a>";
            echo "<a href='#' class='delete-comment' data-comment-id='{$comment->id}'>Delete</a>";
            echo "</p>";
        }
    }
    ?>
    <div id="edit-comment-form" style="display: none;">
        <h3>Edit Comment</h3>
        <form action="edit_comment.php" method="post">
            <input type="hidden" name="comment_id" id="edit-comment-id">
            <input type="hidden" name="game_id" value="<?= $game_id ?>">
            <textarea name="comment_text" id="edit-comment-text" required></textarea>
            <button type="submit">Update Comment</button>
        </form>
    </div>
    <div id="delete-comment-form" style="display: none;">
        <h3>Delete Comment</h3>
        <form action="delete_comment.php" method="post">
            <input type="hidden" name="comment_id" id="delete-comment-id">
            <input type="hidden" name="game_id" value="<?= $game_id ?>">
            <p>Are you sure you want to delete this comment?</p>
            <button type="submit">Delete Comment</button>
        </form>
    </div>

    <?php if (!isset($_SESSION['user'])): ?>
        <?php echo "<a href='../login.php'>Login</a> to post a comment."; ?>
    <?php endif; ?>


<?php endif; ?>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editLinks = document.querySelectorAll('.edit-comment');
        const deleteLinks = document.querySelectorAll('.delete-comment');
        const editForm = document.getElementById('edit-comment-form');
        const deleteForm = document.getElementById('delete-comment-form');
        const editCommentId = document.getElementById('edit-comment-id');
        const editCommentText = document.getElementById('edit-comment-text');
        const deleteCommentId = document.getElementById('delete-comment-id');

        editLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const commentId = this.getAttribute('data-comment-id');
                editCommentId.value = commentId;
                editForm.style.display = 'block';
            });
        });

        deleteLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const commentId = this.getAttribute('data-comment-id');
                deleteCommentId.value = commentId;
                deleteForm.style.display = 'block';
            });
        });
    });

    $(document).ready(function(){
        $("#error-game-alert").delay(3000).fadeOut();
        $("#success-game-alert").delay(3000).fadeOut();
    });
</script>
<?php include '../footer.php';?>
</body>

</html>
