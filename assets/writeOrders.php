<section class="writeOrders">
    <h1>Zadané objednávky</h1>
    <?php if (!empty($contracts)): ?>
        <?php foreach ($contracts as $oneContract): ?>

            <?php
            $contract->id = $oneContract["Id"];
            $contract->orderNumber = $oneContract["OrderNumber"];
            $contract->type = $contract->getContractType($oneContract["Type"]);
            $contract->date = $contract->formatDate($oneContract["Date"]);
            $contract->time = $contract->formatTime($oneContract["Time"]);
            ?>

            <div class="row <?= $contract->type ?>">
                <div class="contractInfo"><?= htmlspecialchars($contract->orderNumber) ?></div>
                <div class="contractInfo"><?= htmlspecialchars($contract->type) ?></div>
                <div class="contractInfo"><?= htmlspecialchars($contract->date) ?></div>
                <div class="contractInfo"><?= htmlspecialchars($contract->time) ?></div>                            
                <div class="contractInfo">                          
                <?php if (Auth::checkRole(Roles::Warehouseman->value)): ?>
                    <form action="./admin/changeContractStatus.php" method="post">
                        <input type="hidden" name="Id" value="<?= $contract->id ?>">
                        <input type="hidden" name="Status" value="<?= ContractStatus::Retrieved->value ?>">
                        <input type="hidden" name="ChangingUser" value="<?= $user->id ?>">
                        <input type="submit" value="Vyskladněno" id="deleteContractButton" class="deleteSubmit">
                    </form>
                <?php elseif(Auth::checkRole(Roles::Admin->value) or Auth::checkRole(Roles::Craftsman->value)): ?>
                    <form action="./admin/changeContractStatus.php" method="post">
                            <input type="hidden" name="Id" value="<?= $contract->id ?>">
                            <input type="hidden" name="Status" value="<?= ContractStatus::Cancelled->value ?>">
                            <input type="hidden" name="ChangingUser" value="<?= $user->id ?>">
                            <input type="submit" value="Zrušit" id="deleteContractButton" class="deleteSubmit">
                    </form>
                <?php endif; ?>
                </div>
            </div>
        <?php endforeach ?>
    <?php else: ?>
        <p class="notice">Zatím si nikdo nic neobjednal...</p>
    <?php endif; ?>
</section>