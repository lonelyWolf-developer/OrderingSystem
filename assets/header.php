<header>
        <div class="logo">
            <a href="">Ještěrka</a>
        </div>

        <?php if($user->id != null): ?>            
            <div class="userIcon">
                <a href="/admin/userAccount.php"><?= $user->email ?></a>
            </div>
        <?php endif; ?>
</header>