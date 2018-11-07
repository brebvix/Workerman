<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace brebvix\Connection;

use MongoDB\BSON\ObjectId;

/**
 * ConnectionInterface.
 */
abstract class  ConnectionInterface
{
    private $_authorized = false;
    private $_user_id;
    private $_identifier;
    private $_socket_id;

    /**
     * Statistics for status command.
     *
     * @var array
     */
    public static $statistics = array(
        'connection_count' => 0,
        'total_request' => 0,
        'throw_exception' => 0,
        'send_fail' => 0,
    );

    /**
     * Emitted when data is received.
     *
     * @var callback
     */
    public $onMessage = null;

    /**
     * Emitted when the other end of the socket sends a FIN packet.
     *
     * @var callback
     */
    public $onClose = null;

    /**
     * Emitted when an error occurs with connection.
     *
     * @var callback
     */
    public $onError = null;

    /**
     * Sends data on the connection.
     *
     * @param string $send_buffer
     * @return void|boolean
     */
    abstract public function send($send_buffer);

    /**
     * Get remote IP.
     *
     * @return string
     */
    abstract public function getRemoteIp();

    /**
     * Get remote port.
     *
     * @return int
     */
    abstract public function getRemotePort();

    /**
     * Get remote address.
     *
     * @return string
     */
    abstract public function getRemoteAddress();

    /**
     * Get local IP.
     *
     * @return string
     */
    abstract public function getLocalIp();

    /**
     * Get local port.
     *
     * @return int
     */
    abstract public function getLocalPort();

    /**
     * Get local address.
     *
     * @return string
     */
    abstract public function getLocalAddress();

    /**
     * Is ipv4.
     *
     * @return bool
     */
    abstract public function isIPv4();

    /**
     * Is ipv6.
     *
     * @return bool
     */
    abstract public function isIPv6();

    /**
     * Close connection.
     *
     * @param $data
     * @return void
     */
    abstract public function close($data = null);

    /**
     * Запоминает ID пользователя и ставит соответствующий статус
     *
     * @param ObjectId $user_id
     * @return bool
     */
    public function authorized(ObjectId $user_id): bool
    {
        return ($this->_authorized = true) && ($this->_user_id = $user_id);
    }

    /**
     * Помечает пользователя как неавторизованного
     *
     * @return bool
     */
    public function logout(): bool
    {
        return ($this->_user_id = null) && ($this->_authorized = false);
    }

    /**
     * Указывает идентификатор пользователя
     *
     * @param string $identifier
     * @return bool
     */
    public function setIdentifier(string $identifier): bool
    {
        return ($this->_identifier = $identifier);
    }

    /**
     * Указывает socket_id пользователя
     *
     * @param int $socket_id
     * @return bool
     */
    public function setSocketId(int $socket_id): bool
    {
        return ($this->_socket_id = $socket_id);
    }

    /**
     * Проверяет, авторизован ли пользователь
     *
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->_authorized;
    }

    /**
     * Возвращает user_id пользователя, если тот авторизован
     *
     * @return bool|ObjectId
     */
    public function getUserId()
    {
        if ($this->isAuthorized()) {
            return $this->_user_id;
        }

        return false;
    }

    /**
     * Возвращает идентификатор пользователя
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }

    /**
     * Возвращает socket_id пользователя
     *
     * @return mixed
     */
    public function getSocketId()
    {
        return $this->_socket_id;
    }
}
