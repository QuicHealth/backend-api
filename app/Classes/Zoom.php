<?php

namespace App\Classes;

use GuzzleHttp\Client;
use App\Models\ZoomToken;
use Exception;

class Zoom
{

    protected $CLIENT_ID;
    protected $CLIENT_SECRET;
    protected $REDIRECT_URI;
    protected $CLIENT;
    protected $ZOOM_ACCESS;
    protected $CREDENTIAL_DATA;

    public function __construct()
    {
        $this->CLIENT_ID = config('zoom.client_id');
        $this->CLIENT_SECRET = config('zoom.client_secret');
        $this->REDIRECT_URI = config('zoom.redirect_uri');
        $api_url = ['base_uri' => config('zoom.api_base_url')];
        $auth_url = ['base_uri' => config('zoom.auth_base_url')];

        $this->CLIENT = $this->newGuzzleHttp($api_url);
        $this->ZOOM_ACCESS = $this->newGuzzleHttp($auth_url);

        $this->CREDENTIAL_DATA = ZoomToken::find(1);
    }


    private function newGuzzleHttp(array $url)
    {
        $newGuzzleClient = new Client($url);

        return $newGuzzleClient;
    }

    public function oAuthUrl()
    {
        return "https://zoom.us/oauth/authorize?response_type=code&client_id={$this->CLIENT_ID}&redirect_uri={$this->REDIRECT_URI}";
    }

    public function token($code)
    {
        $response = $this->ZOOM_ACCESS->request('POST', '/oauth/token', [
            "headers" => [
                "Authorization" => "Basic " . base64_encode($this->CLIENT_ID . ':' . $this->CLIENT_SECRET),
                "Content-Type" => "application/x-www-form-urlencoded"
            ],
            'form_params' => [
                "grant_type" => "authorization_code",
                "code" => $code,
                "redirect_uri" => $this->REDIRECT_URI
            ],
        ]);

        $response_token = json_decode($response->getBody()->getContents(), true);

        $saveNewToken = ZoomToken::updateOrCreate(
            [
                'id' => 1
            ],

            [
                'access_token' => $response_token['access_token'],
                'refresh_token' => $response_token['refresh_token'],
                'expires_in' => $response_token['expires_in'],
                'token_type' => $response_token['token_type'],
                'scope' => $response_token['scope'],
            ]
        );

        if ($saveNewToken) {
            return ['status' => true, 'message' => 'Token saved successfully'];
        } else {
            return ['status' => false, 'message' => 'Token not saved'];
        }
    }

    public function refreshToken()
    {
        try {
            $findToken = ZoomToken::find(1);
            if (!$findToken) {
                return ['status' => false, 'message' => 'Token not found, Generate a new Token'];
            }
            $response = $this->ZOOM_ACCESS->request('POST', '/oauth/token', [
                "headers" => [
                    "Authorization" => "Basic " . base64_encode($this->CLIENT_ID . ':' . $this->CLIENT_SECRET),
                    "Content-Type" => "application/x-www-form-urlencoded"
                ],

                'form_params' => [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $findToken->refresh_token
                ],
            ]);


            $response_token = json_decode($response->getBody()->getContents(), true);


            $findToken->access_token = $response_token['access_token'];
            $findToken->refresh_token = $response_token['refresh_token'];
            $findToken->expires_in = $response_token['expires_in'];
            $findToken->token_type = $response_token['token_type'];
            $findToken->scope = $response_token['scope'];
            $updateToken = $findToken->save();

            if ($updateToken) {
                return ['status' => true, 'message' => 'Token Refreshed successfully'];
            } else {
                return ['status' => false, 'message' => 'Token not refreshed'];
            }
        } catch (Exception $e) {
            return 'Failed during refresh token ' . $e->getMessage();
        }
    }

