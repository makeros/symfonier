<?php
/**
 * IMPORTANT! : userSettings structure should be change only in two places: the dict variable
 * in UserSettings class and in the Document/UserSettings
 * - arek
 */
namespace Symfonier\ApiBundle\Utils;

use Symfonier\UserBundle\Document\User as User;
use Symfonier\ApiBundle\Document\UserSettings as UserSettingsModel;

class UserSettings 
{

	private $dManager;
	private $dict = [
		// general - email notifikations
		'alarmEmail' => array(
			'immediately', 'oncePerDay', 'none'
		),
		'newsletterEmail' => array(
			'immediately', 'oncePerDay', 'none'
		),
		'managerImportantPostEmail' => array(
			'immediately', 'oncePerDay', 'none'
		),
		'managerNewPostEmail' => array(
			'immediately', 'oncePerDay', 'none'
		),
		'managerNewEventEmail' => array(
			'immediately', 'oncePerDay', 'none'
		),
		'thankYouMyPost' => array(
			'immediately', 'oncePerDay', 'none'
		),
		'thankYouMyComment' => array(
			'immediately', 'oncePerDay', 'none'
		),
		'replyMyPost' => array(
			'immediately', 'oncePerDay', 'none'
		),
		'replyMyReply' => array(
			'immediately', 'oncePerDay', 'none'
		),
		'messageFromUser' => array(
			'immediately', 'oncePerDay', 'none'
		),
		'messageFromManager' => array(
			'immediately', 'oncePerDay', 'none'
		),
		'invitationToGroup' => array(
			'immediately', 'oncePerDay', 'none'
		),
		'invitationToEvent' => array(
			'immediately', 'oncePerDay', 'none'
		),
		//privacy - visibility
		'showMyName' => array(
			'full','short'
		),
		'hideProfileInfo' => array(
			'true','false'
		),
		'hideAvatar' => array(
			'true','false'
		),
		'phoneNumber' => array(
			'visibleToOthers','hidden'
		),
		'showAddressAs' => array(
			'full','withoutNumber', 'onlyHousing'
		),
		'showGroups' => array(
			'all','global', 'hide'
		),
		'showActivity' => array(
			'statistic','hide'
		)
	];

	private $defaultSettings = [
		// general - email notifikations
		'alarmEmail' => 'immediately',
		'newsletterEmail' => 'immediately',
		'managerImportantPostEmail' => 'immediately',
		'managerNewPostEmail' => 'immediately',
		'managerNewEventEmail' => 'immediately',
		'thankYouMyPost' => 'immediately',
		'thankYouMyComment' => 'immediately',
		'replyMyPost' => 'immediately',
		'replyMyReply' => 'immediately',
		'messageFromUser' => 'immediately',
		'messageFromManager' => 'immediately',
		'invitationToGroup' => 'immediately',
		'invitationToEvent' => 'immediately',
		//privacy - visibility
		'showMyName' => 'full',
		'hideProfileInfo' => 'true',
		'hideAvatar' => 'true',
		'phoneNumber' => 'visibleToOthers',
		'showAddressAs' => 'full',
		'showGroups' => 'all',
		'showActivity' => 'statistic'
	];

	private $userSettings;

	private function settingsToArray($obj)
	{
		$settingsAsArray = Array();
		$settingsAsArray['id'] = 'current';

		if ( is_object($obj) )
		{

			foreach ($this->dict as $key => $options)
			{
				if ( $value = $obj->get($key) )
				{
					if ( in_array($value, $options) )
					{
						$settingsAsArray[$key] = $value;
					}
				}

			}

			return $settingsAsArray;
		}

		return $this->userSettings;

	}

	private function getSettingsFromDB($user = null)
	{
		$user = ($user instanceof User)? $user : $this->user;
		// db.UserSettings.find('user.$id' : user.id);
	}

	private function getSettings($user = null)
	{
		$user = ($user instanceof User)? $user : $this->user;

		return $user->getSettings();
	}

	/**
	 * Validate setings before saving
	 * It validates the settings agains the $this->dict array
	 * Two level array!
	 */
	private function validateSettings($settings)
	{

		foreach ($settings as $key => $value)
		{

			if ( array_key_exists($key, $this->dict) )
			{
				if (in_array($value, $this->dict[$key]))
				{
					continue;
				}
			}
			return false;
		}

		return true;
	}

	/**
	 * Save new settings
	 */
	private function saveSettingsToDB($user = null)
	{
		$user = ($user instanceof User)? $user : $this->user;

		// dont need to validate - this are defaults! :)

		$settings = new UserSettingsModel($user);

		foreach ($this->userSettings as $key => $option){

			$settings->set($key,$option);
		}

		$settings->setUpdatedAt(new \MongoDate());

		$this->dManager->persist($settings);

		$this->dManager->flush();
		
		return $settings;
	}

	/**
	 * Only update when they exist
	 */
	private function updateSettingsToDB($newSettings, $user = null)
	{
		$user = ($user instanceof User)? $user : $this->user;


		$updatedSettings = $this->dManager->getRepository('SymfonierApiBundle:UserSettings')
			->findOneBy(array(
				'user.$.id' => $user->getId()
			)
		);

		foreach ($newSettings as $key => $option){
			
			$updatedSettings->set($key,$option);

		}

		$updatedSettings->setUpdatedAt(new \MongoDate());

		$this->dManager->persist($updatedSettings);

		$this->dManager->flush();

		$this->userSettings = $newSettings;
		
		return $updatedSettings;

	}

	private function setDefaultSettingsToUser($user = null)
	{
		$user = ($user instanceof User)? $user : $this->user;

		$this->userSettings = $this->defaultSettings;

		$this->saveSettingsToDB($user);

	}

	public function __construct($dManager, User $user)
	{
		$this->user = $user;
		$this->dManager = $dManager;

		if ( !$user->getSettings() )
		{
			$this->setDefaultSettingsToUser($this->user);
			return $this;
		}

		$this->userSettings = $this->getSettings($user);
	}

	public function getAllAsArray()
	{
		return $this->settingsToArray($this->userSettings);
	}

	public function updateAll($arr)
	{
		if (is_array($arr))
		{
			if ( $this->validateSettings($arr)){

				return $this->settingsToArray( $this->updateSettingsToDB($arr) );

			}
		}

		return false;
	}

	public function get($key)
	{
		return $this->userSettings[$key];
	}

	public function set($key, $value)
	{

	}

	public function save()
	{

	}

}