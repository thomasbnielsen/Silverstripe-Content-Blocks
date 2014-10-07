<?php
 
class BlockController extends Controller {

	private static $allowed_actions = array(
		'form',
		'submit'
	);
	
	public function form(SS_HTTPRequest $request) {
/*		
		echo "<pre>";
		echo print_r($request);
		echo "<hr>";
		echo print_r($_POST);
		echo "</pre>";
		echo $_SERVER['HTTP_REFERER'];
*/		
		
		$data = $_POST;
		
		$email = new Email();
		
		$email->setTo('mail@nobrainer.dk');
		$email->setFrom($data['Email']);
		$email->setSubject("Contact Message from " . $data["Name"]);
		
		$messageBody = "
			<p><strong>Name:</strong> {$data['Name']}</p>
			<p><strong>Message:</strong> {$data['Message']}</p>
		";
		
		$email->setBody($messageBody);
		$email->send();
		
/*		return array(
			'Content' => '<p>Thank you for your feedback.</p>',
			'Form' => ''
		);
*/		
		$this->redirect($_SERVER['HTTP_REFERER'] . "?status=success");
		
	}	

}