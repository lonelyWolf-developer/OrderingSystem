<header>
        <div class="logo">
            <a href="/OrderingSystem">Objednávkový systém</a>
        </div>

        <?php if($user->id != null): ?>            
            <div class="userIcon">
                <a href="/OrderingSystem/admin/userAccount.php"><?= $user->email ?></a>
            </div>
        <?php endif; ?>
</header>