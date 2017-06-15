<!-- *********************************** -->
<!--  END OF THE MAIN CONTENT-CONTAINER  -->
<!-- *********************************** -->
</div>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/config/app.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/config/database.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php'); ?>

<?php

function handleBannedUser(){
    global $pdo;
    $stmt = $pdo->prepare("SELECT DISTINCT email
                    FROM Users
                     WHERE username in ( SELECT [user]
                                        FROM Bidding
                                        WHERE productid in (
                                        SELECT productid
                                        FROM Object
                                        WHERE Seller = '?')
                                       )
                    ");
    $stmt->execute([$_SESSION['username']]);
    $dataEmails = $stmt->fetchColumn();

    $headers = 'From: noreply@iproject42.icasites.nl' . "\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    $subject = 'test';
    $message = 'Kijken of iedereen deze mail krijgt';

    foreach($dataEmails as $email){
        mail($email, $subject, $message, $headers);
    }
}


?>

<!-- Add sidebarmenu -->
<div id="sidebar">
    <div id="sidebar-hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <script>
        $(document).ready(function () {
            $('#sidebar-hamburger').click(function () {
                $('#sidebar').toggleClass('visible');
                $('#content').toggleClass('visible');
            });
        });
    </script>
    <ul>
        <form action="<?= $app_url ?>/views/public/browse.php" method="get">
            <li>
                <input class="form-control sm-2" type="search" id="search" name="Search"
                       placeholder="Zoek naar veiling..."/>
        </li>
            <input type="submit" style="display: none"/>
            <?php if (key_exists('cat', $_GET)): ?>
                <input type="hidden" id="catForm" name="cat" value="<?= $_GET['cat'] ?>">
                <li>
                    <div class="alert alert-info show" style="margin-top: 16px; ;">
                        <button type="button" id="catRemoveButton" class="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <script>
                            $('#catRemoveButton').click(function () {
                                $('#catForm').remove();
                                $(this).parent('div').remove();
                            });
                        </script>
                        Zoekresultaten zijn gefilterd op:
                        <strong>
                            <?php
                            global $pdo;
                            $stmt = $pdo->prepare("SELECT Name FROM [bottom level categories] WHERE ID = ?");
                            $stmt->execute([$_GET['cat']]);
                            $catName = $stmt->fetchColumn();
                            print($catName);
                            ?>
                        </strong>
                    </div>
                </li>
            <?php endif ?>
        </form>

        <!-- Add debug alert when debugging is enabled -->
        <?php if ($debug): ?>
            <li>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Let op!</strong> Debug staat enabled in the config/app.php !
                </div>
            </li>
        <?php endif; ?>

        <!-- Add admin menu when user is admin -->
        <?php
            global $user;
            if ($user['admin'] == 1) {
                print('<li><strong>Admin</strong></li>
                <li class="nav-item">
                        <a class="nav-link" href="' . $app_url . '/views/admin/"><i class="fa fa-handshake-o" aria-hidden="true"></i> Alle advertenties</a>
                </li>
                <li class="nav-item">
                        <a class="nav-link" href="' . $app_url . '/views/admin/overview-users.php"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Alle gebruikers</a>
                </li>');
            }
        ?>

        <!-- View dashboard -->
        <li><strong>Dashboard</strong></li>
        <li class="nav-item">
            <a class="nav-link active" href="/views/public/"><i class="fa fa-home" aria-hidden="true"></i> Thuis</a>
        </li>
        <li><span class="sidebar-span"></span></li>

        <!-- View account -->
        <li><strong>Account</strong></li>

        <?php
        $sql = "SELECT COUNT(productid) FROM Object WHERE Seller = ? and auctionClosed = 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user['username']]);
        $ADamount = $stmt->fetchColumn();

        $sql = "SELECT COUNT([user]) FROM Bidding WHERE [user] = ? and productid in (SELECT productid FROM Object WHERE auctionClosed = 0)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user['username']]);
        $BIDamount = $stmt->fetchColumn();
        ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= $app_url ?>/views/account/index.php"><i class="fa fa-gavel" aria-hidden="true"></i> Mijn biedingen</a>
        </li>
        <!-- Create IF statement. If user is merchant, show this link, else hide -->
        <li class="nav-item">
            <a class="nav-link" href="<?= $app_url ?>/views/account/index.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Mijn advertenties</a>
        </li>

        <!-- View business info -->
        <li><strong>Bedrijf</strong></li>
        <li class="nav-item">
            <a class="nav-link" href="<?= $app_url ?>/views/business/about.php"><i class="fa fa-briefcase" aria-hidden="true"></i> Over ons</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= $app_url ?>/views/business/terms.php"><i class="fa fa-file-text" aria-hidden="true"></i> Algemene Voorwaarden</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= $app_url ?>/views/business/privacy.php"><i class="fa fa-file-text" aria-hidden="true"></i> Privacy</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= $app_url ?>/views/business/service.php"><i class="fa fa-question-circle" aria-hidden="true"></i> Servicedesk</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= $app_url ?>/views/business/contact.php"><i class="fa fa-envelope" aria-hidden="true"></i> Contact</a>
        </li>

        <!-- View categories (dynamic) -->
        <li><span class="sidebar-span"></span></li>
        <li><strong>Rubrieken</strong></li>
        <!--        --><?php
        //        $catsql = <<<SQL
        //        SELECT Name FROM [top level categories];
        //SQL;
        //
        //        $stmt = $pdo->prepare($catsql);
        //        $stmt->execute();
        //        $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
        //        if ($categories) {
        //            foreach($categories as $category) {
        //                echo "<li class='nav-item'>";
        //                echo "<a class='nav-link' href='" . $app_url . "/views/categories/" . $category . "'>" . $category . "</a>";
        //                echo "</li>";
        //            }
        //        }
        //        ?>
        <?php
        $catsql = "SELECT * FROM Categories";
        $stmt = $pdo->prepare($catsql);
        $stmt->execute();
        $rawcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $keyed = array();
        foreach ($rawcategories as &$value) {
            $keyed[$value['ID']] = &$value;
        }
        unset($value);
        $rawcategories = $keyed;
        unset($keyed);

        // tree it
        $tree = array();
        foreach ($rawcategories as &$value) {
            if ($parent = $value['Parent'])
                $rawcategories[$parent]['children'][] = &$value;
            else
                $tree[] = &$value;
        }
        unset($value);
        $treecategories = $tree;
        unset($tree);

        //        var_dump($treecategories);

        function printParentAndChildren($parent)
        {
            global $app_url;
//            var_dump($parent);
            echo <<<HTML
            <div class="accordion nav-item" id="accordion{$parent['ID']}">
                <div class="accordion-group">
                    <div class="accordion-heading">
HTML;
            if (array_key_exists('children', $parent)) {
                echo <<<HTML
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion{$parent['ID']}" href="#collapse{$parent['ID']}children" >
                            {$parent['Name']}
                        </a>
HTML;
            } else {
                echo <<<HTML
                <a id="catlink" href="{$app_url}/views/public/browse.php?cat={$parent['ID']}">{$parent['Name']}</a>
HTML;

            }
            echo "</div>";
            if (array_key_exists('children', $parent)) {
                echo <<<HTML
                    <div id="collapse{$parent['ID']}children" class="accordion-body collapse"  style="padding-left: 1em";>
                        <div class="accordion-inner nav-item">
                            <!-- Here we insert another nested accordion -->
HTML;

                foreach ($parent['children'] as $child) {
                    printParentAndChildren($child);
                    echo "</div>";
                }
                echo "</div>";
                echo "</div>";
                echo "</div>";
            } else {
                echo "</div>";
            }
        }

        echo "<li class='nav-item'>";
        foreach ($treecategories[0]['children'] as $toplevel) {
            printParentAndChildren($toplevel);
        }
        echo "</li>";

        ?>
    </ul>
</div>


