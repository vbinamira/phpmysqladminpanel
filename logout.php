<?php
    session_start();
    session_destroy();
    //DESTROY ENDS SESSION
    header("location:/");
