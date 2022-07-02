<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 col-lg-8 offset-lg-2 px-2 mt-1">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#"><?php echo lang("Kea.nav_brand"); ?></a>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/hosts"><?php echo lang("Kea.nav_hosts"); ?><span class="sr-only"> (current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/pools"><?php echo lang("Kea.nav_pools"); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin"><?php echo lang("Kea.nav_admin"); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about"><?php echo lang("Kea.nav_about"); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/logout"><?php echo lang("Kea.nav_logout"); ?></a>
                </li>
            </ul>
        </div>
    </nav>
</div>