<?php

/**
 * @file
 * CCK Field for Australian phone numbers.
 */

 function phone_th_metadata() {
   return array(
     'error' => '"%value" is not a valid Thai phone number<br>Thai phone numbers should contain only numbers with an optional prefix of "+66"',
   );
 }

/**
 * Verification for Thai Phone Numbers.
 *
 * @param string $phonenumber
 * @return boolean Returns boolean FALSE if the phone number is not valid.
 */
function valid_th_phone_number($phonenumber) {

  //$phonenumber = trim($phonenumber);

  // strip formatting chars
  $phonenumber = preg_replace('/[\-() ]/', '', $phonenumber);
  // strip optional '+66' or '0' prefixes
  $phonenumber = preg_replace('/^(\+66|0)/', '', $phonenumber);
  // strip extensions x9999
  $phonenumber = preg_replace('/x.*$/', '', $phonenumber);

  //$rules[] = array("Prefix","Minimum length","Maximum length");
  $rules[] = array("2", 8, 8);
  $rules[] = array("3", 8, 8);
  $rules[] = array("4", 8, 8);
  $rules[] = array("5", 8, 8);
  $rules[] = array("7", 8, 8);
  // mobile
  $rules[] = array("8", 9, 9);
  // VOIP
  $rules[] = array("6", 9, 9);
   
 
  foreach ($rules as $rule) {
    if (preg_match('/^'.$rule[0].'/', $phonenumber) && strlen($phonenumber) >= $rule[1] && strlen($phonenumber) <= $rule[2]) {
      return TRUE;
    }
  }
  return FALSE;
}

/**
 * Formatting for Thai Phone Numbers.
 *
 * @param string $phonenumber
 * @return string Returns a string containing the phone number with some formatting.
 */
function format_th_phone_number($phonenumber, $field = FALSE) {
  $prefix = '';
  $extension = '';
  // strip old formatting chars
  $phonenumber = preg_replace('/[\-() ]/', '', $phonenumber);

  /*
   * strip and save the +66 prefix if found
   */
  if (preg_match('/^\+66/', $phonenumber, $match)) {
    $prefix = '+66 ';
    $phonenumber = str_replace('+66', '', $phonenumber);
  }

  /*
   * strip and save the extension (x9999) postfix if found
   */
  if (preg_match('/(x[0-9]+)$/', $phonenumber, $match)) {
    $extension = ' ('.$match[1].')';
    $phonenumber = preg_replace('/x[0-9]+$/', '', $phonenumber);
  }

  /*
   * geographic numbers and UPT
   * Eg. (02) 9999 9999 or +66 (2) 9999 9999
   */
  if (preg_match('/^(0{0,1}[234578])([0-9]{3})([0-9]{4})$/', $phonenumber, $match)) {
    return $prefix . '(' . $match[1] . ') ' . $match[2] . ' ' . $match[3] . $extension;
  }

  /*
   * mobile numbers
   * Eg. 081 999 9999 or +66 81 999 9999
   */
  if (preg_match('/^(0{0,1}8[0-9]{1})([0-9]{3})([0-9]{4})$/', $phonenumber, $match)) {
    return $prefix . $match[1] . ' ' . $match[2] . ' ' . $match[3] . $extension;
  }

  // default
  return $prefix . $phonenumber . $extension;
}

