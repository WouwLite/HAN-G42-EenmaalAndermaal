<!-- /include/delete-user.php -->

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/getpost.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['banUser'])) {
    $sql = <<<SQL
SELECT email FROM Users WHERE username = ?
SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['banUser']]);
    $useremail = $stmt->fetchColumn();
    print("<!-- " . $useremail . " -->");

    $subject = "Je account is geblokkeerd";
    $message = "Om de volgende reden is je account geblokkeerd: " . $_POST['reason'];
    $headers = 'From: noreply@iproject42.icasites.nl';
    mail($useremail, $subject, $message, $headers);

    $delsql = <<<SQL
UPDATE Users SET banned = 1 WHERE username = ?
SQL;
    $delstmt = $pdo->prepare($delsql);
    $delstmt->execute([$_POST['banUser']]);
}

mailUsers();
handleBannedUser();

function mailUsers(){
    global $pdo;
    $stmt = $pdo->prepare("SELECT DISTINCT email
                                    FROM Users  
                                    WHERE username in ( SELECT [user]
                                    FROM Bidding
                                    WHERE productid in (SELECT productid
                                                        FROM Object
                                                        WHERE Seller = ?))");
    $stmt->execute([$_POST['banUser']]);
    $userEmails = $stmt->fetchAll();

    $subject = "EenmaalAndermaal: veiling onderbroken.";
    $message = "Uw bod op veiling: '...' is verwijderd omdat de verkoper is geblokkeerd";
    $headers = 'From: noreply@iproject42.icasites.nl';

    foreach($userEmails as $mail){
        sendEmail();
        mail($mail, $subject, $message, $headers);
    }
}

function handleBannedUser(){
    global $pdo;

    $stmt = $pdo->prepare("UPDATE Object SET auctionClosed = 1 WHERE seller = ?");
    $stmt->execute([$_POST['banUser']]);
}
?>


<div class="modal fade" id="banModal" tabindex="-1" role="dialog" aria-labelledby="deleteAdModal" aria-hidden="true">
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
                    <p>Weet je zeker dat je het geselecteerde account <strong><span class="username">"Onbekend"</span></strong> wilt blokkeren?<br>
                        Dit kan niet ongedaan worden!</p>
                    <div class="form-group has-danger">
                        <label for="reason" class="form-control-label">Reden voor blokkering</label>
                        <input type="text" class="form-control form-control-danger" name="reason" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                    <button type="submit" id="banButton" name="banUser" value="" class="btn btn-danger">Ja,
                        blokkeer het account.
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#banModal').modal({
        show: false,
        keyboard: true
    });

    $('#banModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var username = button.data('user');
        var modal = $(this);
        modal.find('.username').text(username);
        $('#banButton').val(username);
    })
</script>