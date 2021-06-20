<?php

  #
  # Returns the float representation of a mixed variable
  #
  function int_to_float($mixed){
    if(!intval($mixed) && !floatval($mixed)) return 0x0;
    return (int)$mixed / (float)$mixed == 1 ? (string)$mixed . '.0' : $mixed;
  }
