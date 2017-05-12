<!-- /include/delete-modal.php -->

<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/dev/functions.dev.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vars = getRealPOST();
    $sql = <<<SQL
SELECT email FROM Users WHERE ( SELECT Seller FROM Object WHERE productid = ?)
SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$vars['ad-id']]);
    $useremail = $stmt->fetchColumn();

    $subject = "Je aanbieding is verwijderd";
    $message = "Om de volgende reden is je advertentie verwijderd: " . $vars['reason'];
    $headers = 'From: noreply@iproject42.icasites.nl';
    mail($useremail, $subject, $message, $headers);

    $delsql = <<<SQL
DELETE FROM Object
WHERE productid = ?
SQL;
    $delstmt = $pdo->prepare($delsql);
    $stmt->execute($vars['ad-id']);
}
?>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteAdModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Weet je het zeker?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <p>Weet je zeker dat je advertentie nr.<span class="ad-id">test</span> wilt verwijderen?<br>
                        Dit kan niet ongedaan worden!</p>
                    <div class="form-group">
                        <label for="reason" class="form-control-label">Reden voor verwijdering</label>
                        <input type="text" class="form-control" name="reason">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                    <button type="submit" id="deleteButton" name="deleteItem" value="" class="btn btn-danger">Ja,
                        verwijder de advertentie
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#deleteModal').modal({
        show: false,
        keyboard: true
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var ad = button.data('ad');
        var modal = $(this);
        modal.find('.ad-number').text(ad);
        $('#deleteButton').val(ad);
    })
</script>