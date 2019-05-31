<?php 

	function sendEmail($data){



			$mailAccounts = [
						
						'info@posteayvende.com' => 
						[
							'host' 	=> 'smtp.socketlabs.com',
							'port' 	=> 465,
							'ssl' 	=> 'ssl',
							'userName' 	=> 'server26291',
							'password' => 'd5RBe62CsKc7j8NYz'
						]
				];


			$transport = new Swift_SmtpTransport('smtp.socketlabs.com',465,'ssl');
            $transport->setUsername('server26291');
            $transport->setPassword('d5RBe62CsKc7j8NYz');

            $mailer = new Swift_Mailer($transport);

            $message = new Swift_Message('Notificacion');
            $message->setFrom ('support@rdcasting.com')
           ->setTo (array($data['to'] => 'Recipient'))
           ->setSubject ('RDcasting.com')
           ->setBody ($data['emailbody'].'RDcasting', 'text/html');
            $mailer->send($message);
	}


?>