<?php

  /*
      Returns the issuer of the credit card number
  */

  function test_suite_credit_card(string $card_number){ 

    $c_number = $card_number;
    $c_number_ori = $c_number;
    $c_number_first = mb_substr($c_number_ori, 0, 4);
    $c_number = mb_strlen($c_number) > 4 ? mb_substr($c_number, mb_strlen($c_number) -4, 4) : false;
    $credit_card_type = null;

    // Isracard
    if(8 == mb_strlen($c_number_ori) || 9 == mb_strlen($c_number_ori)){
        $credit_card_type = 'ישראכרט';
    }

    // Leumi card
    elseif(4580 == $c_number_first){
        $credit_card_type = 'אלפא קארד/לאומי קארד';
    }

    // a visa
    elseif(4 == str_split($c_number_first)[0]){
        $credit_card_type = 'ויזה';
    }

    // american express
    elseif(34 == mb_substr($c_number_first, 0, 2) || 37 == mb_substr($c_number_first, 0, 2)){
        $credit_card_type = 'אמריקן אקספרס';
    }

    // dinner's club
    elseif(30 == mb_substr($c_number_first, 0, 2) || 36 == mb_substr($c_number_first, 0, 2) || 38 == mb_substr($c_number_first, 0, 2)){
        $credit_card_type = 'דיינרס';
    }

    // JCB
    elseif(35 == mb_substr($c_number_first, 0, 2)){
        $credit_card_type = 'JCB';
    }

    else{
        $credit_card_type = 'NOT SUPPORTED!';
    }

    return $credit_card_type;
  }
