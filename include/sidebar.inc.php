<!-- *********************************** -->
<!--  END OF THE MAIN CONTENT-CONTAINER  -->
<!-- *********************************** -->
</div>

<!-- Add sidebarmenu -->
<div id="sidebar">
    <ul>
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
        <!-- Create FOREACH with categories from DB -->
        <li class="nav-item">
            <a class="nav-link" href="#">Auto's</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Computers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Scrumboarden</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Games</a>
        </li>
    </ul>

    <div id="sidebar-hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>

<!-- FOOTER WERKT NOG NIET, IVM CONTAINEROVERLAY OP VERKEERD NIVEAU. -->
<footer>
    <p>Hello world!</p>
</footer>

<script>
    $(document).ready(function () {
        $('#sidebar-hamburger').click(function () {
            $('#sidebar').toggleClass('visible');
            $('#content').toggleClass('visible');
        });
    });
</script>