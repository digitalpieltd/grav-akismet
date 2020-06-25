<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class AkismetPlugin
 * @package Grav\Plugin
 */
class AkismetPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            ['autoload', 100000], // TODO: Remove when plugin requires Grav >=1.7
            'onFormProcessed' => ['onFormProcessed', 0]
        ];
    }

    /**
    * Composer autoload.
    *is
    * @return ClassLoader
    */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }
    
    public function onFormProcessed(Event $event)
	{
		$form = $event['form'];
		$action = $event['action'];
		$params = $event['params'];
		
		if($action !== 'akismet')
			return;
		
		// Check if we have an API key configured
		$key = $this->config->get('plugins.akismet.akismet_key');
		
		if(!$key)
			return;
		
		$data = [
			'blog' => $this->grav['uri']->host(),
			'user_ip' => $this->grav['uri']->ip(),
			'user_agent' => $_SERVER['HTTP_USER_AGENT'],
			'referrer' => $_SERVER['HTTP_REFERER'],
			//'permalink' => '' We can't reliably get this
			'comment_type' => 'contact-form',
			'comment_author' => $form->data[$params['name']],
			'comment_author_email' => $form->data[$params['email']],
			'comment_content' => $form->data[$params['content']],
			'is_test' => $this->config->get('plugins.akismet.is_test'),
		];
		
		$curl = curl_init("https://$key.rest.akismet.com/1.1/comment-check");
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, \Grav\Framework\Uri\UriFactory::buildQuery($data));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$result = curl_exec($curl);
		
		if(!$result) {
			$event->stopPropagation();
			$this->grav['log']->info('Contact form submission failed Akismet spam check. Spam author: ' . $form->data[$params['email']]);
			return;
		}
		
		return;
	}
}
