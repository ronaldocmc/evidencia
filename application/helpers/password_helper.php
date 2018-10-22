<?php

function authenticate_operation($input_password, $session_password){
    return hash(ALGORITHM_HASH,$input_password.SALT) == $session_password;
}

