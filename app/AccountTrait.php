<?php

namespace App;

trait AccountTrait
{
  /*
   * To store accounts against a model
   *
   *@
   */
  public function storeAccounts($hotel, $accounts)
  {
    $hotel = $hotel instanceof Hotel ? $hotel : (Hotel::find($hotel));
    if($accounts) {
      foreach($accounts as $accounts) {
        Account::create([
          'hotel_id'      =>  $hotel->id,
          'subject_id'    =>  $this->id,
          'subject_type'  =>  get_class($this),
          'acc_no'        =>  $accounts['acc_no'] ? $accounts['acc_no'] : '',
          'acc_name'      =>  $accounts['acc_name'] ? $accounts['acc_name'] : '',
          'ifsc_code'     =>  $accounts['ifsc_code'] ? $accounts['ifsc_code'] : '',
          'branch'        =>  $accounts['branch'] ? $accounts['branch'] : ''
        ]);
      }
    } 
  }

  /*
   * To update accounts against a model
   *
   *@
   */
  public function updateAccounts($hotel, $accounts)
  {
    if($accounts) {
      foreach($accounts as $account) {
        optional(Account::where('id', '=', $account['id'])->first())->update($account);
      }
    }
  }
}