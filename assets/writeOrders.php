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
            $contract->user = $oneContract["User"];
            $contract->status = $oneContract["Status"];
            $contract->changingUser = $oneContract["ChangingUser"];

            $contractUser = new User();
            $contractUser->id = $contract->user;
            $contractUser->email = $contractUser->getUserEmail($connection, $contractUser->id);

            if($contract->changingUser != null){
                $changeUser = new User();
                $changeUser->id = $contract->changingUser;
                $changeUser->email = $changeUser->getUserEmail($connection, $changeUser->id);
            }

            ?>

            <div class="row <?= $contract->type ?>">
                <div class="contractInfo"><?= htmlspecialchars($contract->orderNumber) ?></div>
                <div class="contractInfo"><?= htmlspecialchars($contract->type) ?></div>
                <div class="contractInfo"><?= htmlspecialchars($contract->date) ?></div>
                <div class="contractInfo"><?= htmlspecialchars($contract->time) ?></div>
                <div class="contractInfo"><?= htmlspecialchars(explode('@', $contractUser->email)[0] ) ?></div>
                <?php if($contract->changingUser != null and $contract->status != 0): ?>
                    <div class="contractInfo"><?= htmlspecialchars(explode('@', $changeUser->email)[0] ) ?></div>
                <?php endif; ?>                            
                <?php if($contract->status == 0): ?>
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
                <?php endif; ?>
            </div>
        <?php endforeach ?>
    <?php else: ?>
        <p class="notice">Zatím si nikdo nic neobjednal...</p>
    <?php endif; ?>
</section>