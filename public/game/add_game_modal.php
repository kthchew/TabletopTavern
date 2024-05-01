<?php if (isset($game_id) && $game_id): ?>

<!--    CREATE NEW COLLECTION MODAL -->
<div class="modal" id="game-collection-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create a new collection</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="add_to_new_collection.php" method="post">
                <input type="hidden" name="game-id" value="<?= $game_id; ?>">
                <?php if (isset($_SESSION['duplicate-error'])): ?>
                    <div class="alert alert-danger mx-3 mt-3" id="duplicate-alert"><?= $_SESSION['duplicate-error'] ?></div>
                    <script>
                        $(document).ready(function(){
                            $('#game-collection-modal').modal('show'); // Show the modal if there's an error
                        });
                        <?php unset($_SESSION['duplicate-error']); ?>
                    </script>

                <?php endif; ?>
                <div class="modal-body">
                    <input type="text" name="collection-name" id="collection-name" class="form-control" placeholder="Name your collection">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn" id="create-btn" disabled>Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>