<?php

namespace App\Http\Controllers;

/* Models */
use App\User;
use App\UsersVideos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

/* Views support*/
// use Illuminate\Support\Facades\View;

/* Mail support*/
// use \Swift_Mailer;
// use \Swift_SmtpTransport;
// use \Swift_Message;

class FileController extends Controller
{
    public static function upload($file)
    {
        $path = 'uploads/files';
        $newName = bin2hex(openssl_random_pseudo_bytes(16)).'.'.$file->getClientOriginalExtension();
            
        // Guardar las fotos en la carpeta
        $file->move($path,$newName);

        return $path.'/'.$newName;
    }

    public function upload3(Request $request)
    {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/../../../../systemdream2014-firebase-adminsdk-s609e-67ff32a0fe.json');
        $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
        $storage = $firebase->getStorage();
        $bucket = $storage->getBucket();
        $file = $request->file('file');

        $newName = ''.bin2hex(openssl_random_pseudo_bytes(16)).'.'.$file->getClientOriginalExtension();

        $bucket->upload(
            file_get_contents($file),
            [
                'name' => $newName
            ]
        );



        // Using Predefined ACLs to manage object permissions, you may
        // upload a file and give read access to anyone with the URL.
        $bucket->upload(
            fopen($file, 'r'),
            [
                'name' => $newName,
                'predefinedAcl' => 'publicRead'
            ]
        );


        // Download and store an object from the bucket locally.
        $object = $bucket->object($newName);
        $object->downloadToFile($newName);
        //return response()->json(['status'=>'upload Ok!'], 201);
    }

    public function upload2(Request $request)
    {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/../../../../systemdream2014-firebase-adminsdk-s609e-67ff32a0fe.json');
        $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
        $storage = $firebase->getStorage();
        $bucket = $storage->getBucket();
        $file = $request->file('file');

        $newName = ''.bin2hex(openssl_random_pseudo_bytes(16)).'.'.$file->getClientOriginalExtension();

        $bucket->upload(
            file_get_contents($file),
            [
                'name' => $newName
            ]
        );

        return response()->json(['status'=>'upload Ok!'], 201);
    }

    public function image(Request $request)
    {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/../../../systemdream2014-firebase-adminsdk-s609e-67ff32a0fe.json');

        $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();

        $storage = $firebase->getStorage();

        $bucket = $storage->getBucket();

        foreach ($request->file('file') as $photo)
        {   
            $newName = 'images/124/'.bin2hex(openssl_random_pseudo_bytes(16)).'.'.$photo->getClientOriginalExtension();

            $bucket->upload(
                file_get_contents($photo),
                [
                    'name' => $newName
                ]
            );

        }

        return response()->json(['status'=>'upload Ok!'], 201);
    }
}
