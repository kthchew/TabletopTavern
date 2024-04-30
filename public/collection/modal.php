<?php if (isset($collection) && $collection): ?>
<!--EDIT MODAL -->
    <div class="modal" id="edit-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit collection name</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="edit_name.php?collection_id=<?= $collection->getId(); ?>" method="post">
                    <div class="modal-body">
                        <input type="text" name="new-name" id="new-name" class="form-control" value=<?= $collection->getName() ?>>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn" id="edit-btn" disabled>Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--EDIT MODAL -->
    <div class="modal" id="delete-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete collection</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="delete_collection.php?collection_id=<?= $collection->getId(); ?>" method="post">
                    <?php if (isset($error)): ?>
                        <script>
                            $(document).ready(function(){
                                $('#delete-modal').modal('show');
                            });
                        </script>
                    <?php endif; ?>
                    <div class="modal-body">
                        <label style="font-size: 18px;">Are you sure you want to delete <b><?= $collection->getName(); ?></b>?</label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--EVENT LISTENER-->
    <script>
        $(document).ready(function(){
            // disable button when there's no input
            $("#new-name").keyup(function(){
                if ($("#new-name").val().trim().length === 0
                    || $("#new-name").val().trim() === <?= json_encode($collection->getName()) ?>) {
                    $("#edit-btn").prop("disabled", true);
                } else {
                    $("#edit-btn").prop("disabled", false);
                }
            });
        });
    </script>
<?php endif; ?>