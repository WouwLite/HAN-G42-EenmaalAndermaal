<!-- *********************************** -->
<!--  END OF THE MAIN CONTENT-CONTAINER  -->
<!-- *********************************** -->
</div>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/config/app.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/config/database.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php'); ?>

<!-- Add sidebarmenu -->
<div id="sidebar">
    <ul>
        <li>
            <form>
                <input class="form-control sm-2" type="search" id="search" name="Search"
                       placeholder="Zoek naar veiling..."/>
            </form>
        </li>

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
        <li class="nav-item">
            <a class="nav-link disabled" href="#"><i class="fa fa-star" aria-hidden="true"></i> Populair</a>
        </li>
        <li><span class="sidebar-span"></span></li>

        <!-- View account -->
        <li><strong>Account</strong></li>
        <li class="nav-item">
            <a class="nav-link" href="<?= $app_url ?>/views/account/index.php"><i class="fa fa-gavel"
                                                                                  aria-hidden="true"></i> Mijn biedingen
                <span class="badge badge-default"><?= $testBiedingNo ?></span></a>
        </li>
        <!-- Create IF statement. If user is merchant, show this link, else hide -->
        <li class="nav-item">
            <a class="nav-link" href="<?= $app_url ?>/views/account/index.php"><i class="fa fa-shopping-cart"
                                                                                  aria-hidden="true"></i> Mijn
                advertenties <span class="badge badge-default"><?= $testAdvertNo ?></span></a>
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
                <a href="?cat={$parent['ID']}">{$parent['Name']}</a>
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

</ul>

<div id="sidebar-hamburger">
    <span></span>
    <span></span>
    <span></span>
</div>
</div>

<script>
    $(document).ready(function () {
        $('#sidebar-hamburger').click(function () {
            $('#sidebar').toggleClass('visible');
            $('#content').toggleClass('visible');
        });
    });
</script>