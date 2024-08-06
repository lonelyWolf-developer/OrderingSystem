<section class="sentOrder">
    <h2>Zadat objednávku</h2>
    <form action="admin/sentToDatabase.php" method="post">
        <input type="text" name="orderNumber" placeholder="Číslo objednávky" pattern="^[0-9]{8}$" required>
        <select name="type" required>
            <option value="<?= ContractType::Road->value ?>">Silniční</option>
            <option value="<?= ContractType::XC->value ?>">Cross Country</option>
            <option value="<?= ContractType::Trail->value ?>">Trail</option>
            <option value="<?= ContractType::Enduro->value ?>">Enduro</option>
            <option value="<?= ContractType::Downhill->value ?>">Downhill</option>
            <option value="<?= ContractType::Gravel->value ?>">Gravel</option>
            <option value="<?= ContractType::Fatbike->value ?>">Fatbike</option>
        </select>
        <input type="date" name="date" required>
        <input type="time" name="time" required>
        <input type="hidden" name="user" value="<?= $user->id ?>">

        <input type="submit" value="Odeslat">
    </form>
</section>