    // https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meetings
    public function listMeeting($query = [], $user_id = 'me')
    {
        try {
            $response = $this->CLIENT->request('GET', "/v2/users/{$user_id}/meetings", [
                "headers" => [
                    "Authorization" => "Bearer " . $this->CREDENTIAL_DATA['access_token']
                ],
                'query' => $query
            ]);

            return array('status' => true, 'data' => json_decode($response->getBody(), true));
        } catch (Exception $e) {
            if ($e->getCode() == 401 && $this->refreshToken()) {
                return $this->listMeeting($user_id, $query);
            } else {
                return array('status' => false, 'message' => $e->getMessage());
            }
        }
    }

    public function createMeeting($json = [], $user_id = 'me')
    {
        try {
            $response = $this->CLIENT->request('POST', "/v2/users/{$user_id}/meetings", [
                "headers" => [
                    "Authorization" => "Bearer " . $this->CREDENTIAL_DATA['access_token']
                ],
                'json' => $json
            ]);

            if ($response->getStatusCode() == 201) {
                return array('status' => true, 'data' => json_decode($response->getBody(), true));
            }

            throw new Exception("Not able to find error");
        } catch (Exception $e) {
            if ($e->getCode() == 401 && $this->refreshToken()) {
                return $this->createMeeting($user_id, $json);
            }
            if ($e->getCode() == 300) {
                return array('status' => false, 'message' => 'Invalid enforce_login_domains, separate multiple domains by semicolon. A maximum of {rateLimitNumber} meetings can be created/updated for a single user in one day.');
            }
            if ($e->getCode() == 404) {
                return array('status' => false, 'message' => 'User {userId} not exist or not belong to this account.');
            }
            if ($e->getCode() != 401) {
                return array('status' => false, 'message' => $e->getMessage());
            }
            return array('status' => false, 'message' => 'Not able to refresh token');
        }
    }

    public function deleteMeeting($meeting_id = '', $query = [])
    {
        try {
            $response = $this->CLIENT->request('DELETE', "/v2/meetings/{$meeting_id}", [
                "headers" => [
                    "Authorization" => "Bearer " . $this->CREDENTIAL_DATA['access_token']
                ],
                'query' => $query
            ]);

            if ($response->getStatusCode() == 204) {
                return array('status' => true, 'message' => 'Meeting deleted.');
            }
            throw new Exception("Not able to find error");
        } catch (Exception $e) {
            if ($e->getCode() == 401 && $this->refreshToken()) {
                return $this->deleteMeeting($meeting_id, $query);
            }
            if ($e->getCode() == 400) {
                return array('status' => false, 'message' => 'User does not belong to this account or dont have access');
            }
            if ($e->getCode() == 404) {
                return array('status' => false, 'message' => 'Meeting with this {meetingId} is not found or has expired.');
            }
            if ($e->getCode() != 401) {
                return array('status' => false, 'message' => $e->getMessage());
            }
            return array('status' => false, 'message' => 'Not able to refresh token');
        }
    }


    public function addMeetingRegistrant($meeting_id = '', $json = [])
    {
        try {
            $response = $this->CLIENT->request('POST', "/v2/meetings/{$meeting_id}/registrants", [
                "headers" => [
                    "Authorization" => "Bearer " . $this->CREDENTIAL_DATA['access_token']
                ],
                'json' => $json
            ]);

            if ($response->getStatusCode() == 201) {
                return array('status' => true, 'message' => 'Registration successfull', 'data' => json_decode($response->getBody(), true));
            }

            throw new Exception("Not able to find error");
        } catch (Exception $e) {
            if ($e->getCode() == 401 && $this->refreshToken()) {
                return $this->addMeetingRegistrant($meeting_id, $json);
            }
            if ($e->getCode() == 300) {
                return array('status' => false, 'message' => 'Meeting {meetingId} is not found or has expired.');
            }
            if ($e->getCode() == 400) {
                return array('status' => false, 'message' => 'Access error. Not have correct access. validation failed');
            }
            if ($e->getCode() == 404) {
                return array('status' => false, 'message' => 'Meeting not found or Meeting host does not exist: {userId}.');
            }
            if ($e->getCode() != 401) {
                return array('status' => false, 'message' => $e->getMessage());
            }
            return array('status' => false, 'message' => 'Not able to refresh token');
        }
    }
}
