<?php

namespace Thrift\Server;

use Thrift\Transport\TSocket;

class TSwooleTransport extends TServerTransport
{
    protected $listener_;

    protected $port_;

    protected $host_;

    public function __construct($host = '0.0.0.0', $port = 80)
    {
        $this->host_ = $host;
        $this->port_ = $port;
    }

    public function listen()
    {
        $this->listener_ = new \Swoole\Http\Server($this->host_, $this->port_, SWOOLE_BASE);
    }

    public function close()
    {
        $this->listener_->stop(-1, true);
        $this->listener_ = null;
    }

    public function addCallback($funName, $funBody)
    {
        $this->listener_->on($funName, $funBody);
    }

    protected function acceptImpl()
    {
        return $this->listener_->start();
    }
}
