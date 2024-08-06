<?php if($message): ?>    
    <div class="messageBox" id="messageBoxTimer">
        <div class="iconsDiv" id="icons">
            <img class="crossIcon" id="icon" src="<?= $message->crossPath ?>" alt="Hamburger">
        </div>
        <div class="background <?= $message->color ?>"></div>
        <p><?= $message->message ?></p>
    </div>
    <?php $message->killSession() ?>  
<?php endif; ?>