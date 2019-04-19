<?php

/**
 * @package      Oneall PHP SDK
 * @copyright    Copyright 2017-Present http://www.oneall.com
 * @license      GNU/GPL 2 or later
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
 *
 * The "GNU General Public License" (GPL) is available at http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Oneall\Api\Apis;

use Oneall\Api\AbstractApi;
use Oneall\Api\Pagination;

/**
 * Class Sharing
 *
 * @package Oneall\Api\Apis
 */
class Sharing extends AbstractApi
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sharing';
    }

    // ****************
    // Share Icons
    // ****************

    /**
     * List All Shared Pages
     *
     * @see http://docs.oneall.com/api/resources/sharing/pages/list-all-pages/
     *
     * @param \Oneall\Api\Pagination|null $pagination
     *
     * @return \Oneall\Client\Response
     */
    public function getAll(Pagination $pagination = null)
    {
        if (!$pagination)
        {
            $pagination = new Pagination();
        }

        return $this->getClient()->get('/sharing/pages.json?' . $pagination->build());
    }

    /**
     * Get page details by its token
     *
     * @param string                      $sharing_page_token
     * @param \Oneall\Api\Pagination|null $pagination
     *
     * @see http://docs.oneall.com/api/resources/sharing/pages/read-page-details/
     *
     * @return \Oneall\Client\Response
     */
    public function getPageByToken($sharing_page_token, Pagination $pagination = null)
    {
        if (!$pagination)
        {
            $pagination = new Pagination();
        }

        return $this->getClient()->get('/sharing/pages/' . $sharing_page_token . '.json?' . $pagination->build());
    }

    /**
     * Get page details by its url
     *
     * @param string $url
     *
     * @see http://docs.oneall.com/api/resources/sharing/pages/read-page-details/
     *
     * @return \Oneall\Client\Response
     */
    public function getPageByUrl($url)
    {
        return $this->getClient()->get('/sharing/pages/page.json?page_url=' . $url);
    }

    // ****************
    // Advanced Sharing API
    // ****************

    /**
     * List All Published Messages
     *
     *
     * @see http://docs.oneall.com/api/resources/social-sharing/list-all-messages/
     *
     * @return \Oneall\Client\Response
     */
    public function getMessages(Pagination $pagination = null)
    {
        if (!$pagination)
        {
            $pagination = new Pagination();
        }

        return $this->getClient()->get('/sharing/messages.json?' . $pagination->build());
    }

    /**
     * Publish To Social Networks
     *
     * @param string $userToken      The unique token of the user to post the content for.
     * @param array  $providers      providers list : ['facebook', 'twitter']
     * @param string $text
     * @param string $videoUrl
     * @param string $pictureUrl
     * @param array  $link
     * @param array  $uploads
     * @param bool   $enableTracking A flag to turn on/off the automatic shortening of URLs included in the post.
     *
     * @see http://docs.oneall.com/api/resources/social-sharing/publish-new-message/
     *
     * @return \Oneall\Client\Response
     */
    public function publish(
        $userToken,
        array $providers,
        $text,
        $videoUrl = null,
        $pictureUrl = null,
        array $link = [],
        array $uploads = [],
        $enableTracking = true
    )
    {
        $data = [
            "request" => [
                "sharing_message" => [
                    'publish_for_user' => [
                        'user_token' => $userToken,
                        'providers' => $providers
                    ],
                    "parts" => [
                        "text" => ["body" => $text],
                        "flags" => ['enable_tracking' => $enableTracking ? 1 : 0]
                    ]
                ]
            ]
        ];

        $data = $this->addInfo($data, 'request/sharing_message/parts/video/url', $videoUrl);
        $data = $this->addInfo($data, 'request/sharing_message/parts/picture/url', $pictureUrl);
        $data = $this->addInfo($data, 'request/sharing_message/parts/uploads', $uploads);
        $data = $this->addInfo($data, 'request/sharing_message/parts/link', $link);

        return $this->getClient()->post('/sharing/messages.json', $data);
    }

    /**
     * Publish To Social Networks
     *
     * @param string $identityToken  Publish for this identity
     * @param string $text
     * @param string $videoUrl
     * @param string $pictureUrl
     * @param array  $link
     * @param array  $uploads
     * @param bool   $enableTracking A flag to turn on/off the automatic shortening of URLs included in the post.
     *
     * @see http://docs.oneall.com/api/resources/social-sharing/publish-new-message/
     *
     * @return \Oneall\Client\Response
     */
    public function publishForIdentity(
        $identityToken,
        $text,
        $videoUrl = null,
        $pictureUrl = null,
        array $link = [],
        array $uploads = [],
        $enableTracking = true
    )
    {
        $data = [
            "request" => [
                "sharing_message" => [
                    'publish_for_identity' => array(
                        'identity_token' => $identityToken
                    ),
                    "parts" => [
                        "text" => ["body" => $text],
                        "flags" => ['enable_tracking' => $enableTracking ? 1 : 0]
                    ]
                ]
            ]
        ];

        $data = $this->addInfo($data, 'request/sharing_message/parts/video/url', $videoUrl);
        $data = $this->addInfo($data, 'request/sharing_message/parts/picture/url', $pictureUrl);
        $data = $this->addInfo($data, 'request/sharing_message/parts/uploads', $uploads);
        $data = $this->addInfo($data, 'request/sharing_message/parts/link', $link);

        return $this->getClient()->post('/sharing/messages.json', $data);
    }

    /**
     * Read Details Of A Message
     *
     * @param string $messageToken
     *
     * @see http://docs.oneall.com/api/resources/social-sharing/read-message/
     *
     * @return \Oneall\Client\Response
     */
    public function getMessageDetails($messageToken)
    {
        return $this->getClient()->get('/sharing/messages/' . $messageToken . '.json');
    }

    /**
     * Re-Publish A Message
     *
     * @param string $messageToken
     * @param string $userToken The unique token of the user to post the content for.
     * @param array  $providers providers list : ['facebook', 'twitter']
     *
     * @see http://docs.oneall.com/api/resources/social-sharing/re-publish-message/
     *
     * @return \Oneall\Client\Response
     */
    public function republish($messageToken, $userToken, array $providers)
    {
        $data = [
            "request" => [
                "sharing_message" => [
                    'publish_for_user' => [
                        'user_token' => $userToken,
                        'providers' => $providers
                    ]
                ]
            ]
        ];

        return $this->getClient()->post('/sharing/messages/' . $messageToken . '.json', $data);
    }

    /**
     * Re-Publish A Message for a single identity
     *
     * @param string $messageToken
     * @param string $identityToken The unique token of the user to post the content for.
     *
     * @see hhttp://docs.oneall.com/api/resources/social-sharing/re-publish-message/
     *
     * @return \Oneall\Client\Response
     */
    public function republishForIdentity($messageToken, $identityToken)
    {
        $data = [
            "request" => [
                "sharing_message" => [
                    'publish_for_identity' => [
                        'identity_token' => $identityToken
                    ]
                ]
            ]
        ];

        return $this->getClient()->post('/sharing/messages/' . $messageToken . '.json', $data);
    }

    /**
     * Delete Message
     *
     * @param string $messageToken
     *
     * @see http://docs.oneall.com/api/resources/social-sharing/delete-message/
     *
     * @return \Oneall\Client\Response
     */
    public function delete($messageToken)
    {
        $uri = '/sharing/messages/' . $messageToken . '.json?confirm_deletion=true';
        $response = $this->getClient()->delete($uri);

        return $response;
    }
}
