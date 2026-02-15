<?php
function showAlert($type, $message) {
    echo '
    <div id="myAlert" class="alert alert-' . $type . ' alert-dismissible fade show" role="alert"
    style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 400px; text-align: center;">
    <strong>' . $message . '</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>