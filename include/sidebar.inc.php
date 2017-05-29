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
                <input class="form-control sm-2" type="search" id="search" name="Search" placeholder="Zoek naar veiling..."/>
            </form>
        </li>
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
        <?php
        global $user;
        if($user['admin'] == 1){
            print('<li><strong>Admin</strong></li>
            <li class="nav-item">
                    <a class="nav-link" href="/views/admin/adminpanel.php"><i class="fa fa-handshake-o" aria-hidden="true"></i> Alle advertenties</a>
            </li>
            <li class="nav-item">
                    <a class="nav-link" href="/views/admin/allUsers.php"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Alle gebruikers</a>
            </li>');
        }
        ?>
        <li><strong>Dashboard</strong></li>
        <li class="nav-item">
            <a class="nav-link active" href="/views/public/"><i class="fa fa-home" aria-hidden="true"></i> Thuis</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-star" aria-hidden="true"></i> Populair</a>
        </li>
        <li><span class="sidebar-span"></span></li>
        <li><strong>Account</strong></li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-gavel" aria-hidden="true"></i> Mijn biedingen <span class="badge badge-default"><?=$testBiedingNo?></span></a>
        </li>
        <!-- Create IF statement. If user is merchant, show this link, else hide -->
        <li class="nav-item">
            <a class="nav-link disabled" href="#"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Mijn advertenties <span class="badge badge-default"><?=$testAdvertNo?></span></a>
        </li>
        <li><span class="sidebar-span"></span></li>
        <li><strong>Rubrieken</strong></li>
        <?php
        $catsql = <<<SQL
        SELECT Name FROM [top level categories];
SQL;

        $stmt = $pdo->prepare($catsql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if ($categories) {
            foreach($categories as $category) {
                echo "<li class='nav-item'>";
                echo "<a class='nav-link' href='" . $app_url . "/views/categories/" . $category . "'>" . $category . "</a>";
                echo "</li>";
            }
        }
        ?>
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