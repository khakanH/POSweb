<?php

namespace App\Traits;
use Mail;


trait CommonTrait
{   


  public function SendMailVerification($username,$code,$email,$text_notes)
  {

    if (empty($username)) 
    {
      $username = "Dear";
    }


    $data = array('name'=>$username,'code'=>$code,'text_notes'=>$text_notes);
    Mail::send('mail', $data, function($message) use ($email,$username){
                                                    $message->to($email, $username)
                                                            ->subject('Verification Code');
                                                  });
    return ;
  }

}