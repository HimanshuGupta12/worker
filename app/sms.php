<?php

function getSmsText($worker)
{
    if ( $worker->language_settings =='English')
    {
        $text = 'Hello ' . $worker->first_name . ',
Your employer inviting you to  WorkerNU, time registration and tools management system.
You can access your profile by clicking on this link:
' . $worker->workerLink();
                welcomesms($worker->phone(), $text);
                $success = 'SMS with a login link was sent to the worker';
                
    }
    elseif( $worker->language_settings =='Russian')
    {
        $text = 'Hello ' . $worker->first_name . ',
Your employer inviting you to WorkerNU, time registration and tools management system.
You can access your profile by clicking on this link: 
' . $worker->workerLink();
                welcomesms($worker->phone(), $text);
                $success = 'SMS with a login link was sent to the worker';
                
    }
    elseif( $worker->language_settings =='Lithuanian')
    {
        $text = 'Labas ' . $worker->first_name . ',
Tavo vadovas pakvietė tave į WorkerNU, valandų registravimo ir įrankių kontrolės programą. 
Savo profilį galite pasiekti paspaudę šią nuorodą: 
' . $worker->workerLink();
                welcomesms($worker->phone(), $text);
                $success = 'SMS with a login link was sent to the worker';
    }
    else
    {
        $text = 'Hello ' . $worker->first_name . ',
Your employer inviting you to  WorkerNU, time registration and tools management system. 
You can access your profile by clicking on this link: 
' . $worker->workerLink();
                welcomesms($worker->phone(), $text);
                $success = 'SMS with a login link was sent to the worker';
    }

}

// ============Messages translation holiday/sickness msg, updateinfo to worker=========

function detailSmsText($worker)
{
    if ( $worker->language_settings =='English')
    {
        $text = 'Hello '.$worker->fullName() .', '. $worker->company->name.' requests you to update your worker profile. Do that by clicking here: ' . $worker->informationLink();
        sms($worker->phone(), $text);
    } 
    elseif( $worker->language_settings =='Lithuanian')
    {
        $text = 'Labas '.$worker->fullName().', '. $worker->company->name.' prašo tavęs pridėti daugiau informacijos apie save. Informaciją atnaujinti gali paspaudęs čia: '.$worker->informationLink();
        sms($worker->phone(), $text);
    }
    elseif( $worker->language_settings =='Russian')
    {
        $text = 'Привет '.$worker->fullName().', '. $worker->company->name.' просит тебя добавить дополнительную информацию о себе. Ты можешь обновить информацию, нажав здесь: '.$worker->informationLink();
        sms($worker->phone(), $text);
    }
}

function holidayApproved($worker,$date_from,$date_to,$days)
{
    if ( $worker->language_settings =='English')
    {
        $text ='Hello '. $worker->fullName() .', your holidays were approved. You will be on holidays from: '.$date_from.' to '.$date_to.'. Total: '.$days.'d. Have a nice holiday. '. $worker->company->name;
        sms($worker->phone(), $text);

    }
    elseif( $worker->language_settings =='Lithuanian')
    {
         $text ='Sveiki ' . $worker->fullName() . ', jūsų atostogos buvo patvirtintos. Pirma atostogų diena: '.$date_from. ', paskutinė atostogų diena: '.$date_to.' Viso: '.$days.' dienos(-/ų). Gražių atostogų. '.$worker->company->name;
        sms($worker->phone(), $text);

    }
    elseif( $worker->language_settings =='Russian')
    {
         $text ='Привет ' . $worker->fullName() . ', ваш отпуск подтвержден. Первый день отпуска: '.$date_from. ', последний день отпуска: '.$date_to. ' Всего: '.$days.' день/дней. Хорошего отпуска. '.$worker->company->name;
        sms($worker->phone(), $text);

    }
}

function holidayNotApproved($worker,$date_from,$date_to)
{
    if ( $worker->language_settings =='English')
    {
        $text ='Hello '. $worker->fullName().', your holidays from: '.$date_from.' to '.$date_to.' were NOT approved. '.$worker->company->name;
        sms($worker->phone(), $text);

    }
    elseif( $worker->language_settings =='Lithuanian')
    {
         $text ='Sveiki ' . $worker->fullName().', jūsų atostogos nuo: ' .$date_from. ' iki ' .$date_to. ' nepatvirtintos. '.$worker->company->name;
        sms($worker->phone(), $text);

    }
    elseif( $worker->language_settings =='Russian')
    {
         $text ='Привет '. $worker->fullName() . ', ваш отпуск от: ' .$date_from. ' до ' .$date_to. ' не одобрен. '.$worker->company->name;
        sms($worker->phone(), $text);

    }
}
