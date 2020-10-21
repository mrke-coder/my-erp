<?php


namespace App\Repositories;


class RhRepository
{

    public static function uploadFile($file, string $upload_type)
    {
       $nomfichier = null;
       $file_upload = null;
       for ($i=0; $i<5; $i++){
           $nomfichier .= mt_rand(1,9);
       }


       if ($upload_type === 'cv'){
           $nomfichier = 'cv_'.$nomfichier;
           $file_upload = $nomfichier.'.'.$file->extension();
           $file->move(public_path('uploads/cv/'),$file_upload);
       } elseif ($upload_type === 'lm'){
           $nomfichier = 'lm_'.$nomfichier;
           $file_upload = $nomfichier.'.'.$file->extension();
           $file->move(public_path('uploads/lm/'),$file_upload);
       } elseif ($upload_type === 'avatar'){
           $nomfichier = 'avatar_'.$nomfichier;
           $file_upload = $nomfichier.'.'.$file->extension();
           $file->move(public_path('uploads/avatar/'),$file_upload);
       }

       return $file_upload;
    }
    public static function deleteUploadFile($chemin)
    {
        if (file_exists($chemin)){
         unlink($chemin);
        }
    }
}
