

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/getpost.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['unbanUser'])) {
    $sql = <<<SQL
SELECT email FROM Users WHERE username = ?
SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['unbanUser']]);
    $useremail = $stmt->fetchColumn();
    print("<!-- " . $useremail . " -->");

    $subject = "Je account is gedeblokkeerd";
    $message = "Om de volgende reden is je account gedeblokkeerd: " . $_POST['reason'];
    $headers = 'From: noreply@iproject42.icasites.nl';
    mail($useremail, $subject, $message, $headers);

    $delsql = <<<SQL
UPDATE Users SET banned = 0 WHERE username = ?
SQL;
    $delstmt = $pdo->prepare($delsql);
    $delstmt->execute([$_POST['unbanUser']]);
}
?>


<div class="modal fade" id="unbanModal" tabindex="-1" role="dialog" aria-labelledby="unbanModal" aria-hidden="true">
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
                    <p>Weet je zeker dat je het geselecteerde account <strong><span class="username">"Onbekend"</span></strong> wilt deblokkeren?<br></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                    <button type="submit" id="unbanButton" name="unbanUser" value="" class="btn btn-danger">Ja,
                        deblokkeer het account.
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#unbanModal').modal({
        show: false,
        keyboard: true
    });

    $('#unbanModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var username = button.data('user');
        var modal = $(this);
        modal.find('.username').text(username);
        $('#unbanButton').val(username);
    })
</script>