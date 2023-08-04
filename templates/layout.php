<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body>
<?php if ($user): ?>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item has-text-weight-bold" href="/">
                Artur Work Manager
            </a>
            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false"
               data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="/">Dodaj naprawę</a>
                <a class="navbar-item" href="/list">Lista napraw</a>
            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <!--                <div class="buttons">-->
                    <!--                    <a class="button is-primary">-->
                    <!--                        <strong>Sign up</strong>-->
                    <!--                    </a>-->
                    <!--                    <a class="button is-light">-->
                    <!--                        Log in-->
                    <!--                    </a>-->
                    <!--                </div>-->
                    Zalogowany jako: <?= $user->username ?>
                    (<?= strtoupper($user->role) ?>)
                    <a href="/logout" class="ml-3">wyloguj</a>
                </div>
            </div>
        </div>
    </nav>
<?php endif; ?>
<section class="section">
    <?= $content ?>
</section>


<script>
    document.addEventListener('DOMContentLoaded', () => {

        // Get all "navbar-burger" elements
        const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

        // Add a click event on each of them
        $navbarBurgers.forEach(el => {
            el.addEventListener('click', () => {

                // Get the target from the "data-target" attribute
                const target = el.dataset.target;
                const $target = document.getElementById(target);

                // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
                el.classList.toggle('is-active');
                $target.classList.toggle('is-active');

            });
        });

    });
</script>
</body>
</html>
