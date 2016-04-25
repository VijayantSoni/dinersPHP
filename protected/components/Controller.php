<?php
Yii::import('application.vendors.*');
spl_autoload_unregister(array('YiiBase', 'autoload'));
require_once('twilio/Services/Twilio.php');
spl_autoload_register(array('YiiBase', 'autoload'));
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/indexlayout';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	protected function sendSMS($to, $textmessage) {
		$sid = "AC683b6a55bc0f961e3b48c7963a6cef35";
		$token = "e79424d7746235598e3a689353f9800a";
		$from = '+12037796145';
		$client = new Services_Twilio($sid, $token);
		try {
			$message = $client->account->sms_messages->create(
			  $from, // From a valid Twilio number
			  $to, // Text this number
			  $textmessage //message
			);
			$twilioMessageResponse = $message->sid;
		}
		catch (Exception $e) {
			$error = $e->getMessage();
		}
	}

	protected function mailsend($to,$from,$from_name,$subject,$message)
	{
		$mail=Yii::app()->Smtpmail;
		$mail->SetFrom($from, $from_name);
		$mail->Subject = $subject;
		$mail->MsgHTML($message);
		$mail->AddAddress($to, "");
		$mail->Send();
		// if(!$mail->Send()) {
	 //    return false;
		// }else {
	 //    return true;
		// }
	}
}