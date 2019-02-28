<?php
function unsetSessionVars() {
    unset( $_SESSION['msg'] );
    unset( $_SESSION['imageURL'] );
    unset( $_SESSION['make'] );
    unset( $_SESSION['year'] );
    unset( $_SESSION['mileage'] );
}
?>