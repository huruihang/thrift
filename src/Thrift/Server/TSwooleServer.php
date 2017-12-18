<?php

namespace Thrift\Server;

use Thrift\Exception\TTransportException;


class TSwooleServer
{

    protected $transport_;
    protected $callbackList_;

    public function __construct(TServerTransport $transport, array $callbackList)
    {
        $this->transport_ = $transport;
        $this->callbackList_ = $callbackList;
    }

    public function serve()
    {
        $this->transport_->listen();
        foreach ($this->callbackList_ as $k => $v) {
            $this->transport_->addCallback($k, $v);
        }
        $this->transport_->accept();
    }

    public function stop()
    {
        $this->transport_->close();
    }
}
