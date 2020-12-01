<?php

namespace App\Traits;
use Mail;

use App\Models\Notification;



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



  public function SaveNotification($receiver_type,$sender_id,$receiver_id,$title,$description,$type)
  {
      date_default_timezone_set('Asia/Karachi');

          if(Notification::insert(array(
                                                                "receiver_type"     => $receiver_type ,
                                                                "sender_id"         => $sender_id ,
                                                                "receiver_id"       => $receiver_id ,
                                                                "title"             => $title ,
                                                                "description"       => $description ,
                                                                "type"              => $type ,
                                                                "is_new"            => 1,
                                                                "is_deleted"        => 0,
                                                                "created_at"        =>  date("Y-m-d H:i:s"),
                                                                "updated_at"        =>  date("Y-m-d H:i:s"),
                                                           )))
          {
            return 1;
          }
          else
          {
            return 0;
          }

  }

    public function getTimeLapse($ptime)
    {
      $etime = time() - strtotime($ptime);

    if ($etime < 50)
    {
        return 'Just Now';
    }

    $a = array( 365 * 24 * 60 * 60  =>  'year',
                 30 * 24 * 60 * 60  =>  'month',
                      24 * 60 * 60  =>  'day',
                           60 * 60  =>  'hour',
                                60  =>  'minute',
                                 1  =>  'second'
                );
    $a_plural = array( 'year'   => 'years',
                       'month'  => 'months',
                       'day'    => 'days',
                       'hour'   => 'hours',
                       'minute' => 'minutes',
                       'second' => 'seconds'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
        }
    }
    }


}