<header>
        <div class="logo logoSize">
            <a href="./"><img src="./img/ForkliftSmall.png" alt="Forklift"> Ještěrka</a>
        </div>

        <?php if($user->id != null): ?>            
            <div class="userIcon">
                <a href="/admin/userAccount.php"><?= $user->email ?></a>
            </div>
        <?php endif; ?>
</header>