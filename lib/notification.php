<?php

class Notification
{

    private $title;

    private $message;

    private $image_url;

    private $action;

    private $action_destination;

    private $data;

    function __construct()
    {}

    function setTitle($title)
    {
        $this->title = $title;
    }

    function setMessage($message)
    {
        $this->message = $message;
    }

    function setImage($imageUrl)
    {
        $this->image_url = $imageUrl;
    }

    function setAction($action)
    {
        $this->action = $action;
    }

    function setActionDestination($actionDestination)
    {
        $this->action_destination = $actionDestination;
    }

    function setPayload($data)
    {
        $this->data = $data;
    }

    function getNotificatin()
    {
        $notification = array();
        $notification['title'] = $this->title;
        $notification['message'] = $this->message;
        $notification['image'] = $this->image_url;
        $notification['action'] = $this->action;
        $notification['action_destination'] = $this->action_destination;
        return $notification;
    }
}
?>
