<?php

namespace Thrift\Transport;

use Thrift\Exception\TException;
use Thrift\Factory\TStringFuncFactory;


class TSwooleTransport extends TTransport
{
    private $request = null;
    private $response = null;
    private $buf_ = '';

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function isOpen()
    {
    }

    public function open()
    {
        $this->buf_ = $this->request->rawContent();
    }

    public function close()
    {
        $this->buf_ = '';
    }

    public function read($len)
    {
        $bufLength = TStringFuncFactory::create()->strlen($this->buf_);

        if ($bufLength === 0) {
            throw new TTransportException('TSwooleTransport: Could not read ' .
                $len . ' bytes from buffer.',
                TTransportException::UNKNOWN);
        }

        if ($bufLength <= $len) {
            $ret = $this->buf_;
            $this->buf_ = '';

            return $ret;
        }

        $ret = TStringFuncFactory::create()->substr($this->buf_, 0, $len);
        $this->buf_ = TStringFuncFactory::create()->substr($this->buf_, $len);

        return $ret;
    }

    public function write($buf)
    {
        $this->buf_ .= $buf;
    }

    public function flush()
    {
        $this->response->end($this->buf_);
    }

}
