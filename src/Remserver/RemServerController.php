<?php

namespace Anax\RemServer;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;

/**
 * A controller for the REM Server.
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 */
class RemServerController implements InjectionAwareInterface
{
    use InjectionAwareTrait;



    /**
     * Start the session and initiate the REM Server.
     *
     * @return void
     */
    public function anyPrepare()
    {
        $session = $this->di->get("session");
        $rem     = $this->di->get("rem");

        $session->start();

        if (!$rem->hasDataset()) {
            $rem->init();
        }
    }



    /**
     * Init or re-init the REM Server.
     *
     * @return void
     */
    public function anyInit()
    {
        $this->di->get("rem")->init();
        $this->di->get("response")->sendJson(["message" => "The session is initiated with the default dataset."]);
        exit;
    }



    /**
     * Destroy the session.
     *
     * @return void
     */
    public function anyDestroy()
    {
        $this->di->get("session")->destroy();
        $this->di->get("response")->sendJson(["message" => "The session was destroyed."]);
        exit;
    }



    /**
     * Get the dataset or parts of it.
     *
     * @param string $key for the dataset
     *
     * @return void
     */
    public function getDataset($key)
    {
        $request = $this->di->get("request");

        $dataset = $this->di->get("rem")->getDataset($key);
        $offset  = $request->getGet("offset", 0);
        $limit   = $request->getGet("limit", 25);
        $res = [
            "data" => array_slice($dataset, $offset, $limit),
            "offset" => $offset,
            "limit" => $limit,
            "total" => count($dataset)
        ];

        $this->di->get("response")->sendJson($res);
        exit;
    }



    /**
     * Get one item from the dataset.
     *
     * @param string $key    for the dataset
     * @param string $itemId for the item to get
     *
     * @return void
     */
    public function getItem($key, $itemId)
    {
        $response = $this->di->get("response");

        $item = $this->di->get("rem")->getItem($key, $itemId);
        if (!$item) {
            $response->sendJson(["message" => "The item is not found."]);
            exit;
        }

        $response->sendJson($item);
        exit;
    }



    /**
     * Create a new item by getting the entry from the request body and add
     * to the dataset.
     *
     * @param string $key    for the dataset
     *
     * @return void
     */
    public function postItem($key)
    {
        $entry = $this->di->get("request")->getBody();
        $entry = json_decode($entry, true);

        $item = $this->di->get("rem")->addItem($key, $entry);
        $this->id->get("response")->sendJson($item);
        exit;
    }


    /**
     * Upsert/replace an item in the dataset, entry is taken from request body.
     *
     * @param string $key    for the dataset
     * @param string $itemId where to save the entry
     *
     * @return void
     */
    public function putItem($key, $itemId)
    {
        $entry = $this->di->get("request")->getBody();
        $entry = json_decode($entry, true);

        $item = $this->di->get("rem")->upsertItem($key, $itemId, $entry);
        $this->di->get("response")->sendJson($item);
        exit;
    }



    /**
     * Delete an item from the dataset.
     *
     * @param string $key    for the dataset
     * @param string $itemId for the item to delete
     *
     * @return void
     */
    public function deleteItem($key, $itemId)
    {
        $this->di->get("rem")->deleteItem($key, $itemId);
        $this->di->get("response")->sendJson(null);
        exit;
    }



    /**
     * Show a message that the route is unsupported, a local 404.
     *
     * @return void
     */
    public function anyUnsupported()
    {
        $this->di->get("response")->sendJson(["message" => "404. The api/ does not support that."], 404);
        exit;
    }
}
