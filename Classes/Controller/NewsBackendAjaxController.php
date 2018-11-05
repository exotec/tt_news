<?php
namespace RG\TtNews\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RG\TtNews\Module\NewsAdminModule;
use TYPO3\CMS\Core\Http\HtmlResponse;


class NewsBackendAjaxController
{
    /**
     * The local configuration array
     *
     * @var array
     */
    protected $conf;


    /**
     * The constructor of this class
     */
    public function __construct()
    {


    }

    /**
     * The main dispatcher function. Collect data and prepare HTML output.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws \Doctrine\DBAL\DBALException
     * @throws \TYPO3\CMS\Core\Exception\SiteNotFoundException
     */
    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getQueryParams();

        $this->conf = [
            'category' => $parsedBody['category'] ?? null,
            'pid' => (int)$parsedBody['pid'] ?? null,
            'action' => $parsedBody['action'] ?? null,
        ];

        $response = new HtmlResponse('');

        $content = '';

        // Determine the scripts to execute
        switch ($this->conf['action']) {
            case 'loadList':
                $content .= $this->loadList();
                break;

            default:
                $content .= 'no action given';
        }
        $response->getBody()->write($content);

        return $response;
    }

    /**
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     * @throws \TYPO3\CMS\Core\Exception\SiteNotFoundException
     */
    private function loadList()
    {
        $module = new NewsAdminModule();
        $content = $module->ajaxLoadList($this->conf);
        return $content;
    }

}